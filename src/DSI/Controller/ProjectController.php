<?php

namespace DSI\Controller;

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
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\ProjectDsiFocusTagRepository;
use DSI\Repository\ProjectFollowRepository;
use DSI\Repository\ProjectImpactHelpTagRepository;
use DSI\Repository\ProjectImpactTechTagRepository;
use DSI\Repository\ProjectLinkRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectMemberRequestRepository;
use DSI\Repository\ProjectPostRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectTagRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\JsModules;
use DSI\Service\Mailer;
use DSI\Service\URL;
use DSI\UseCase\AddEmailToProject;
use DSI\UseCase\AddMemberInvitationToProject;
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
use DSI\UseCase\SetAdminStatusToProjectMember;
use DSI\UseCase\UpdateProject;
use DSI\UseCase\UpdateProjectCountryRegion;

class ProjectController
{
    /** @var  ProjectController_Data */
    private $data;

    /** @var URL */
    private $urlHandler;

    public function __construct()
    {
        $this->data = new ProjectController_Data();
    }

    public function exec()
    {
        $this->urlHandler = $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $projectRepo = new ProjectRepository();
        $project = $projectRepo->getById($this->data()->projectID);

        $memberRequests = [];
        $isAdmin = false;
        $canUserRequestMembership = false;
        $isOwner = false;

        $projectMembers = (new ProjectMemberRepository())->getByProject($project);
        $organisationProjectsObj = (new OrganisationProjectRepository())->getByProjectID($project->getId());
        usort($organisationProjectsObj, function (OrganisationProject $a, OrganisationProject $b) {
            return ($a->getOrganisation()->getName() <= $b->getOrganisation()->getName()) ? -1 : 1;
        });
        $organisationProjects = array_map(function (OrganisationProject $organisationProject) use ($urlHandler) {
            $organisation = $organisationProject->getOrganisation();
            return [
                'id' => $organisation->getId(),
                'name' => $organisation->getName(),
                'url' => $urlHandler->organisation($organisation),
                'projectsCount' => count((new OrganisationProjectRepository())->getByOrganisationID($organisation->getId())),
            ];
        }, $organisationProjectsObj);

        $userIsMember = false;
        $userSentJoinRequest = false;
        $userCanSendJoinRequest = false;

        if ($loggedInUser) {
            if (isset($_POST['getSecureCode']))
                return $this->setSecureCode();

            if (isset($_POST['report']))
                return $this->report($loggedInUser, $project, $urlHandler);

            if (isset($_POST['deleteProject']))
                return $this->deleteProject($loggedInUser, $project, $urlHandler);

            if (isset($_POST['joinProject']))
                return $this->joinProject($loggedInUser, $project);

            if (isset($_POST['cancelJoinRequest']))
                return $this->cancelJoinRequest($loggedInUser, $project);

            if (isset($_POST['leaveProject']))
                return $this->leaveProject($loggedInUser, $project);

            if (isset($_POST['followProject']))
                return $this->followProject($loggedInUser, $project);

            if (isset($_POST['unfollowProject']))
                return $this->unfollowProject($loggedInUser, $project);

            $userIsMember = (new ProjectMemberRepository())->projectHasMember($project, $loggedInUser);
            if (!$userIsMember) {
                $userSentJoinRequest = (new ProjectMemberRequestRepository())->projectHasRequestFromMember(
                    $project->getId(),
                    $loggedInUser->getId()
                );
                if (!$userSentJoinRequest)
                    $userCanSendJoinRequest = true;
            }

            if ($project->getOwnerID() == $loggedInUser->getId()) {
                $isOwner = true;
                $isAdmin = true;
            }

            $member = (new ProjectMemberRepository())->getByProjectAndMember($project, $loggedInUser);
            if ($member !== null AND $member->isAdmin())
                $isAdmin = true;

            if (isset($isAdmin) AND $isAdmin === true)
                $memberRequests = (new ProjectMemberRequestRepository())->getMembersForProject($project->getId());
        }

        $userCanEditProject = ($isAdmin OR ($loggedInUser AND $loggedInUser->isCommunityAdmin()));
        $userCanAddPost = $isAdmin;
        $userIsFollowing = ($loggedInUser AND (new ProjectFollowRepository())->userFollowsProject($loggedInUser, $project));

        $links = [];
        $projectLinks = (new ProjectLinkRepository())->getByProjectID($project->getId());
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
                if (isset($_POST['updateBasic'])) {
                    $authUser->ifNotLoggedInRedirectTo($urlHandler->login());

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
                    return true;
                }

                if (isset($_POST['addEmail'])) {
                    $addEmailToProject = new AddEmailToProject();
                    $addEmailToProject->data()->projectID = $project->getId();
                    $addEmailToProject->data()->email = $_POST['addEmail'];
                    $addEmailToProject->data()->byUserID = $loggedInUser->getId();
                    $addEmailToProject->exec();
                    $user = $addEmailToProject->getUser();

                    $response = [];
                    $response['result'] = 'ok';
                    $response['successMessage'] = $user ?
                        'Member has been successfully invited' :
                        'An invitation email has been sent to the person';

                    echo json_encode($response);
                    return true;
                }
                if (isset($_POST['addMember'])) {
                    $addMemberToProject = new AddMemberInvitationToProject();
                    $addMemberToProject->setProject($project);
                    $addMemberToProject->setUserID($_POST['addMember']);
                    $addMemberToProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return true;
                }
                if (isset($_POST['removeMember'])) {
                    $removeMemberFromProject = new RemoveMemberFromProject();
                    $removeMemberFromProject->setProject($project);
                    $removeMemberFromProject->setUserId($_POST['removeMember']);
                    $removeMemberFromProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return true;
                }
                if (isset($_POST['approveRequestToJoin'])) {
                    $approveMemberRequestToJoinProject = new ApproveMemberRequestToProject();
                    $approveMemberRequestToJoinProject->data()->projectID = $project->getId();
                    $approveMemberRequestToJoinProject->data()->userID = $_POST['approveRequestToJoin'];
                    $approveMemberRequestToJoinProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return true;
                }
                if (isset($_POST['rejectRequestToJoin'])) {
                    $rejectMemberRequestToJoinProject = new RejectMemberRequestToProject();
                    $rejectMemberRequestToJoinProject->data()->projectID = $project->getId();
                    $rejectMemberRequestToJoinProject->data()->userID = $_POST['rejectRequestToJoin'];
                    $rejectMemberRequestToJoinProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return true;
                }

                if (isset($_POST['updateCountryRegion'])) {
                    $updateProjectCountryRegionCmd = new UpdateProjectCountryRegion();
                    $updateProjectCountryRegionCmd->data()->projectID = $project->getId();
                    $updateProjectCountryRegionCmd->data()->countryID = $_POST['countryID'];
                    $updateProjectCountryRegionCmd->data()->region = $_POST['region'];
                    $updateProjectCountryRegionCmd->exec();
                    echo json_encode(['result' => 'ok']);
                    return true;
                }

                if (isset($_POST['newOrganisationID'])) {
                    $addOrganisationProjectCmd = new AddProjectToOrganisation();
                    $addOrganisationProjectCmd->data()->projectID = $project->getId();
                    $addOrganisationProjectCmd->data()->organisationID = $_POST['newOrganisationID'];
                    $addOrganisationProjectCmd->exec();
                    echo json_encode(['result' => 'ok']);
                    return true;
                }

                if (isset($_POST['addPost'])) {
                    $addPostCmd = new CreateProjectPost();
                    $addPostCmd->data()->project = $project;
                    $addPostCmd->data()->executor = $loggedInUser;
                    $addPostCmd->data()->text = $_POST['addPost'];
                    $addPostCmd->exec();
                    echo json_encode([
                        'result' => 'ok',
                        'posts' => $this->getPostsForProject($project),
                    ]);
                    return true;
                }

                if (isset($_POST['setAdmin'])) {
                    $setStatusCmd = new SetAdminStatusToProjectMember();
                    $setStatusCmd->setExecutor($loggedInUser);
                    $setStatusCmd->setMemberId($_POST['member']);
                    $setStatusCmd->setProject($project);
                    $setStatusCmd->setIsAdmin($_POST['isAdmin']);
                    $setStatusCmd->exec();

                    echo json_encode([
                        'result' => 'ok'
                    ]);
                    return true;
                }
            } else {
                if (isset($_POST['requestToJoin'])) {
                    $addMemberRequestToJoinProject = new AddMemberRequestToProject();
                    $addMemberRequestToJoinProject->data()->projectID = $project->getId();
                    $addMemberRequestToJoinProject->data()->userID = $loggedInUser->getId();
                    $addMemberRequestToJoinProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return true;
                }
            }
        } catch (ErrorHandler $e) {
            echo json_encode([
                'result' => 'error',
                'errors' => $e->getErrors()
            ]);
            return true;
        }

        if ($this->data()->format == 'json') {
            echo json_encode([
                'name' => $project->getName(),
                'url' => $project->getUrl(),
                'status' => $project->getStatus(),
                'shortDescription' => $project->getShortDescription(),
                'description' => $project->getDescription(),
                'startDate' => $project->getStartDate(),
                'endDate' => $project->getEndDate(),
                'tags' => array_map(function (ProjectTag $projectTag) {
                    return [
                        'id' => $projectTag->getTagID(),
                        'name' => $projectTag->getTag()->getName(),
                    ];
                }, (new ProjectTagRepository())->getByProjectID($project->getId())),
                'impactTagsA' => array_map(function (ProjectImpactHelpTag $projectTag) {
                    return [
                        'id' => $projectTag->getTagID(),
                        'name' => $projectTag->getTag()->getName(),
                    ];
                }, (new ProjectImpactHelpTagRepository())->getByProjectID($project->getId())),
                'impactTagsB' => array_map(function (ProjectDsiFocusTag $projectTag) {
                    return [
                        'id' => $projectTag->getTagID(),
                        'name' => $projectTag->getTag()->getName(),
                    ];
                }, (new ProjectDsiFocusTagRepository())->getByProjectID($project->getId())),
                'impactTagsC' => array_map(function (ProjectImpactTechTag $projectTag) {
                    return [
                        'id' => $projectTag->getTagID(),
                        'name' => $projectTag->getTag()->getName(),
                    ];
                }, (new ProjectImpactTechTagRepository())->getByProjectID($project->getId())),

                'members' => $this->getMembers($project->getOwner(), $projectMembers),
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
                'countryID' => $project->getCountryID(),
                'countryRegionID' => $project->getRegionID(),
                'countryRegion' => $project->getRegion() ? $project->getRegion()->getName() : '',
                'posts' => $this->getPostsForProject($project),
            ]);
        } else {
            $pageTitle = $project->getName();
            JsModules::setTinyMCE(true);
            require __DIR__ . '/../../../www/views/project.php';
        }

        return true;
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
        }, (new ProjectPostRepository())->getByProjectID($project->getId()));
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
}

class ProjectController_Data
{
    /** @var  int */
    public $projectID;

    /** @var string */
    public $format = 'html';
}