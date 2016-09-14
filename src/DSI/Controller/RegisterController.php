<?php
/**
 * Created by PhpStorm.
 * User: apandele
 * Date: 25/04/2016
 * Time: 15:45
 */

namespace DSI\Controller;


use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\Register;

class RegisterController
{
    public $responseFormat = 'html';

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        //$authUser->ifLoggedInRedirectTo(URL::myProfile());

        if (isset($_POST['register'])) {
            try {
                $register = new Register();
                $register->data()->email = $_POST['email'];
                $register->data()->password = $_POST['password'];
                $register->data()->sendEmail = true;
                $register->exec();

                $authUser->saveUserInSession($register->getUser());

                if ($this->responseFormat === 'json') {
                    echo json_encode([
                        'response' => 'ok',
                        'url' => $urlHandler->editProfile(),
                    ]);
                    die();
                } else {
                    go_to($urlHandler->editProfile());
                }
            } catch (ErrorHandler $e) {
                if ($this->responseFormat === 'json') {
                    echo json_encode([
                        'response' => 'error',
                        'errors' => $e->getErrors(),
                    ]);
                    die();
                } else {
                    $errors = $e->getErrors();
                }
            }
        }

        require __DIR__ . '/../../../www/views/register.php';
    }
}