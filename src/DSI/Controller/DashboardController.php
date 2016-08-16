<?php

namespace DSI\Controller;

use DSI\Entity\OrganisationMemberInvitation;
use DSI\Entity\OrganisationMemberRequest;
use DSI\Entity\ProjectMemberInvitation;
use DSI\Repository\OrganisationMemberInvitationRepository;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationMemberRequestRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\ProjectMemberInvitationRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\StoryRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\ApproveMemberInvitationToOrganisation;
use DSI\UseCase\ApproveMemberInvitationToProject;
use DSI\UseCase\ApproveMemberRequestToOrganisation;
use DSI\UseCase\RejectMemberInvitationToOrganisation;
use DSI\UseCase\RejectMemberInvitationToProject;
use DSI\UseCase\RejectMemberRequestToOrganisation;

class DashboardController
{
    public $format = 'html';

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = (new UserRepository())->getById($authUser->getUserId());

        if ($this->format == 'json') {
            try {
                if (isset($_POST['approveProjectInvitation'])) {
                    $approveInvitation = new ApproveMemberInvitationToProject();
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
                if (isset($_POST['rejectProjectInvitation'])) {
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
                if (isset($_POST['approveOrganisationInvitation'])) {
                    $approveInvitation = new ApproveMemberInvitationToOrganisation();
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
                if (isset($_POST['rejectOrganisationInvitation'])) {
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
                if (isset($_POST['approveOrganisationRequest'])) {
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
                if (isset($_POST['rejectOrganisationRequest'])) {
                    $rejectRequest = new RejectMemberRequestToOrganisation();
                    $rejectRequest->data()->executor = $loggedInUser;
                    $rejectRequest->data()->userID = $_POST['userID'] ?? 0;
                    $rejectRequest->data()->organisationID = $_POST['organisationID'] ?? 0;
                    $rejectRequest->exec();

                    echo json_encode([
                        'code' => 'ok',
                        'message' => [
                            'title' => 'OK',
                            'text' => 'You have declined user\'s the request to join the organisation!',
                        ]
                    ]);
                    return;
                }

                $projectInvitations = (new ProjectMemberInvitationRepository())->getByMemberID($loggedInUser->getId());
                $organisationInvitations = (new OrganisationMemberInvitationRepository())->getByMemberID($loggedInUser->getId());
                $organisationsWhereUserIsAdmin = (new OrganisationMemberRepository())->getByAdmin($loggedInUser);
                if ($organisationsWhereUserIsAdmin) {
                    $_orgIDs = [];
                    foreach ($organisationsWhereUserIsAdmin AS $_org)
                        $_orgIDs[] = $_org->getOrganisationID();

                    $organisationRequests = (new OrganisationMemberRequestRepository())->getByOrganisationIDs($_orgIDs);
                } else {
                    $organisationRequests = [];
                }

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
                                'url' => $urlHandler->profile($user->getId()),
                            ],
                            'organisation' => [
                                'id' => $organisation->getId(),
                                'name' => $organisation->getName(),
                                'url' => $urlHandler->organisation($organisation),
                            ],
                        ];
                    }, $organisationRequests),
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors()
                ]);
            }
        } else {
            $latestStories = (new StoryRepository())->getLast(3);
            $projectsMember = (new ProjectMemberRepository())->getByMemberID($loggedInUser->getId());
            $organisationsMember = (new OrganisationMemberRepository())->getByMemberID($loggedInUser->getId());

            require __DIR__ . '/../../../www/dashboard.php';
        }
    }
}