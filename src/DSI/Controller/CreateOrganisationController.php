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
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        try {
            $createOrganisation = new CreateOrganisation();
            $createOrganisation->data()->name = $_POST['name'] ?? '';
            $createOrganisation->data()->owner = $loggedInUser;
            $createOrganisation->exec();

            echo json_encode([
                'result' => 'ok',
                'url' => $urlHandler->organisationEdit($createOrganisation->getOrganisation()),
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