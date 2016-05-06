<?php

namespace DSI\UseCase;

use DSI\NotEnoughData;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class UpdateUserPassword
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UpdateUserPassword_Data */
    private $data;

    public function __construct()
    {
        $this->data = new UpdateUserPassword_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $userRepo = new UserRepository();

        $this->checkIfAllInfoHaveBeenSubmitted();

        if(strlen($this->data()->password) < 8)
            $this->errorHandler->addTaggedError('password', 'Password must have at least 8 characters');

        if($this->data()->password != $this->data()->retypePassword)
            $this->errorHandler->addTaggedError('retypePassword', 'Passwords do not match');

        $this->errorHandler->throwIfNotEmpty();

        $this->updateUserPassword($userRepo);
    }

    /**
     * @return UpdateUserPassword_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function checkIfAllInfoHaveBeenSubmitted()
    {
        if (!isset($this->data()->userID))
            throw new NotEnoughData('userID');
        if (!isset($this->data()->password))
            throw new NotEnoughData('password');
        if (!isset($this->data()->retypePassword))
            throw new NotEnoughData('retypePassword');
    }

    /**
     * @param $userRepo
     */
    private function updateUserPassword($userRepo)
    {
        $user = $userRepo->getById($this->data()->userID);
        $user->setPassword($this->data()->password);
        $userRepo->save($user);
    }
}

class UpdateUserPassword_Data
{
    /** @var string */
    public $password,
        $retypePassword;

    /** @var int */
    public $userID;
}