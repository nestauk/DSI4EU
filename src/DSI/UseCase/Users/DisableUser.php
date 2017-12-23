<?php

namespace DSI\UseCase\Users;

use DSI\Entity\User;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class DisableUser
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var DisableUser_Data */
    private $data;

    public function __construct()
    {
        $this->data = new DisableUser_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $userRepo = new UserRepo();

        $this->assertExecutorCanMakeChange();

        $user = $userRepo->getById($this->data()->userID);
        $user->setDisabled(true);
        $userRepo->save($user);
    }

    /**
     * @return DisableUser_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function assertExecutorCanMakeChange()
    {
        if (!$this->data()->executor->isSysAdmin()) {
            $this->errorHandler->addTaggedError('executor', 'You are not allowed to edit this user');
            throw $this->errorHandler;
        }
    }
}

class DisableUser_Data
{
    /** @var User */
    public $executor;

    /** @var int */
    public $userID;
}