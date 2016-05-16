<?php
/**
 * Created by PhpStorm.
 * User: apandele
 * Date: 25/04/2016
 * Time: 16:05
 */

namespace DSI\Service;

use DSI\Entity\User;

class Auth
{
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

    public function saveUserInSession(User $user)
    {
        $_SESSION['user']['userID'] = $user->getId();
        $_SESSION['user']['firstName'] = $user->getFirstName();
        $_SESSION['user']['lastName'] = $user->getLastName();
    }

    public function removeUserFromSession(int $userID)
    {
        unset($_SESSION['user']);
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
}