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
use DSI\UseCase\Login;

class LoginController
{
    public $responseFormat = 'html';

    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifLoggedInRedirectTo(URL::myProfile());

        if (isset($_POST['login'])) {
            try {
                $login = new Login();
                $login->data()->email = $_POST['email'];
                $login->data()->password = $_POST['password'];
                $login->exec();

                $authUser->saveUserInSession($login->getUser());

                if($this->responseFormat === 'json'){
                    echo json_encode([
                        'response' => 'ok'
                    ]);
                    die();
                } else {
                    go_to(URL::myProfile());
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

        // require __DIR__ . '/../../../www/login.php';
    }
}