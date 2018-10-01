<?php

namespace Controllers\Projects;

use DSI\Entity\OrganisationProject;
use DSI\Entity\Project;
use DSI\Entity\ProjectDsiFocusTag;
use DSI\Entity\ProjectImpactHelpTag;
use DSI\Entity\ProjectImpactTechTag;
use DSI\Entity\ProjectLink_Service;
use DSI\Entity\ProjectMember;
use DSI\Entity\ProjectPost;
use DSI\Entity\ProjectTag;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Repository\OrganisationProjectRepo;
use DSI\Repository\ProjectDsiFocusTagRepo;
use DSI\Repository\ProjectFollowRepo;
use DSI\Repository\ProjectImpactHelpTagRepo;
use DSI\Repository\ProjectImpactTechTagRepo;
use DSI\Repository\ProjectLinkRepo;
use DSI\Repository\ProjectMemberRepo;
use DSI\Repository\ProjectMemberRequestRepo;
use DSI\Repository\ProjectPostRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectTagRepo;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\JsModules;
use DSI\Service\Mailer;
use Services\URL;
use DSI\UseCase\AddMemberRequestToProject;
use DSI\UseCase\AddProjectToOrganisation;
use DSI\UseCase\ApproveMemberRequestToProject;
use DSI\UseCase\CreateProjectPost;
use DSI\UseCase\Projects\FollowProject;
use DSI\UseCase\Projects\RemoveProject;
use DSI\UseCase\Projects\UnfollowProject;
use DSI\UseCase\RejectMemberRequestToProject;
use DSI\UseCase\RemoveMemberFromProject;
use DSI\UseCase\SecureCode;
use DSI\UseCase\SendEmailToCommunityAdmins;
use DSI\UseCase\UpdateProject;
use DSI\UseCase\UpdateProjectCountryRegion;
use Services\View;

class ProjectController
{
    /** @var  ProjectController_Data */
    private $data;

    /** @var URL */
    private $urlHandler;

    /** @var Project */
    private $project;

    public function __construct()
    {
        $this->data = new ProjectController_Data();
    }

