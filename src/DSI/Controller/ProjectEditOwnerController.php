<?php

namespace DSI\Controller;

use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectRepositoryInAPC;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\Projects\ChangeOwner;

class ProjectEditOwnerController
{
    /** @var  int */
    public $projectID;

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        $projectRepository = new ProjectRepositoryInAPC();
        $project = $projectRepository->getById($this->projectID);
        $owner = $project->getOwner();

        if ($owner->getId() == $loggedInUser->getId())
            $isOwner = true;
        else
            $isOwner = false;

        if (!$isOwner AND !$loggedInUser->isSysAdmin())
            go_to($urlHandler->home());

        if (isset($_POST['save'])) {
            try {
                $exec = new ChangeOwner();
                $exec->data()->executor = $loggedInUser;
                $exec->data()->member = (new UserRepository())->getById($_POST['newOwnerID']);
                $exec->data()->project = $project;
                $exec->exec();

                echo json_encode([
                    'code' => 'ok',
                    'url' => $urlHandler->project($project),
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors(),
                ]);
            }
            return;
        }

        $pageTitle = $project->getName();
        $users = (new UserRepository())->getAll();
        require __DIR__ . '/../../../www/views/project-editOwner.php';
    }
}