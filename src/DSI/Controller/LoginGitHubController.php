<?php
/**
 * Created by PhpStorm.
 * User: apandele
 * Date: 25/04/2016
 * Time: 15:45
 */

namespace DSI\Controller;

use DSI\Repository\UserRepo;
use DSI\Service\Auth;
use DSI\Service\GitHubLogin;
use Services\URL;
use DSI\UseCase\GitHubRegister;

class LoginGitHubController
{
    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();

        /** @var GitHubLogin */
        $gitHubLoginService = new GitHubLogin();

        if (!isset($_GET['code'])) {
            // If we don't have an authorization code then get one
            go_to($gitHubLoginService->getUrl());
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            // Check given state against previously stored one to mitigate CSRF attack
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        } else {
            // Try to get an access token (using the authorization code grant)
            $token = $gitHubLoginService->getProvider()->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);

            // Optional: Now you have a token you can look up a users profile data
            try {
                /** @var \League\OAuth2\Client\Provider\GithubResourceOwner $user */
                $user = $gitHubLoginService->getProvider()->getResourceOwner($token);
                $userRepo = new UserRepo();

                pr($user);

                if ($userRepo->gitHubUIDExists($user->getId())) {
                    $gitHubLogin = new \DSI\UseCase\GitHubLogin();
                    $gitHubLogin->data()->gitHubUID = $user->getId();
                    $gitHubLogin->exec();
                    $authUser->saveUserInSession($gitHubLogin->getUser());
                    go_to($urlHandler->confirmPermanentLogin($gitHubLogin->getUser()));
                } else {
                    $name = explode(' ', $user->getName());
                    $lastName = array_pop($name);
                    $firstName = implode(' ', $name);
                    $gitHubLogin = new GitHubRegister();
                    $gitHubLogin->data()->gitHubUID = $user->getId();
                    $gitHubLogin->data()->firstName = $firstName;
                    $gitHubLogin->data()->lastName = $lastName;
                    $gitHubLogin->data()->email = $user->getEmail();
                    $gitHubLogin->exec();
                    $authUser->saveUserInSession($gitHubLogin->getUser());
                    go_to($urlHandler->editUserProfile($gitHubLogin->getUser()));
                }
            } catch (\Exception $e) {
                pr($e);
                exit('Something went wrong...');
            }

            // Use this to interact with an API on the users behalf
            echo $token->getToken();
        }
    }
}