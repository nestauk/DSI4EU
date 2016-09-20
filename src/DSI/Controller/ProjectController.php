<?php

namespace DSI\Controller;

use DSI\Entity\OrganisationProject;
use DSI\Entity\Project;
use DSI\Entity\ProjectLink_Service;
use DSI\Entity\ProjectMember;
use DSI\Entity\ProjectPost;
use DSI\Entity\User;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\ProjectImpactTagARepository;
use DSI\Repository\ProjectDsiFocusTagRepository;
use DSI\Repository\ProjectImpactTagCRepository;
use DSI\Repository\ProjectLinkRepository;
use DSI\Repository\ProjectMemberInvitationRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectMemberRequestRepository;
use DSI\Repository\ProjectPostRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectTagRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\AddEmailToProject;
use DSI\UseCase\AddImpactTagAToProject;
use DSI\UseCase\AddDsiFocusTagToProject;
use DSI\UseCase\AddImpactTagCToProject;
use DSI\UseCase\AddMemberInvitationToProject;
use DSI\UseCase\AddMemberRequestToProject;
use DSI\UseCase\AddProjectToOrganisation;
use DSI\UseCase\AddTagToProject;
use DSI\UseCase\ApproveMemberRequestToProject;
use DSI\UseCase\CreateProjectPost;
use DSI\UseCase\RejectMemberRequestToProject;
use DSI\UseCase\RemoveImpactTagAFromProject;
use DSI\UseCase\RemoveDsiFocusTagFromProject;
use DSI\UseCase\RemoveImpactTagCFromProject;
use DSI\UseCase\RemoveMemberFromProject;
use DSI\UseCase\RemoveTagFromProject;
use DSI\UseCase\SetAdminStatusToProjectMember;
use DSI\UseCase\UpdateProject;
use DSI\UseCase\UpdateProjectCountryRegion;

class ProjectController
{
    /** @var  ProjectController_Data */
    private $data;

    public function __construct()
    {
        $this->data = new ProjectController_Data();
    }

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $loggedInUser = $authUser->getUserIfLoggedIn();

        $projectRepo = new ProjectRepository();
        $project = $projectRepo->getById($this->data()->projectID);

        $memberRequests = [];
        $isAdmin = false;
        $canUserRequestMembership = false;
        $isOwner = false;

        $projectMembers = (new ProjectMemberRepository())->getByProjectID($project->getId());
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

        if ($loggedInUser) {
            $userHasInvitation = (new ProjectMemberInvitationRepository())->memberHasInvitationToProject(
                $loggedInUser->getId(),
                $project->getId()
            );

            $canUserRequestMembership = $this->canUserRequestMembership($project, $loggedInUser, $userHasInvitation);
            if ($project->getOwnerID() == $loggedInUser->getId()) {
                $isOwner = true;
                $isAdmin = true;
            }

            $member = (new ProjectMemberRepository())->getByProjectIDAndMemberID($project->getId(), $loggedInUser->getId());
            if ($member !== null AND $member->isAdmin())
                $isAdmin = true;

            if (isset($isAdmin) AND $isAdmin === true)
                $memberRequests = (new ProjectMemberRequestRepository())->getMembersForProject($project->getId());
        }

