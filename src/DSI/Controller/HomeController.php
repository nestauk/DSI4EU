<?php

namespace DSI\Controller;

use DSI\Entity\ProjectMemberInvitation;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\ProjectMemberInvitationRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\StoryRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\ApproveMemberInvitationToProject;

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

                        echo json_encode(['code' => 'ok']);
                        return;
                    }

                    $projectInvitations = (new ProjectMemberInvitationRepository())->getByMemberID($loggedInUser->getId());
                    echo json_encode([
                        'projectInvitations' => array_map(function (ProjectMemberInvitation $projectMember) {
                            $project = $projectMember->getProject();
                            return [
                                'id' => $project->getId(),
                                'name' => $project->getName(),
                                'url' => URL::project($project->getId(), $project->getName()),
                            ];
                        }, $projectInvitations),
                    ]);
                } catch(ErrorHandler $e){
                    echo json_encode([
                        'code' => 'error',
                        'errors' => $e->getErrors()
                    ]);
                }
            } else {
                $totalProjects = (new ProjectRepository())->countProjects();
                $latestStories = (new StoryRepository())->getLast(3);
                $projectsMember = (new ProjectMemberRepository())->getByMemberID($loggedInUser->getId());
                $organisationsMember = (new OrganisationMemberRepository())->getByMemberID($loggedInUser->getId());

                require __DIR__ . '/../../../www/home-dashboard.php';
            }
        } else {
            $loggedInUser = null;
            $hideSearch = true;

            require __DIR__ . '/../../../www/home.php';
        }
    }
}