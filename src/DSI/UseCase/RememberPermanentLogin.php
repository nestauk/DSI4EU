<?php

namespace DSI\UseCase;

use DSI\DuplicateEntry;
use DSI\Entity\AuthToken;
use DSI\Entity\User;
use DSI\Repository\AuthTokenRepo;
use DSI\Service\ErrorHandler;
use DSI\Service\PermanentLogin;

class RememberPermanentLogin
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var User */
    private $user;

    public function __construct()
    {
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();

        do {
            $token = $this->getRandomString(32);
            $selector = $this->getRandomString(32);

            try {
                $auth_token = new AuthToken();
                $auth_token->setSelector($selector);
                $auth_token->setToken(password_hash($token, PASSWORD_DEFAULT));
                $auth_token->setUser($this->user);
                (new AuthTokenRepo())->insert($auth_token);
            } catch (DuplicateEntry $e) {
                $auth_token = null;
            }
        } while ($auth_token === null);

        $cookieValue = "{$selector}:{$token}";
        setcookie(PermanentLogin::CookieName, $cookieValue, time() + PermanentLogin::ExpireTime);
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param int $length
     * @return string
     */
    private function getRandomString($length = 32): string
    {
        $charPool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $return = '';
        for ($i = 0; $i < $length; $i++) {
            $return .= $charPool[random_int(0, strlen($charPool) - 1)];
        }
        return $return;
    }
}