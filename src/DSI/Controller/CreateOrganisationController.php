<?php

namespace DSI\Controller;

use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\CreateOrganisation;

class CreateOrganisationController
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
            $createOrganisation = new CreateOrganisation();
            $createOrganisation->data()->name = $_POST['name'] ?? '';
            $createOrganisation->data()->owner = $loggedInUser;
            $createOrganisation->exec();

            echo json_encode([
                'result' => 'ok',
                'url' => URL::organisation($createOrganisation->getOrganisation()),
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