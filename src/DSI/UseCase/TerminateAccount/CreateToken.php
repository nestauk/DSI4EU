<?php

namespace DSI\UseCase\TerminateAccount;

use DSI\Entity\TerminateAccountToken;
use DSI\Entity\User;
use DSI\Repository\TerminateAccountTokenRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class CreateToken
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var User */
    private $user;

    /** @var TerminateAccountToken */
    private $token;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();

        $day = 60 * 60 * 24;

        $this->token = new TerminateAccountToken();
        $this->token->setToken($this->getRandomString(255));
        $this->token->setUser($this->user);
        $this->token->setExpire(date('Y-m-d H:i:s', time() + 1 * $day));
        (new TerminateAccountTokenRepository())->insert($this->token);
    }

    /**
     * @return TerminateAccountToken
     */
    public function getToken(): TerminateAccountToken
    {
        return $this->token;
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