    public function exec()
    {
        $this->urlHandler = $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $projectRepo = new ProjectRepo();
        try {
            $this->project = $projectRepo->getById($this->data()->projectID);
        } catch (NotFound $e) {
            \Services\View::setPageTitle('Project does not exist');
            require __DIR__ . '/../../Views/project/project-404.php';
            return;
        }

        if ($this->project->isWaitingApproval() AND !$this->canViewWaitingApproval($loggedInUser)) {
            \Services\View::setPageTitle('Project is waiting approval');
            require __DIR__ . '/../../Views/project/project-404.php';
            return;
        }

        $memberRequests = [];
        $isAdmin = false;
        $canUserRequestMembership = false;
        $isOwner = false;

        $projectMembers = (new ProjectMemberRepo())->getByProject($this->project);
        $organisationProjectsObj = (new OrganisationProjectRepo())->getByProjectID($this->project->getId());
        usort($organisationProjectsObj, function (OrganisationProject $a, OrganisationProject $b) {
            return ($a->getOrganisation()->getName() <= $b->getOrganisation()->getName()) ? -1 : 1;
        });
        $organisationProjects = array_map(function (OrganisationProject $organisationProject) use ($urlHandler) {
            $organisation = $organisationProject->getOrganisation();
            return [
                'id' => $organisation->getId(),
                'name' => $organisation->getName(),
                'url' => $urlHandler->organisation($organisation),
                'projectsCount' => count((new OrganisationProjectRepo())->getByOrganisationID($organisation->getId())),
            ];
        }, $organisationProjectsObj);

        $userIsMember = false;
        $userSentJoinRequest = false;
        $userCanSendJoinRequest = false;

        if ($loggedInUser) {
            if (isset($_POST['getSecureCode']))
                return $this->setSecureCode();

            if (isset($_POST['report']))
                return $this->report($loggedInUser, $this->project, $urlHandler);

            if (isset($_POST['deleteProject']))
                return $this->deleteProject($loggedInUser, $this->project, $urlHandler);

            if (isset($_POST['joinProject']))
                return $this->joinProject($loggedInUser, $this->project);

            if (isset($_POST['cancelJoinRequest']))
                return $this->cancelJoinRequest($loggedInUser, $this->project);

            if (isset($_POST['leaveProject']))
                return $this->leaveProject($loggedInUser, $this->project);

            if (isset($_POST['followProject']))
                return $this->followProject($loggedInUser, $this->project);

            if (isset($_POST['unfollowProject']))
                return $this->unfollowProject($loggedInUser, $this->project);

            $userIsMember = (new ProjectMemberRepo())->projectHasMember($this->project, $loggedInUser);
            if (!$userIsMember) {
                $userSentJoinRequest = (new ProjectMemberRequestRepo())->projectHasRequestFromMember(
                    $this->project->getId(),
                    $loggedInUser->getId()
                );
                if (!$userSentJoinRequest)
                    $userCanSendJoinRequest = true;
            }

            if ($this->project->getOwnerID() == $loggedInUser->getId()) {
                $isOwner = true;
                $isAdmin = true;
            }

            $member = (new ProjectMemberRepo())->getByProjectAndMember($this->project, $loggedInUser);
            if ($member !== null AND $member->isAdmin())
                $isAdmin = true;

            if (isset($isAdmin) AND $isAdmin === true)
                $memberRequests = (new ProjectMemberRequestRepo())->getMembersForProject($this->project->getId());
        }

        $userCanEditProject = ($isAdmin OR ($loggedInUser AND $loggedInUser->isCommunityAdmin()));
        $userCanAddPost = $isAdmin;
        $userIsFollowing = ($loggedInUser AND (new ProjectFollowRepo())->userFollowsProject($loggedInUser, $this->project));

        $links = [];
        $projectLinks = (new ProjectLinkRepo())->getByProjectID($this->project->getId());
        foreach ($projectLinks AS $projectLink) {
            if ($projectLink->getLinkService() == ProjectLink_Service::Facebook)
                $links['facebook'] = $projectLink->getLink();
            if ($projectLink->getLinkService() == ProjectLink_Service::Twitter)
                $links['twitter'] = $projectLink->getLink();
            if ($projectLink->getLinkService() == ProjectLink_Service::GooglePlus)
                $links['googleplus'] = $projectLink->getLink();
            if ($projectLink->getLinkService() == ProjectLink_Service::GitHub)
                $links['github'] = $projectLink->getLink();
        }

        try {
            if ($userCanEditProject) {
                if (isset($_POST['updateBasic']))
                    return $this->updateBasicInfo($this->project, $loggedInUser);

                if (isset($_POST['approveRequestToJoin']))
                    return $this->approveRequestToJoin($this->project);

                if (isset($_POST['rejectRequestToJoin']))
                    return $this->rejectRequestToJoin($this->project);

                if (isset($_POST['updateCountryRegion']))
                    return $this->updateCountryRegion($this->project);

                if (isset($_POST['newOrganisationID']))
                    return $this->addOrganisationToProject($this->project);

                if (isset($_POST['addPost']))
                    return $this->addNewPostToProject($this->project, $loggedInUser);
            } else {
                if (isset($_POST['requestToJoin']))
                    return $this->requestToJoin($this->project, $loggedInUser);
            }
        } catch (ErrorHandler $e) {
            echo json_encode([
                'result' => 'error',
                'errors' => $e->getErrors()
            ]);
            return null;
        }

        if ($this->data()->format == 'json') {
            echo json_encode([
                'name' => $this->project->getName(),
                'url' => $this->project->getUrl(),
                'status' => $this->project->getStatus(),
                'shortDescription' => $this->project->getShortDescription(),
                'description' => $this->project->getDescription(),
                'startDate' => $this->project->getStartDate(),
                'endDate' => $this->project->getEndDate(),
                'tags' => array_map(function (ProjectTag $projectTag) {
                    return [
                        'id' => $projectTag->getTagID(),
                        'name' => $projectTag->getTag()->getName(),
                    ];
                }, (new ProjectTagRepo())->getByProjectID($this->project->getId())),
                'impactTagsA' => array_map(function (ProjectImpactHelpTag $projectTag) {
                    return [
                        'id' => $projectTag->getTagID(),
                        'name' => $projectTag->getTag()->getName(),
                    ];
                }, (new ProjectImpactHelpTagRepo())->getByProjectID($this->project->getId())),
                'impactTagsB' => array_map(function (ProjectDsiFocusTag $projectTag) {
                    return [
                        'id' => $projectTag->getTagID(),
                        'name' => $projectTag->getTag()->getName(),
                    ];
                }, (new ProjectDsiFocusTagRepo())->getByProjectID($this->project->getId())),
                'impactTagsC' => array_map(function (ProjectImpactTechTag $projectTag) {
                    return [
                        'id' => $projectTag->getTagID(),
                        'name' => $projectTag->getTag()->getName(),
                    ];
                }, (new ProjectImpactTechTagRepo())->getByProjectID($this->project->getId())),

                'members' => $this->getMembers($this->project->getOwner(), $projectMembers),
                'memberRequests' => array_map(function (User $user) {
                    return [
                        'id' => $user->getId(),
                        'text' => $user->getFirstName() . ' ' . $user->getLastName(),
                        'firstName' => $user->getFirstName(),
                        'lastName' => $user->getLastName(),
                        'profilePic' => $user->getProfilePicOrDefault()
                    ];
                }, $memberRequests),
                'organisationProjects' => $organisationProjects,
                'countryID' => $this->project->getCountryID(),
                'countryRegionID' => $this->project->getRegionID(),
                'countryRegion' => $this->project->getRegion() ? $this->project->getRegion()->getName() : '',
                'posts' => $this->getPostsForProject($this->project),
            ]);
        } else {
            $project = $this->project;
            JsModules::setTinyMCE(true);
            JsModules::setTranslations(true);
            View::setPageTitle($this->project->getName());
            View::setPageDescription(
                $project->getDescription() ?:
                    $project->getShortDescription() ?:
                        $project->getName()
            );
            require __DIR__ . '/../../Views/project/project.php';
        }

        return null;
    }

