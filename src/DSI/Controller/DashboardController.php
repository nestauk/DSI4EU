<?php

namespace DSI\Controller;

use DSI\Entity\OrganisationMemberInvitation;
use DSI\Entity\OrganisationMemberRequest;
use DSI\Entity\OrganisationProject;
use DSI\Entity\ProjectMemberInvitation;
use DSI\Entity\ProjectMemberRequest;
use DSI\Entity\ProjectPost;
use DSI\Entity\Story;
use DSI\Entity\User;
use DSI\Repository\OrganisationFollowRepository;
use DSI\Repository\OrganisationMemberInvitationRepository;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationMemberRequestRepository;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\ProjectFollowRepository;
use DSI\Repository\ProjectMemberInvitationRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectMemberRequestRepository;
use DSI\Repository\ProjectPostRepository;
use DSI\Repository\StoryRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\JsModules;
use DSI\Service\URL;
use DSI\UseCase\AcceptMemberInvitationToOrganisation;
use DSI\UseCase\AcceptMemberInvitationToProject;
use DSI\UseCase\ApproveMemberRequestToOrganisation;
use DSI\UseCase\ApproveMemberRequestToProject;
use DSI\UseCase\RejectMemberInvitationToOrganisation;
use DSI\UseCase\RejectMemberInvitationToProject;
use DSI\UseCase\RejectMemberRequestToOrganisation;
use DSI\UseCase\RejectMemberRequestToProject;
use DSI\UseCase\SendTerminateAccountPreconfirmationEmail;

class DashboardController
{
    public $format = 'html';

    /** @var URL */
    private $urlHandler;

