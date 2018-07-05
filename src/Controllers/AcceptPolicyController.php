<?php

namespace Controllers;

use Actions\Register\AcceptPolicy;
use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use Models\UserAccept;
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

        $userAccept = UserAccept
            ::where(UserAccept::UserID, $loggedInUser->getId())
            ->count();
        if ($userAccept)
            return go_to($urlHandler->dashboard());

        $errors = [];
        if (Request::isPost()) {
            try {
                $exec = new AcceptPolicy();
                $exec->user = $loggedInUser;
                $exec->acceptTerms = $_POST['accept-terms'];
                $exec->emailSubscription = $_POST['email-subscription'];
                $exec->exec();
                return go_to($urlHandler->dashboard());

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