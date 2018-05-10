<?php
namespace Controllers;

use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use Services\URL;
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
                $register->data()->recaptchaResponse = $this->checkRecaptcha();
                $register->exec();

                $authUser->saveUserInSession($register->getUser());

                if ($this->responseFormat === 'json') {
                    echo json_encode([
                        'response' => 'ok',
                        'url' => $urlHandler->editUserProfile($register->getUser()),
                    ]);
                    die();
                } else {
                    go_to($urlHandler->editUserProfile($register->getUser()));
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

        require __DIR__ . '/../Views/register.php';
    }

    private function checkRecaptcha():bool
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'secret' => '6Ldc3QgUAAAAALNmk8zJczokhAVEa_7mvUpEotQ_',
            'response' => $_POST['g-recaptcha-response'],
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        $response = json_decode($response, true);

        return (bool)$response['success'];
    }
}