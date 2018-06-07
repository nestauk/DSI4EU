<?php

namespace Controllers;

use DSI\Service\Auth;
use DSI\Service\ErrorHandler;
use Services\App;
use Services\URL;
use Actions\Register\Register;
use Services\View;

class RegisterController
{
    public $responseFormat = 'html';

    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();
        $errors = [];
        // $authUser->ifLoggedInRedirectTo(URL::myProfile());

        if (isset($_POST['register'])) {
            try {
                $register = new Register();
                $register->data()->email = $_POST['email'];
                $register->data()->password = $_POST['password'];
                $register->data()->emailSubscription = $_POST['email-subscription'];
                $register->data()->acceptTerms = $_POST['accept-terms'];
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

        return View::render(__DIR__ . '/../Views/register.php', [
            'authUser' => $authUser,
            'urlHandler' => $urlHandler,
            'errors' => $errors,
        ]);
    }

    private function checkRecaptcha(): bool
    {
        if (App::getEnv() === APP::DEV OR App::getEnv() === APP::TEST)
            return true;

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