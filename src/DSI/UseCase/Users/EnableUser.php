<?php

namespace DSI\UseCase\Users;

use DSI\Entity\User;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class EnableUser
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var EnableUser_Data */
    private $data;

    public function __construct()
    {
        $this->data = new EnableUser_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $userRepo = new UserRepo();

        $this->assertExecutorCanMakeChange();
        
        $user = $userRepo->getById($this->data()->userID);
        $user->setDisabled(false);
        $userRepo->save($user);
    }

    /**
     * @return EnableUser_Data
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

class EnableUser_Data
{
    /** @var User */
    public $executor;

    /** @var int */
    public $userID;
}