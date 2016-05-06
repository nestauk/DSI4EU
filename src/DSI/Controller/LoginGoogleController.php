<?php
/**
 * Created by PhpStorm.
 * User: apandele
 * Date: 25/04/2016
 * Time: 15:45
 */

namespace DSI\Controller;

use DSI\Repository\UserRepository;
use DSI\Service\Auth;
use DSI\Service\GoogleLogin;
use DSI\Service\URL;

use DSI\UseCase\GoogleRegister;
use League\OAuth2\Client\Token\AccessToken;

class LoginGoogleController
{

    public function exec()
    {
        $authUser = new Auth();

        /** @var GoogleLogin */
        $googleLoginService = new GoogleLogin();

        if (!empty($_GET['error'])) {
            // Got an error, probably user denied access
            exit('Got error: ' . $_GET['error']);
        } elseif (empty($_GET['code'])) {
            go_to($googleLoginService->getUrl());
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            // State is invalid, possible CSRF attack in progress
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        } else {
            /** @var AccessToken $token */
            $token = $googleLoginService->getProvider()->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);

            // Optional: Now you have a token you can look up a users profile data
            try {
                /** @var \League\OAuth2\Client\Provider\GoogleUser $user */
                $user = $googleLoginService->getProvider()->getResourceOwner($token);
                $userRepo = new UserRepository();
                if ($userRepo->googleUIDExists($user->getId())) {
                    $googleLogin = new \DSI\UseCase\GoogleLogin();
                    $googleLogin->data()->googleUID = $user->getId();
                    $googleLogin->exec();
                    $authUser->saveUserInSession($googleLogin->getUser());
                    go_to(URL::home());
                } else {
                    $googleLogin = new GoogleRegister();
                    $googleLogin->data()->googleUID = $user->getId();
                    $googleLogin->data()->firstName = $user->getFirstName();
                    $googleLogin->data()->lastName = $user->getLastName();
                    $googleLogin->data()->email = $user->getEmail();
                    $googleLogin->exec();
                    $authUser->saveUserInSession($googleLogin->getUser());
                    go_to(URL::home());
                }
            } catch (\Exception $e) {
                pr($e);
                exit('Something went wrong...');
            }
        }
    }
}