    /**
     * @return ProjectController_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @param User $owner
     * @param ProjectMember[] $projectMembers
     * @return array
     */
    private function getMembers(User $owner, $projectMembers)
    {
        return array_values(
            array_filter(
                array_map(
                    function (ProjectMember $projectMember) use ($owner) {
                        $user = $projectMember->getMember();
                        if ($owner->getId() == $user->getId())
                            return null;
                        else
                            return [
                                'id' => $user->getId(),
                                'text' => $user->getFirstName() . ' ' . $user->getLastName(),
                                'firstName' => $user->getFirstName(),
                                'lastName' => $user->getLastName(),
                                'profilePic' => $user->getProfilePicOrDefault(),
                                'isAdmin' => $projectMember->isAdmin(),
                            ];
                    }, $projectMembers)));
    }

    /**
     * @param $project
     * @return mixed
     */
    private function getPostsForProject(Project $project)
    {
        return array_map(function (ProjectPost $post) {
            $user = $post->getUser();

            return [
                'id' => $post->getId(),
                'time' => $post->getTime('l, jS M Y H:i'),
                'text' => $post->getText(),
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getFirstName() . ' ' . $user->getLastName(),
                    'profilePic' => $user->getProfilePicOrDefault(),
                    'url' => $this->urlHandler->profile($user),
                ],
                'commentsCount' => $post->getCommentsCount(),
            ];
        }, (new ProjectPostRepo())->getByProjectID($project->getId()));
    }

    private function setSecureCode()
    {
        $genSecureCode = new SecureCode();
        $genSecureCode->exec();
        echo json_encode([
            'code' => 'ok',
            'secureCode' => $genSecureCode->getCode(),
        ]);
        return true;
    }

    /**
     * @param User $loggedInUser
     * @param Project $project
     * @param URL $urlHandler
     * @return bool
     */
    private function report(User $loggedInUser, Project $project, URL $urlHandler)
    {
        $genSecureCode = new SecureCode();
        if ($genSecureCode->checkCode($_POST['secureCode'])) {
            try {
                ob_start(); ?>
                User: <?php echo show_input($loggedInUser->getFullName()) ?>
                (<a href="https://<?php echo SITE_DOMAIN . $urlHandler->profile($loggedInUser) ?>">View profile</a>)
                <br/>
                Reported Project: <?php echo show_input($project->getName()) ?>
                (<a href="https://<?php echo SITE_DOMAIN . $urlHandler->project($project) ?>">View page</a>)
                <br/>
                Reason: <?php echo show_input($_POST['reason']) ?>
                <br/>
                <?php $message = ob_get_clean();

                $mail = new Mailer();
                $mail->Subject = 'Project Report on DSI4EU';
                $mail->wrapMessageInTemplate([
                    'header' => 'Project Report on DSI4EU',
                    'body' => $message
                ]);

                $exec = new SendEmailToCommunityAdmins();
                $exec->data()->executor = $loggedInUser;
                $exec->data()->mail = $mail;
                $exec->exec();

                echo json_encode([
                    'code' => 'ok',
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        }
        return true;
    }

    /**
     * @param User $loggedInUser
     * @param Project $project
     * @param URL $urlHandler
     * @return bool
     */
    private function deleteProject(User $loggedInUser, Project $project, URL $urlHandler)
    {
        $genSecureCode = new SecureCode();
        if ($genSecureCode->checkCode($_POST['secureCode'])) {
            try {
                $removeProject = new RemoveProject();
                $removeProject->data()->executor = $loggedInUser;
                $removeProject->data()->project = $project;
                $removeProject->exec();

                echo json_encode([
                    'code' => 'ok',
                    'url' => $urlHandler->projects()
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        }
        return true;
    }

    /**
     * @param User $user
     * @param Project $project
     * @return bool
     */
    private function cancelJoinRequest(User $user, Project $project)
    {
        $genSecureCode = new SecureCode();
        if ($genSecureCode->checkCode($_POST['secureCode'])) {
            try {
                $cancelJoinRequest = new RejectMemberRequestToProject();
                $cancelJoinRequest->data()->executor = $user;
                $cancelJoinRequest->data()->projectID = $project->getId();
                $cancelJoinRequest->data()->userID = $user->getId();
                $cancelJoinRequest->exec();

                echo json_encode([
                    'code' => 'ok',
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        }
        return true;
    }

    private function joinProject(User $user, Project $project)
    {
        $genSecureCode = new SecureCode();
        if ($genSecureCode->checkCode($_POST['secureCode'])) {
            try {
                $joinProject = new AddMemberRequestToProject();
                $joinProject->data()->projectID = $project->getId();
                $joinProject->data()->userID = $user->getId();
                $joinProject->exec();

                echo json_encode([
                    'code' => 'ok',
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        }
        return true;
    }

    /**
     * @param User $user
     * @param Project $project
     * @return bool
     */
    private function leaveProject(User $user, Project $project)
    {
        $genSecureCode = new SecureCode();
        if ($genSecureCode->checkCode($_POST['secureCode'])) {
            try {
                $removeMember = new RemoveMemberFromProject();
                $removeMember->setProject($project);
                $removeMember->setUser($user);
                $removeMember->exec();

                echo json_encode([
                    'code' => 'ok',
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        }
        return true;
    }

    /**
     * @param User $user
     * @param Project $project
     * @return bool
     */
    private function followProject(User $user, Project $project)
    {
        $genSecureCode = new SecureCode();
        if ($genSecureCode->checkCode($_POST['secureCode'])) {
            try {
                $followProject = new FollowProject();
                $followProject->setProject($project);
                $followProject->setUser($user);
                $followProject->setExecutor($user);
                $followProject->exec();

                echo json_encode([
                    'code' => 'ok',
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        }
        return true;
    }

    /**
     * @param User $user
     * @param Project $project
     * @return bool
     */
    private function unfollowProject(User $user, Project $project)
    {
        $genSecureCode = new SecureCode();
        if ($genSecureCode->checkCode($_POST['secureCode'])) {
            try {
                $unfollowProject = new UnfollowProject();
                $unfollowProject->setProject($project);
                $unfollowProject->setUser($user);
                $unfollowProject->setExecutor($user);
                $unfollowProject->exec();

                echo json_encode([
                    'code' => 'ok',
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        }
        return true;
    }

    /**
     * @param Project $project
     * @param User $loggedInUser
     * @return null
     */
    private function updateBasicInfo(Project $project, User $loggedInUser)
    {
        $updateProject = new UpdateProject();
        $updateProject->data()->project = $project;
        $updateProject->data()->executor = $loggedInUser;
        if (isset($_POST['name']))
            $updateProject->data()->name = $_POST['name'];
        if (isset($_POST['url']))
            $updateProject->data()->url = $_POST['url'];
        if (isset($_POST['status']))
            $updateProject->data()->status = $_POST['status'];
        if (isset($_POST['description']))
            $updateProject->data()->description = $_POST['description'];

        $updateProject->data()->startDate = $_POST['startDate'] ?? NULL;
        $updateProject->data()->endDate = $_POST['endDate'] ?? NULL;

        $updateProject->exec();
        echo json_encode(['result' => 'ok']);
        return;
    }

    /**
     * @param Project $project
     * @return null
     */
    private function approveRequestToJoin(Project $project)
    {
        $approveMemberRequestToJoinProject = new ApproveMemberRequestToProject();
        $approveMemberRequestToJoinProject->data()->projectID = $project->getId();
        $approveMemberRequestToJoinProject->data()->userID = $_POST['approveRequestToJoin'];
        $approveMemberRequestToJoinProject->exec();
        echo json_encode(['result' => 'ok']);
        return;
    }

    /**
     * @param Project $project
     * @return null
     */
    private function rejectRequestToJoin(Project $project)
    {
        $rejectMemberRequestToJoinProject = new RejectMemberRequestToProject();
        $rejectMemberRequestToJoinProject->data()->projectID = $project->getId();
        $rejectMemberRequestToJoinProject->data()->userID = $_POST['rejectRequestToJoin'];
        $rejectMemberRequestToJoinProject->exec();
        echo json_encode(['result' => 'ok']);
        return;
    }

    /**
     * @param Project $project
     * @return null
     */
    private function updateCountryRegion(Project $project)
    {
        $updateProjectCountryRegionCmd = new UpdateProjectCountryRegion();
        $updateProjectCountryRegionCmd->data()->projectID = $project->getId();
        $updateProjectCountryRegionCmd->data()->countryID = $_POST['countryID'];
        $updateProjectCountryRegionCmd->data()->region = $_POST['region'];
        $updateProjectCountryRegionCmd->exec();
        echo json_encode(['result' => 'ok']);
        return;
    }

    /**
     * @param Project $project
     * @return null
     */
    private function addOrganisationToProject(Project $project)
    {
        $addOrganisationProjectCmd = new AddProjectToOrganisation();
        $addOrganisationProjectCmd->data()->projectID = $project->getId();
        $addOrganisationProjectCmd->data()->organisationID = $_POST['newOrganisationID'];
        $addOrganisationProjectCmd->exec();
        echo json_encode(['result' => 'ok']);
        return;
    }

    /**
     * @param Project $project
     * @param User $loggedInUser
     * @return null
     */
    private function addNewPostToProject(Project $project, User $loggedInUser)
    {
        $addPostCmd = new CreateProjectPost();
        $addPostCmd->data()->project = $project;
        $addPostCmd->data()->executor = $loggedInUser;
        $addPostCmd->data()->text = $_POST['addPost'];
        $addPostCmd->exec();
        echo json_encode([
            'result' => 'ok',
            'posts' => $this->getPostsForProject($project),
        ]);
        return;
    }

    /**
     * @param Project $project
     * @param User $loggedInUser
     * @return null
     */
    private function requestToJoin(Project $project, User $loggedInUser)
    {
        $addMemberRequestToJoinProject = new AddMemberRequestToProject();
        $addMemberRequestToJoinProject->data()->projectID = $project->getId();
        $addMemberRequestToJoinProject->data()->userID = $loggedInUser->getId();
        $addMemberRequestToJoinProject->exec();
        echo json_encode(['result' => 'ok']);
        return;
    }

    /**
     * @param User|null $loggedInUser
     * @return bool
     */
    private function canViewWaitingApproval($loggedInUser)
    {
        if (!$loggedInUser)
            return false;

        else if ($loggedInUser->isEditorialAdmin() OR $loggedInUser->isCommunityAdmin())
            return true;

        else if ($this->project->getOwnerID() != $loggedInUser->getId())
            return true;

        return false;
    }
}

class ProjectController_Data
{
    /** @var  int */
    public $projectID;

    /** @var string */
    public $format = 'html';
}