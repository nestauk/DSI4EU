<?php
namespace DSI\Controller;

use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use DSI\Service\URL;
use DSI\UseCase\Login;
use DSI\UseCase\RememberPermanentLogin;

class LoginController
{
    public $responseFormat = 'html';

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        if ($authUser->isLoggedIn())
            go_to($urlHandler->profile($authUser->getUser()));

        if (isset($_POST['login'])) {
            try {
                $login = new Login();
                $login->data()->email = $_POST['email'];
                $login->data()->password = $_POST['password'];
                $login->exec();

                $authUser->saveUserInSession($login->getUser());

                $action = new RememberPermanentLogin();
                $action->setUser($login->getUser());
                $action->exec();

                if ($this->responseFormat === 'json') {
                    echo json_encode([
                        'response' => 'ok'
                    ]);
                    return;
                } else {
                    go_to($urlHandler->profile($login->getUser()));
                }
            } catch (ErrorHandler $e) {
                if ($this->responseFormat === 'json') {
                    echo json_encode([
                        'response' => 'error',
                        'errors' => $e->getErrors(),
                    ]);
                    return;
                } else {
                    $errors = $e->getErrors();
                }
            }
        }

        require __DIR__ . '/../../../www/views/login.php';
    }
}