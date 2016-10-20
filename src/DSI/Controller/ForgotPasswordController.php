<?php

namespace DSI\Controller;

use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\CompletePasswordRecovery;
use DSI\UseCase\CreatePasswordRecovery;
use DSI\UseCase\VerifyPasswordRecovery;

class ForgotPasswordController
{
    public $responseFormat = 'html';

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $authUser->ifLoggedInRedirectTo($urlHandler->profile($authUser->getUser()));

        try {
            if (isset($_POST['sendCode'])) {
                $passwordRecovery = new CreatePasswordRecovery();
                $passwordRecovery->data()->email = $_POST['email'] ?? '';
                $passwordRecovery->data()->sendEmail = true;
                $passwordRecovery->exec();
            } elseif (isset($_POST['verifyCode'])) {
                $verifyPasswordRecovery = new VerifyPasswordRecovery();
                $verifyPasswordRecovery->data()->email = $_POST['email'] ?? '';
                $verifyPasswordRecovery->data()->code = $_POST['code'] ?? '';
                $verifyPasswordRecovery->exec();
            } elseif (isset($_POST['completeForgotPassword'])) {
                $completePasswordRecovery = new CompletePasswordRecovery();
                $completePasswordRecovery->data()->email = $_POST['email'] ?? '';
                $completePasswordRecovery->data()->code = $_POST['code'] ?? '';
                $completePasswordRecovery->data()->password = $_POST['password'] ?? '';
                $completePasswordRecovery->data()->retypePassword = $_POST['retypePassword'] ?? '';
                $completePasswordRecovery->exec();
            }

            echo json_encode([
                'result' => 'ok',
            ]);
        } catch (ErrorHandler $e) {
            echo json_encode([
                'result' => 'error',
                'errors' => $e->getErrors(),
            ]);
        }
    }
}