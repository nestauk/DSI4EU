<?php

namespace DSI\Controller;

use DSI\Entity\OrganisationProject;
use DSI\Entity\Project;
use DSI\Entity\ProjectMember;
use DSI\Entity\ProjectPost;
use DSI\Entity\ProjectPostComment;
use DSI\Entity\User;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\ProjectImpactTagARepository;
use DSI\Repository\ProjectImpactTagBRepository;
use DSI\Repository\ProjectImpactTagCRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectMemberRequestRepository;
use DSI\Repository\ProjectPostCommentRepository;
use DSI\Repository\ProjectPostRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectTagRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\AddCommentToProjectPost;
use DSI\UseCase\AddEmailToProject;
use DSI\UseCase\AddImpactTagAToProject;
use DSI\UseCase\AddImpactTagBToProject;
use DSI\UseCase\AddImpactTagCToProject;
use DSI\UseCase\AddMemberRequestToProject;
use DSI\UseCase\AddMemberToProject;
use DSI\UseCase\AddProjectToOrganisation;
use DSI\UseCase\AddTagToProject;
use DSI\UseCase\ApproveMemberRequestToProject;
use DSI\UseCase\CreateProjectPost;
use DSI\UseCase\RejectMemberRequestToProject;
use DSI\UseCase\RemoveImpactTagAFromProject;
use DSI\UseCase\RemoveImpactTagBFromProject;
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
        $loggedInUser = null;

        $authUser = new Auth();
        if ($authUser->isLoggedIn()) {
            $userRepo = new UserRepository();
            $loggedInUser = $userRepo->getById($authUser->getUserId());
        }

        $projectRepo = new ProjectRepository();
        $project = $projectRepo->getById($this->data()->projectID);

        $memberRequests = [];
        $isOwner = false;
        $canUserRequestMembership = false;
        $projectMembers = (new ProjectMemberRepository())->getByProjectID($project->getId());
        $organisationProjects = (new OrganisationProjectRepository())->getByProjectID($project->getId());
        $organisationProjects = array_map(function (OrganisationProject $organisationProject) {
            $organisation = $organisationProject->getOrganisation();
            return [
                'id' => $organisation->getId(),
                'name' => $organisation->getName(),
                'url' => URL::organisation($organisation->getId(), $organisation->getName()),
                'projectsCount' => count((new OrganisationProjectRepository())->getByOrganisationID($organisation->getId())),
            ];
        }, $organisationProjects);
        usort($organisationProjects, function ($a, $b) {
            return ($a['name'] <= $b['name']) ? -1 : 1;
        });

        if ($loggedInUser) {
            $canUserRequestMembership = $this->canUserRequestMembership($project, $loggedInUser);
            if ($project->getOwner()->getId() == $loggedInUser->getId())
                $isOwner = true;

            if (isset($isOwner) AND $isOwner === true)
                $memberRequests = (new ProjectMemberRequestRepository())->getMembersForProject($project->getId());
        }

        if ($isOwner) {
            try {
                if (isset($_POST['updateBasic'])) {
                    $authUser->ifNotLoggedInRedirectTo(URL::login());

                    $updateProject = new UpdateProject();
                    $updateProject->data()->project = $project;
                    $updateProject->data()->user = $loggedInUser;
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
                    die();
                }

                if (isset($_POST['addTag'])) {
                    $addTagToProject = new AddTagToProject();
                    $addTagToProject->data()->projectID = $project->getId();
                    $addTagToProject->data()->tag = $_POST['addTag'];
                    $addTagToProject->exec();
                    echo json_encode(['result' => 'ok']);
                    die();
                }
                if (isset($_POST['removeTag'])) {
                    $removeTagFromProject = new RemoveTagFromProject();
                    $removeTagFromProject->data()->projectID = $project->getId();
                    $removeTagFromProject->data()->tag = $_POST['removeTag'];
                    $removeTagFromProject->exec();
                    echo json_encode(['result' => 'ok']);
                    die();
                }

                if (isset($_POST['addImpactTagA'])) {
                    $addTagToProject = new AddImpactTagAToProject();
                    $addTagToProject->data()->projectID = $project->getId();
                    $addTagToProject->data()->tag = $_POST['addImpactTagA'];
                    $addTagToProject->exec();
                    echo json_encode(['result' => 'ok']);
                    die();
                }
                if (isset($_POST['removeImpactTagA'])) {
                    $removeTagFromProject = new RemoveImpactTagAFromProject();
                    $removeTagFromProject->data()->projectID = $project->getId();
                    $removeTagFromProject->data()->tag = $_POST['removeImpactTagA'];
                    $removeTagFromProject->exec();
                    echo json_encode(['result' => 'ok']);
                    die();
                }

                if (isset($_POST['addImpactTagB'])) {
                    $addTagToProject = new AddImpactTagBToProject();
                    $addTagToProject->data()->projectID = $project->getId();
                    $addTagToProject->data()->tag = $_POST['addImpactTagB'];
                    $addTagToProject->exec();
                    echo json_encode(['result' => 'ok']);
                    die();
                }
                if (isset($_POST['removeImpactTagB'])) {
                    $removeTagFromProject = new RemoveImpactTagBFromProject();
                    $removeTagFromProject->data()->projectID = $project->getId();
                    $removeTagFromProject->data()->tag = $_POST['removeImpactTagB'];
                    $removeTagFromProject->exec();
                    echo json_encode(['result' => 'ok']);
                    die();
                }

                if (isset($_POST['addImpactTagC'])) {
                    $addTagToProject = new AddImpactTagCToProject();
                    $addTagToProject->data()->projectID = $project->getId();
                    $addTagToProject->data()->tag = $_POST['addImpactTagC'];
                    $addTagToProject->exec();
                    echo json_encode(['result' => 'ok']);
                    die();
                }
                if (isset($_POST['removeImpactTagC'])) {
                    $removeTagFromProject = new RemoveImpactTagCFromProject();
                    $removeTagFromProject->data()->projectID = $project->getId();
                    $removeTagFromProject->data()->tag = $_POST['removeImpactTagC'];
                    $removeTagFromProject->exec();
                    echo json_encode(['result' => 'ok']);
                    die();
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
                        'Member has been successfully added' :
                        'An invitation email has been sent to the person';
                    if ($user) {
                        $response['user'] = [
                            'id' => $user->getId(),
                            'text' => $user->getFirstName() . ' ' . $user->getLastName(),
                            'profilePic' => $user->getProfilePicOrDefault(),
                            'firstName' => $user->getFirstName(),
                            'lastName' => $user->getLastName(),
                        ];
                    }

                    echo json_encode($response);
                    return;
                }
                if (isset($_POST['addMember'])) {
                    $addMemberToProject = new AddMemberToProject();
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
                    die();
                }

                if (isset($_POST['requestToJoin'])) {
                    $addMemberRequestToJoinProject = new AddMemberRequestToProject();
                    $addMemberRequestToJoinProject->data()->projectID = $project->getId();
                    $addMemberRequestToJoinProject->data()->userID = $loggedInUser->getId();
                    $addMemberRequestToJoinProject->exec();
                    echo json_encode(['result' => 'ok']);
                    die();
                }
                if (isset($_POST['approveRequestToJoin'])) {
                    $approveMemberRequestToJoinProject = new ApproveMemberRequestToProject();
                    $approveMemberRequestToJoinProject->data()->projectID = $project->getId();
                    $approveMemberRequestToJoinProject->data()->userID = $_POST['approveRequestToJoin'];
                    $approveMemberRequestToJoinProject->exec();
                    echo json_encode(['result' => 'ok']);
                    die();
                }
                if (isset($_POST['rejectRequestToJoin'])) {
                    $rejectMemberRequestToJoinProject = new RejectMemberRequestToProject();
                    $rejectMemberRequestToJoinProject->data()->projectID = $project->getId();
                    $rejectMemberRequestToJoinProject->data()->userID = $_POST['rejectRequestToJoin'];
                    $rejectMemberRequestToJoinProject->exec();
                    echo json_encode(['result' => 'ok']);
                    die();
                }

                if (isset($_POST['updateCountryRegion'])) {
                    $updateProjectCountryRegionCmd = new UpdateProjectCountryRegion();
                    $updateProjectCountryRegionCmd->data()->projectID = $project->getId();
                    $updateProjectCountryRegionCmd->data()->countryID = $_POST['countryID'];
                    $updateProjectCountryRegionCmd->data()->region = $_POST['region'];
                    $updateProjectCountryRegionCmd->exec();
                    echo json_encode(['result' => 'ok']);
                    die();
                }

                if (isset($_POST['newOrganisationID'])) {
                    $addOrganisationProjectCmd = new AddProjectToOrganisation();
                    $addOrganisationProjectCmd->data()->projectID = $project->getId();
                    $addOrganisationProjectCmd->data()->organisationID = $_POST['newOrganisationID'];
                    $addOrganisationProjectCmd->exec();
                    echo json_encode(['result' => 'ok']);
                    die();
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
                    die();
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

            } catch (ErrorHandler $e) {
                echo json_encode([
                    'result' => 'error',
                    'errors' => $e->getErrors()
                ]);
                die();
            }
        }

        if ($this->data()->format == 'json') {
            $owner = $project->getOwner();
            echo json_encode([
                'name' => $project->getName(),
                'url' => $project->getUrl(),
                'status' => $project->getStatus(),
                'description' => $project->getDescription(),
                'startDate' => $project->getStartDate(),
                'endDate' => $project->getEndDate(),
                'tags' => (new ProjectTagRepository())->getTagsNameByProjectID($project->getId()),
                'impactTagsA' => (new ProjectImpactTagARepository())->getTagsNameByProjectID($project->getId()),
                'impactTagsB' => (new ProjectImpactTagBRepository())->getTagsNameByProjectID($project->getId()),
                'impactTagsC' => (new ProjectImpactTagCRepository())->getTagsNameByProjectID($project->getId()),
                'members' => $this->getMembers($owner, $projectMembers),
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
                'countryRegionID' => $project->getCountryRegionID(),
                'countryRegion' => $project->getCountryRegion() ? $project->getCountryRegion()->getName() : '',
                'posts' => $this->getPostsForProject($project),
            ]);
            die();
        } else {
            $data = [
                'project' => $project,
                'canUserRequestMembership' => $canUserRequestMembership ?? false,
                'isOwner' => $isOwner ?? false,
            ];
            require __DIR__ . '/../../../www/project.php';
        }
    }

    /**
     * @return ProjectController_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @param Project $project
     * @param User $loggedInUser
     * @return bool
     */
    private function canUserRequestMembership(Project $project, User $loggedInUser)
    {
        if ($project->getOwner()->getId() == $loggedInUser->getId())
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
            $comments = (new ProjectPostCommentRepository())->getByPostID($post->getId());

            return [
                'id' => $post->getId(),
                'time' => $post->getTime(),
                'text' => $post->getText(),
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getFirstName() . ' ' . $user->getLastName(),
                    'profilePic' => $user->getProfilePicOrDefault(),
                ],
                'comments' => array_map(function (ProjectPostComment $comment) {
                    $user = $comment->getUser();
                    return [
                        'id' => $comment->getId(),
                        'comment' => $comment->getComment(),
                        'time' => $comment->getJsTime(),
                        'user' => [
                            'name' => $user->getFullName(),
                            'profilePic' => $user->getProfilePicOrDefault(),
                        ],
                    ];
                }, $comments),
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