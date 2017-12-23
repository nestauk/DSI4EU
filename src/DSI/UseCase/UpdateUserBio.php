<?php

namespace DSI\UseCase;

use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class UpdateUserBio
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UpdateUserBio_Data */
    private $data;

    public function __construct()
    {
        $this->data = new UpdateUserBio_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $userRepo = new UserRepo();

        $user = $userRepo->getById($this->data()->userID);
        $user->setBio($this->data()->bio);
        $userRepo->save($user);
    }

    /**
     * @return UpdateUserBio_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class UpdateUserBio_Data
{
    /** @var string */
    public $bio;

    /** @var int */
    public $userID;
}