        $userCanEditProject = ($isAdmin OR ($loggedInUser AND $loggedInUser->isCommunityAdmin()));

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
                    return;
                }

                if (isset($_POST['addTag'])) {
                    $addTagToProject = new AddTagToProject();
                    $addTagToProject->data()->projectID = $project->getId();
                    $addTagToProject->data()->tag = $_POST['addTag'];
                    $addTagToProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return;
                }
                if (isset($_POST['removeTag'])) {
                    $removeTagFromProject = new RemoveTagFromProject();
                    $removeTagFromProject->data()->projectID = $project->getId();
                    $removeTagFromProject->data()->tag = $_POST['removeTag'];
                    $removeTagFromProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return;
                }

                if (isset($_POST['addImpactTagA'])) {
                    $addTagToProject = new AddImpactTagAToProject();
                    $addTagToProject->data()->projectID = $project->getId();
                    $addTagToProject->data()->tag = $_POST['addImpactTagA'];
                    $addTagToProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return;
                }
                if (isset($_POST['removeImpactTagA'])) {
                    $removeTagFromProject = new RemoveImpactTagAFromProject();
                    $removeTagFromProject->data()->projectID = $project->getId();
                    $removeTagFromProject->data()->tag = $_POST['removeImpactTagA'];
                    $removeTagFromProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return;
                }

                if (isset($_POST['addImpactTagB'])) {
                    $addTagToProject = new AddDsiFocusTagToProject();
                    $addTagToProject->data()->projectID = $project->getId();
                    $addTagToProject->data()->tag = $_POST['addImpactTagB'];
                    $addTagToProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return;
                }
                if (isset($_POST['removeImpactTagB'])) {
                    $removeTagFromProject = new RemoveDsiFocusTagFromProject();
                    $removeTagFromProject->data()->projectID = $project->getId();
                    $removeTagFromProject->data()->tag = $_POST['removeImpactTagB'];
                    $removeTagFromProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return;
                }

                if (isset($_POST['addImpactTagC'])) {
                    $addTagToProject = new AddImpactTagCToProject();
                    $addTagToProject->data()->projectID = $project->getId();
                    $addTagToProject->data()->tag = $_POST['addImpactTagC'];
                    $addTagToProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return;
                }
                if (isset($_POST['removeImpactTagC'])) {
                    $removeTagFromProject = new RemoveImpactTagCFromProject();
                    $removeTagFromProject->data()->projectID = $project->getId();
                    $removeTagFromProject->data()->tag = $_POST['removeImpactTagC'];
                    $removeTagFromProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return;
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
                    return;
                }
                if (isset($_POST['addMember'])) {
                    $addMemberToProject = new AddMemberInvitationToProject();
                    $addMemberToProject->data()->projectID = $project->getId();
                    $addMemberToProject->data()->userID = $_POST['addMember'];
                    $addMemberToProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return;
                }
                if (isset($_POST['removeMember'])) {
                    $removeMemberFromProject = new RemoveMemberFromProject();
                    $removeMemberFromProject->data()->projectID = $project->getId();
                    $removeMemberFromProject->data()->userID = $_POST['removeMember'];
                    $removeMemberFromProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return;
                }
                if (isset($_POST['approveRequestToJoin'])) {
                    $approveMemberRequestToJoinProject = new ApproveMemberRequestToProject();
                    $approveMemberRequestToJoinProject->data()->projectID = $project->getId();
                    $approveMemberRequestToJoinProject->data()->userID = $_POST['approveRequestToJoin'];
                    $approveMemberRequestToJoinProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return;
                }
                if (isset($_POST['rejectRequestToJoin'])) {
                    $rejectMemberRequestToJoinProject = new RejectMemberRequestToProject();
                    $rejectMemberRequestToJoinProject->data()->projectID = $project->getId();
                    $rejectMemberRequestToJoinProject->data()->userID = $_POST['rejectRequestToJoin'];
                    $rejectMemberRequestToJoinProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return;
                }

                if (isset($_POST['updateCountryRegion'])) {
                    $updateProjectCountryRegionCmd = new UpdateProjectCountryRegion();
                    $updateProjectCountryRegionCmd->data()->projectID = $project->getId();
                    $updateProjectCountryRegionCmd->data()->countryID = $_POST['countryID'];
                    $updateProjectCountryRegionCmd->data()->region = $_POST['region'];
                    $updateProjectCountryRegionCmd->exec();
                    echo json_encode(['result' => 'ok']);
                    return;
                }

                if (isset($_POST['newOrganisationID'])) {
                    $addOrganisationProjectCmd = new AddProjectToOrganisation();
                    $addOrganisationProjectCmd->data()->projectID = $project->getId();
                    $addOrganisationProjectCmd->data()->organisationID = $_POST['newOrganisationID'];
                    $addOrganisationProjectCmd->exec();
                    echo json_encode(['result' => 'ok']);
                    return;
                }

                if (isset($_POST['addPost'])) {
                    $addPostCmd = new CreateProjectPost();
                    $addPostCmd->data()->project = $project;
                    $addPostCmd->data()->user = $loggedInUser;
                    $addPostCmd->data()->text = $_POST['addPost'];
                    $addPostCmd->exec();
                    echo json_encode([
                        'result' => 'ok',
                        'posts' => $this->getPostsForProject($project),
                    ]);
                    return;
                }

                if (isset($_POST['setAdmin'])) {
                    $setStatusCmd = new SetAdminStatusToProjectMember();
                    $setStatusCmd->data()->executor = $loggedInUser;
                    $setStatusCmd->data()->member = (new UserRepository())->getById($_POST['member']);
                    $setStatusCmd->data()->project = $project;
                    $setStatusCmd->data()->isAdmin = (bool)$_POST['isAdmin'];
                    $setStatusCmd->exec();

                    echo json_encode([
                        'result' => 'ok'
                    ]);
                    return;
                }
            } else {
                if (isset($_POST['requestToJoin'])) {
                    $addMemberRequestToJoinProject = new AddMemberRequestToProject();
                    $addMemberRequestToJoinProject->data()->projectID = $project->getId();
                    $addMemberRequestToJoinProject->data()->userID = $loggedInUser->getId();
                    $addMemberRequestToJoinProject->exec();
                    echo json_encode(['result' => 'ok']);
                    return;
                }
            }
        } catch (ErrorHandler $e) {
            echo json_encode([
                'result' => 'error',
                'errors' => $e->getErrors()
            ]);
            return;
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
                'tags' => (new ProjectTagRepository())->getTagsNameByProjectID($project->getId()),
                'impactTagsA' => (new ProjectImpactTagARepository())->getTagsNameByProjectID($project->getId()),
                'impactTagsB' => (new ProjectDsiFocusTagRepository())->getTagsNameByProjectID($project->getId()),
                'impactTagsC' => (new ProjectImpactTagCRepository())->getTagsNameByProjectID($project->getId()),
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
            return;
        } else {
            $pageTitle = $project->getName();
            require __DIR__ . '/../../../www/views/project.php';
        }
    }

    /**
     * @return ProjectController_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function canUserRequestMembership(Project $project, User $loggedInUser, $userHasInvitation)
    {
        if ($userHasInvitation)
            return false;
        if ($project->getOwnerID() == $loggedInUser->getId())
            return false;
        if ((new ProjectMemberRepository())->projectHasMember($project->getId(), $loggedInUser->getId()))
            return false;
        if ((new ProjectMemberRequestRepository())->projectHasRequestFromMember($project->getId(), $loggedInUser->getId()))
            return false;

        return true;
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
                'time' => $post->getTime(),
                'text' => $post->getText(),
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getFirstName() . ' ' . $user->getLastName(),
                    'profilePic' => $user->getProfilePicOrDefault(),
                ],
                'commentsCount' => $post->getCommentsCount(),
            ];
        }, (new ProjectPostRepository())->getByProjectID($project->getId()));
    }
}

class ProjectController_Data
{
    /** @var  int */
    public $projectID;

    /** @var string */
    public $format = 'html';
}