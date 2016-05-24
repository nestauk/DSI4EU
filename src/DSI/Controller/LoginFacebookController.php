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
use DSI\Service\URL;
use DSI\UseCase\FacebookLogin;
use DSI\UseCase\FacebookRegister;

class LoginFacebookController
{
    public function exec()
    {
        $authUser = new Auth();

        $facebookLoginService = new \DSI\Service\FacebookLogin();

        if (!isset($_GET['code'])) {
            go_to($facebookLoginService->getUrl());
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            echo 'Invalid state.';
            return;
        } else {
            $token = $facebookLoginService->getProvider()->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);
            try {
                /** @var \League\OAuth2\Client\Provider\FacebookUser $user */
                $user = $facebookLoginService->getProvider()->getResourceOwner($token);
                $userRepo = new UserRepository();
                if ($userRepo->facebookUIDExists($user->getId())) {
                    $facebookLogin = new FacebookLogin();
                    $facebookLogin->data()->facebookUID = $user->getId();
                    $facebookLogin->exec();
                    $authUser->saveUserInSession($facebookLogin->getUser());
                    go_to(URL::myProfile());
                } else {
                    $facebookLogin = new FacebookRegister();
                    $facebookLogin->data()->facebookUID = $user->getId();
                    $facebookLogin->data()->firstName = $user->getFirstName();
                    $facebookLogin->data()->lastName = $user->getLastName();
                    $facebookLogin->data()->email = $user->getEmail();
                    $facebookLogin->exec();
                    $authUser->saveUserInSession($facebookLogin->getUser());
                    go_to(URL::myProfile());
                }
            } catch (\Exception $e) {
                pr($e);
                exit('Something went wrong...');
            }
        }
    }
}