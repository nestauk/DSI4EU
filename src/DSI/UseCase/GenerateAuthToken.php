<?php

namespace DSI\UseCase;

use DSI\Entity\User;
use DSI\Service\ErrorHandler;

class GenerateAuthToken
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var User */
    private $user;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();


    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}