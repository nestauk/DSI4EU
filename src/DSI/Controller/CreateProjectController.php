<?php

namespace DSI\Controller;

use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\CreateProject;

class CreateProjectController
{
    public function __construct()
    {
    }

    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());

        $loggedInUser = (new UserRepository())->getById($authUser->getUserId());

        try {
            $createProject = new CreateProject();
            $createProject->data()->name = $_POST['name'] ?? '';
            $createProject->data()->owner = $loggedInUser;
            $createProject->exec();

            echo json_encode([
                'result' => 'ok',
                'url' => URL::project($createProject->getProject()),
            ]);
        } catch (ErrorHandler $e) {
            echo json_encode([
                'result' => 'error',
                'errors' => $e->getErrors(),
            ]);
        }
        die();
    }
}