<?php

namespace DSI\Controller;

use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use Services\URL;
use DSI\UseCase\CreateProject;

class CreateProjectController
{
    public function __construct()
    {
    }

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        try {
            $createProject = new CreateProject();
            $createProject->data()->name = $_POST['name'] ?? '';
            $createProject->data()->owner = $loggedInUser;
            $createProject->exec();

            echo json_encode([
                'result' => 'ok',
                'url' => $urlHandler->projectEdit($createProject->getProject()),
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