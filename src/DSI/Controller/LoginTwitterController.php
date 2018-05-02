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
use Services\URL;
use DSI\UseCase\TwitterLogin;
use DSI\UseCase\TwitterRegister;

class LoginTwitterController
{
    public function exec()
    {
        $urlHandler = new URL();
        $authUser = new Auth();

        $twitterLoginService = new \DSI\Service\TwitterLogin();

        if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {
            // Step 3
            // Retrieve the temporary credentials from step 2
            $temporaryCredentials = unserialize($_SESSION['temporary_credentials']);
            // Third and final part to OAuth 1.0 authentication is to retrieve token
            // credentials (formally known as access tokens in earlier OAuth 1.0
            // specs).
            $tokenCredentials = $twitterLoginService->getProvider()->getTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);
            // Now, we'll store the token credentials and discard the temporary
            // ones - they're irrelevant at this stage.
            unset($_SESSION['temporary_credentials']);

            /** @var \League\OAuth1\Client\Server\User $user */
            $user = $twitterLoginService->getProvider()->getUserDetails($tokenCredentials);
            /*
            pr([
                'id' => $user->uid,
                'firstName' => $user->firstName,
                'lastName' => $user->lastName,
                'email' => $user->email,
            ]);
            */

            $userRepo = new UserRepo();
            if ($userRepo->twitterUIDExists($user->uid)) {
                $twitterLogin = new TwitterLogin();
                $twitterLogin->data()->twitterUID = $user->uid;
                $twitterLogin->exec();
                $authUser->saveUserInSession($twitterLogin->getUser());
                go_to($urlHandler->confirmPermanentLogin($twitterLogin->getUser()));
            } else {
                $twitterLogin = new TwitterRegister();
                $twitterLogin->data()->twitterUID = $user->uid;
                $twitterLogin->data()->firstName = $user->firstName;
                $twitterLogin->data()->lastName = $user->lastName;
                $twitterLogin->data()->email = $user->email;
                $twitterLogin->exec();
                $authUser->saveUserInSession($twitterLogin->getUser());
                go_to($urlHandler->editUserProfile($twitterLogin->getUser()));
            }
        } elseif (isset($_GET['denied'])) {
            // Step 2.5 - denied request to authorize client
            echo 'Hey! You denied the client access to your Twitter account! If you did this by mistake, you should <a href="?">try again</a>.';
        } else {
            // Step 2
            // First part of OAuth 1.0 authentication is retrieving temporary credentials.
            // These identify you as a client to the server.
            $temporaryCredentials = $twitterLoginService->getProvider()->getTemporaryCredentials();
            // Store the credentials in the session.
            $_SESSION['temporary_credentials'] = serialize($temporaryCredentials);
            // Second part of OAuth 1.0 authentication is to redirect the
            // resource owner to the login screen on the server.
            $twitterLoginService->getProvider()->authorize($temporaryCredentials);
        }
    }
}