    public function exec()
    {
        $this->urlHandler = $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        if ($this->format == 'json') {
            try {
                if (isset($_POST['approveProjectInvitation']))
                    return $this->approveProjectInvitation($loggedInUser);

                if (isset($_POST['rejectProjectInvitation']))
                    return $this->rejectProjectInvitation($loggedInUser);

                if (isset($_POST['approveOrganisationInvitation']))
                    return $this->approveOrganisationInvitation($loggedInUser);

                if (isset($_POST['rejectOrganisationInvitation']))
                    return $this->rejectOrganisationInvitation($loggedInUser);

                if (isset($_POST['approveOrganisationRequest']))
                    return $this->approveOrganisationRequest($loggedInUser);

                if (isset($_POST['rejectOrganisationRequest']))
                    return $this->rejectOrganisationRequest($loggedInUser);

                if (isset($_POST['approveProjectRequest']))
                    return $this->approveProjectRequest($loggedInUser);

                if (isset($_POST['rejectProjectRequest']))
                    return $this->rejectProjectRequest($loggedInUser);

                if (isset($_POST['terminateAccount']))
                    return $this->terminateAccount($loggedInUser);

                $projectInvitations = (new ProjectMemberInvitationRepository())->getByMemberID($loggedInUser->getId());
                $projectRequests = $this->getProjectRequests($loggedInUser);
                $organisationInvitations = (new OrganisationMemberInvitationRepository())->getByMemberID($loggedInUser->getId());
                $organisationRequests = $this->getOrganisationRequests($loggedInUser);

                echo json_encode([
                    'projectInvitations' => array_map(function (ProjectMemberInvitation $projectMember) use ($urlHandler) {
                        $project = $projectMember->getProject();
                        return [
                            'id' => $project->getId(),
                            'name' => $project->getName(),
                            'url' => $urlHandler->project($project),
                        ];
                    }, $projectInvitations),
                    'organisationInvitations' => array_map(function (OrganisationMemberInvitation $organisationMember) use ($urlHandler) {
                        $organisation = $organisationMember->getOrganisation();
                        return [
                            'id' => $organisation->getId(),
                            'name' => $organisation->getName(),
                            'url' => $urlHandler->organisation($organisation),
                        ];
                    }, $organisationInvitations),
                    'organisationRequests' => array_map(function (OrganisationMemberRequest $organisationMemberReq) use ($urlHandler) {
                        $user = $organisationMemberReq->getMember();
                        $organisation = $organisationMemberReq->getOrganisation();
                        return [
                            'user' => [
                                'id' => $user->getId(),
                                'name' => $user->getFullName(),
                                'url' => $urlHandler->profile($user),
                            ],
                            'organisation' => [
                                'id' => $organisation->getId(),
                                'name' => $organisation->getName(),
                                'url' => $urlHandler->organisation($organisation),
                            ],
                        ];
                    }, $organisationRequests),
                    'projectRequests' => array_map(function (ProjectMemberRequest $projectMemberReq) use ($urlHandler) {
                        $user = $projectMemberReq->getMember();
                        $project = $projectMemberReq->getProject();
                        return [
                            'user' => [
                                'id' => $user->getId(),
                                'name' => $user->getFullName(),
                                'url' => $urlHandler->profile($user),
                            ],
                            'project' => [
                                'id' => $project->getId(),
                                'name' => $project->getName(),
                                'url' => $urlHandler->project($project),
                            ],
                        ];
                    }, $projectRequests),
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        } else {
            /** @var DashboardController_Update[] $updates */
            $updates = $this->getUpdates($loggedInUser);
            $projectsMember = (new ProjectMemberRepository())->getByMemberID($loggedInUser->getId());
            $organisationsMember = (new OrganisationMemberRepository())->getByMemberID($loggedInUser->getId());
            JsModules::setTranslations(true);
            require __DIR__ . '/../../../www/views/dashboard.php';
        }

        return null;
    }

    /**
     * @param User $loggedInUser
     * @return \DSI\Entity\OrganisationMemberRequest[]
     */
    private function getOrganisationRequests(User $loggedInUser)
    {
        $organisationsWhereUserIsAdmin = (new OrganisationMemberRepository())->getByAdmin($loggedInUser);
        if ($organisationsWhereUserIsAdmin) {
            $_orgIDs = [];
            foreach ($organisationsWhereUserIsAdmin AS $_org)
                $_orgIDs[] = $_org->getOrganisationID();

            $organisationRequests = (new OrganisationMemberRequestRepository())->getByOrganisationIDs($_orgIDs);
        } else {
            $organisationRequests = [];
        }
        return $organisationRequests;
    }

    /**
     * @param User $loggedInUser
     * @return \DSI\Entity\ProjectMemberRequest[]
     */
    private function getProjectRequests(User $loggedInUser)
    {
        $projectsWhereUserIsAdmin = (new ProjectMemberRepository())->getByAdmin($loggedInUser);
        if ($projectsWhereUserIsAdmin) {
            $_projectIDs = [];
            foreach ($projectsWhereUserIsAdmin AS $_project)
                $_projectIDs[] = $_project->getProjectID();

            $projectRequests = (new ProjectMemberRequestRepository())->getByProjectIDs($_projectIDs);
        } else {
            $projectRequests = [];
        }
        return $projectRequests;
    }

    /**
     * @param User $loggedInUser
     * @return null
     */
    private function approveProjectInvitation(User $loggedInUser)
    {
        $approveInvitation = new AcceptMemberInvitationToProject();
        $approveInvitation->data()->executor = $loggedInUser;
        $approveInvitation->data()->userID = $loggedInUser->getId();
        $approveInvitation->data()->projectID = isset($_POST['projectID']) ? $_POST['projectID'] : 0;
        $approveInvitation->exec();

        echo json_encode([
            'code' => 'ok',
            'message' => [
                'title' => 'Success!',
                'text' => 'You have accepted the invitation to be part of the project!',
            ]
        ]);

        return;
    }

    /**
     * @param User $loggedInUser
     * @return null
     */
    private function rejectProjectInvitation(User $loggedInUser)
    {
        $rejectInvitation = new RejectMemberInvitationToProject();
        $rejectInvitation->data()->executor = $loggedInUser;
        $rejectInvitation->data()->userID = $loggedInUser->getId();
        $rejectInvitation->data()->projectID = isset($_POST['projectID']) ? $_POST['projectID'] : 0;
        $rejectInvitation->exec();

        echo json_encode([
            'code' => 'ok',
            'message' => [
                'title' => 'OK',
                'text' => 'You have declined the invitation to be part of the project!',
            ]
        ]);

        return;
    }

    /**
     * @param $loggedInUser
     * @return null
     */
    private function approveOrganisationInvitation(User $loggedInUser)
    {
        $approveInvitation = new AcceptMemberInvitationToOrganisation();
        $approveInvitation->data()->executor = $loggedInUser;
        $approveInvitation->data()->userID = $loggedInUser->getId();
        $approveInvitation->data()->organisationID = isset($_POST['organisationID']) ? $_POST['organisationID'] : 0;
        $approveInvitation->exec();

        echo json_encode([
            'code' => 'ok',
            'message' => [
                'title' => 'Success!',
                'text' => 'You have accepted the invitation to be part of the organisation!',
            ]
        ]);

        return;
    }

    /**
     * @param $loggedInUser
     * @return null
     */
    private function rejectOrganisationInvitation(User $loggedInUser)
    {
        $rejectInvitation = new RejectMemberInvitationToOrganisation();
        $rejectInvitation->data()->executor = $loggedInUser;
        $rejectInvitation->data()->userID = $loggedInUser->getId();
        $rejectInvitation->data()->organisationID = isset($_POST['organisationID']) ? $_POST['organisationID'] : 0;
        $rejectInvitation->exec();

        echo json_encode([
            'code' => 'ok',
            'message' => [
                'title' => 'OK',
                'text' => 'You have declined the invitation to be part of the organisation!',
            ]
        ]);
        return;
    }

    /**
     * @param $loggedInUser
     * @return null
     */
    private function approveOrganisationRequest($loggedInUser)
    {
        $approveRequest = new ApproveMemberRequestToOrganisation();
        $approveRequest->data()->executor = $loggedInUser;
        $approveRequest->data()->userID = $_POST['userID'] ?? 0;
        $approveRequest->data()->organisationID = $_POST['organisationID'] ?? 0;
        $approveRequest->exec();

        echo json_encode([
            'code' => 'ok',
            'message' => [
                'title' => 'Success!',
                'text' => 'You have accepted user\'s request to be part of the organisation!',
            ]
        ]);
        return;
    }

    /**
     * @param $loggedInUser
     * @return null
     */
    private function rejectOrganisationRequest($loggedInUser)
    {
        $rejectRequest = new RejectMemberRequestToOrganisation();
        $rejectRequest->data()->executor = $loggedInUser;
        $rejectRequest->data()->userID = $_POST['userID'] ?? 0;
        $rejectRequest->data()->organisationID = $_POST['organisationID'] ?? 0;
        $rejectRequest->exec();

        echo json_encode([
            'code' => 'ok',
            'message' => [
                'title' => 'OK',
                'text' => 'You have declined user\'s request to join the organisation!',
            ]
        ]);
        return;
    }

    /**
     * @param $loggedInUser
     * @return null
     */
    private function approveProjectRequest($loggedInUser)
    {
        $approveRequest = new ApproveMemberRequestToProject();
        $approveRequest->data()->executor = $loggedInUser;
        $approveRequest->data()->userID = $_POST['userID'] ?? 0;
        $approveRequest->data()->projectID = $_POST['projectID'] ?? 0;
        $approveRequest->exec();

        echo json_encode([
            'code' => 'ok',
            'message' => [
                'title' => 'Success!',
                'text' => "You have accepted user's request to be part of the project!",
            ]
        ]);
        return;
    }

    /**
     * @param $loggedInUser
     * @return null
     */
    private function rejectProjectRequest($loggedInUser)
    {
        $rejectRequest = new RejectMemberRequestToProject();
        $rejectRequest->data()->executor = $loggedInUser;
        $rejectRequest->data()->userID = $_POST['userID'] ?? 0;
        $rejectRequest->data()->projectID = $_POST['projectID'] ?? 0;
        $rejectRequest->exec();

        echo json_encode([
            'code' => 'ok',
            'message' => [
                'title' => 'OK',
                'text' => "You have declined user's request to join the project!",
            ]
        ]);
        return;
    }

    /**
     * @param $loggedInUser
     * @return null
     */
    private function terminateAccount($loggedInUser)
    {
        $exec = new SendTerminateAccountPreconfirmationEmail();
        $exec->setUser($loggedInUser);
        $exec->setSendEmail(true);
        $exec->exec();

        echo json_encode([
            'code' => 'ok',
        ]);
        return;
    }

    private function getUpdates(User $loggedInUser)
    {
        $updates = array_merge(
            $this->getLatestStories(3),
            $this->getLatestFollowedProjectPosts($loggedInUser),
            $this->getLatestFollowedOrganisationProjects($loggedInUser)
        );

        usort($updates, function (DashboardController_Update $a, DashboardController_Update $b) {
            return $this->sortDashboardUpdates($a, $b);
        });

        return $updates;
    }

    /**
     * @param $limit
     * @return DashboardController_Update[]
     */
    private function getLatestStories($limit)
    {
        return array_map(function (Story $story) {
            $update = new DashboardController_Update();
            $update->title = $story->getTitle();
            $update->content = substr(strip_tags($story->getContent()), 0, 200);
            $update->link = $this->urlHandler->blogPost($story);
            $update->published = strtotime($story->getDatePublished());
            $update->timestamp = strtotime($story->getDatePublished());
            return $update;
        }, (new StoryRepository())->getLast($limit));
    }

    /**
     * @param User $loggedInUser
     * @return array
     */
    private function getLatestFollowedProjectPosts(User $loggedInUser)
    {
        $updates = array_map(function (ProjectPost $projectPost) {
            $update = new DashboardController_Update();
            $update->title = $projectPost->getProject()->getName() . ' added a new post';
            $update->content = substr(strip_tags($projectPost->getText()), 0, 200);
            $update->timestamp = strtotime($projectPost->getTime());
            $update->link = $this->urlHandler->project($projectPost->getProject());
            return $update;
        }, (new ProjectPostRepository())->getByProjectIDs(
            (new ProjectFollowRepository())->getProjectIDsForUser($loggedInUser))
        );

        usort($updates, function (DashboardController_Update $a, DashboardController_Update $b) {
            return $this->sortDashboardUpdates($a, $b);
        });

        return array_slice($updates, 0, 3);
    }

    /**
     * @param User $loggedInUser
     * @return array
     */
    private function getLatestFollowedOrganisationProjects(User $loggedInUser)
    {
        $updates = array_map(function (OrganisationProject $organisationProject) {
            $update = new DashboardController_Update();
            $update->title = sprintf(
                '%s has added %s',
                $organisationProject->getOrganisation()->getName(),
                $organisationProject->getProject()->getName()
            );
            $update->timestamp = strtotime($organisationProject->getProject()->getCreationTime());
            $update->link = $this->urlHandler->organisation($organisationProject->getOrganisation());
            return $update;
        }, (new OrganisationProjectRepository())->getByOrganisationIDs(
            (new OrganisationFollowRepository())->getOrganisationIDsForUser($loggedInUser))
        );

        usort($updates, function (DashboardController_Update $a, DashboardController_Update $b) {
            return $this->sortDashboardUpdates($a, $b);
        });

        return array_slice($updates, 0, 3);
    }

    /**
     * @param DashboardController_Update $a
     * @param DashboardController_Update $b
     * @return int
     */
    private function sortDashboardUpdates(DashboardController_Update $a, DashboardController_Update $b):int
    {
        if ($a->timestamp == $b->timestamp) {
            return 0;
        }
        return ($a->timestamp > $b->timestamp) ? -1 : 1;
    }
}

class DashboardController_Update
{
    /** @var String */
    public $title,
        $content,
        $link;

    /** @var int */
    public $timestamp;

    public function getPublishDate($format = 'd M Y')
    {
        return date($format, $this->timestamp);
    }
}