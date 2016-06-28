<?php

namespace DSI\Controller;

use DSI\Entity\OrganisationMemberInvitation;
use DSI\Entity\ProjectMemberInvitation;
use DSI\Repository\OrganisationMemberInvitationRepository;
use DSI\Repository\OrganisationMemberRepository;
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
use DSI\UseCase\RejectMemberInvitationToOrganisation;
use DSI\UseCase\RejectMemberInvitationToProject;

class HomeController
{
    public $format = 'html';

    public function exec()
    {
        $authUser = new Auth();

        if ($authUser->isLoggedIn()) {
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

                    $projectInvitations = (new ProjectMemberInvitationRepository())->getByMemberID($loggedInUser->getId());
                    $organisationInvitations = (new OrganisationMemberInvitationRepository())->getByMemberID($loggedInUser->getId());
                    echo json_encode([
                        'projectInvitations' => array_map(function (ProjectMemberInvitation $projectMember) {
                            $project = $projectMember->getProject();
                            return [
                                'id' => $project->getId(),
                                'name' => $project->getName(),
                                'url' => URL::project($project->getId(), $project->getName()),
                            ];
                        }, $projectInvitations),
                        'organisationInvitations' => array_map(function (OrganisationMemberInvitation $organisationMember) {
                            $organisation = $organisationMember->getOrganisation();
                            return [
                                'id' => $organisation->getId(),
                                'name' => $organisation->getName(),
                                'url' => URL::organisation($organisation),
                            ];
                        }, $organisationInvitations),
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

                require __DIR__ . '/../../../www/home-dashboard.php';
            }
        } else {
            $loggedInUser = null;
            $hideSearch = true;
            $stories = (new StoryRepository())->getPublishedLast(3);

            require __DIR__ . '/../../../www/home.php';
        }
    }
}