<?php

namespace DSI\UseCase\Users;

use DSI\Entity\User;
use DSI\Repository\UserRepository;
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
        $userRepo = new UserRepository();

        $this->assertExecutorCanMakeChange();
        
        $user = $userRepo->getById($this->data()->userID);
        $user->setIsDisabled(false);
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
        if (!$this->data()->executor->isSuperAdmin()) {
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