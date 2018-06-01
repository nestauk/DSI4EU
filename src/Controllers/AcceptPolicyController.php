<?php

namespace Controllers;

use Actions\Register\AcceptPolicy;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use Services\Request;
use Services\URL;
use Services\View;

class AcceptPolicyController
{
    public $responseFormat = 'html';

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo($urlHandler->login());
        $loggedInUser = $authUser->getUser();

        $errors = [];
        if (Request::isMethod(Request::METHOD_POST)) {
            try {
                $exec = new AcceptPolicy();
                $exec->user = $loggedInUser;
                $exec->acceptTerms = $_POST['accept-terms'];
                $exec->emailSubscription = $_POST['email-subscription'];
                $exec->exec();
                go_to($urlHandler->dashboard());
            } catch (ErrorHandler $e) {
                $errors = $e->getErrors();
            }
        }

        View::render(__DIR__ . '/../Views/accept-policy.php', [
            'loggedInUser' => $loggedInUser,
            'errors' => $errors,
        ]);
    }
}