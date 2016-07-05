<?php

namespace DSI\UseCase;

use DSI\Entity\User;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class UpdateUserIsAdmin
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UpdateUserIsAdmin_Data */
    private $data;

    public function __construct()
    {
        $this->data = new UpdateUserIsAdmin_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $userRepo = new UserRepository();

        $this->assertExecutorCanMakeChange();
        
        $user = $userRepo->getById($this->data()->userID);
        $user->setIsAdmin($this->data()->isAdmin);
        $userRepo->save($user);
    }

    /**
     * @return UpdateUserIsAdmin_Data
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

class UpdateUserIsAdmin_Data
{
    /** @var User */
    public $executor;

    /** @var string */
    public $isAdmin;

    /** @var int */
    public $userID;
}