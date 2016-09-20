<?php

namespace DSI\Controller;

use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\CompletePasswordRecovery;

class ResetPasswordController
{
    /** @var  int */
    public $organisationID;

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifLoggedInRedirectTo($urlHandler->home());

        if (isset($_POST['save'])) {
            try {
                $completePasswordRecovery = new CompletePasswordRecovery();
                $completePasswordRecovery->data()->email = $_POST['email'] ?? '';
                $completePasswordRecovery->data()->code = $_POST['code'] ?? '';
                $completePasswordRecovery->data()->password = $_POST['password'] ?? '';
                $completePasswordRecovery->data()->retypePassword = $_POST['retypePassword'] ?? '';
                $completePasswordRecovery->exec();

                $user = $completePasswordRecovery->getUser();
                $authUser->saveUserInSession($user);

                echo json_encode([
                    'code' => 'ok',
                    'url' => $urlHandler->editProfile()
                ]);
            } catch (ErrorHandler $e) {
                echo json_encode([
                    'code' => 'error',
                    'errors' => $e->getErrors(),
                ]);
            }
            return;
        }

        require __DIR__ . '/../../../www/views/reset-password.php';
    }
}