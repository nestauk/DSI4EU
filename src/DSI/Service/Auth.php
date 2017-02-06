<?php
namespace DSI\Service;

use DSI\Entity\User;
use DSI\Repository\AuthTokenRepository;
use DSI\Repository\UserRepository;

class Auth
{
    public function __construct()
    {
        if (isset($_COOKIE[PermanentLogin::CookieName]) AND !$this->isLoggedIn()) {
            try {
                $authToken = $this->getAuthToken($_COOKIE[PermanentLogin::CookieName]);
                $this->updateCookieToExpireIn($oneYear = 3600 * 24 * 356);
                $this->saveUserInSession($authToken->getUser());
            } catch (\Exception $e) {
                setcookie(PermanentLogin::CookieName, '', 1);
            }
        }
    }

    public function isLoggedIn(): bool
    {
        return (
            isset($_SESSION['user']) AND
            isset($_SESSION['user']['userID']) AND
            $_SESSION['user']['userID'] > 0
        );
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        if (isset($_SESSION['user']) AND isset($_SESSION['user']['userID']))
            return (int)$_SESSION['user']['userID'];
        else
            return 0;
    }

    public function getUser()
    {
        return (new UserRepository())->getById($this->getUserId());
    }

    public function getUserIfLoggedIn()
    {
        if ($this->isLoggedIn())
            return $this->getUser();
        else
            return null;
    }

    public function saveUserInSession(User $user)
    {
        session_regenerate_id(TRUE);

        $_SESSION['user']['userID'] = $user->getId();
        $_SESSION['user']['firstName'] = $user->getFirstName();
        $_SESSION['user']['lastName'] = $user->getLastName();
    }

    public function removeUserFromSession(User $user)
    {
        $this->deleteUserAuthTokens($user);

        unset($_SESSION['user']);
        setcookie(PermanentLogin::CookieName, '', 1);

        session_regenerate_id(TRUE);
    }

    public function ifNotLoggedInRedirectTo($url)
    {
        if (!$this->isLoggedIn()) {
            go_to($url);
        }
    }

    public function ifLoggedInRedirectTo($url)
    {
        if ($this->isLoggedIn()) {
            go_to($url);
        }
    }

    /**
     * @param User $user
     */
    private function deleteUserAuthTokens(User $user)
    {
        $authTokenRepository = new AuthTokenRepository();
        $userTokens = $authTokenRepository->getAllByUser($user);
        foreach ($userTokens AS $userToken) {
            $authTokenRepository->remove($userToken);
        }
    }

    /**
     * @param $oneYear
     */
    private function updateCookieToExpireIn($oneYear)
    {
        setcookie(PermanentLogin::CookieName, $_COOKIE[PermanentLogin::CookieName], time() + $oneYear);
    }

    /**
     * @param string $cookieValue
     * @return \DSI\Entity\AuthToken
     * @throws \Exception
     */
    private function getAuthToken($cookieValue): \DSI\Entity\AuthToken
    {
        list($selector, $token) = explode(':', $cookieValue, 2);
        $authTokenRepo = new AuthTokenRepository();
        $authToken = $authTokenRepo->getBySelector($selector);

        if (!password_verify($token, $authToken->getToken()))
            throw new \Exception('Invalid cookie value');

        $authToken->setLastUse(date('Y-m-d H:i:s'));
        $authTokenRepo->save($authToken);
        return $authToken;
    }
}