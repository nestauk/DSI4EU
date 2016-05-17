<?php

namespace DSI\UseCase;

use DSI\Entity\PasswordRecovery;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\Repository\PasswordRecoveryRepository;
use DSI\Service\ErrorHandler;

class CreatePasswordRecovery
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var PasswordRecoveryRepository */
    private $passwordRecoveryRepo;

    /** @var PasswordRecovery */
    private $passwordRecovery;

    /** @var CreatePasswordRecovery_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CreatePasswordRecovery_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->passwordRecoveryRepo = new PasswordRecoveryRepository();

        if (!isset($this->data()->user))
            throw new NotEnoughData('user');

        if ($this->data()->user->getId() <= 0) {
            $this->errorHandler->addTaggedError('user', 'Invalid user ID');
            throw $this->errorHandler;
        }

        $passwordRecovery = new PasswordRecovery();
        $passwordRecovery->setCode($this->generateRandomCode());
        $passwordRecovery->setUser($this->data()->user);
        $this->passwordRecoveryRepo->insert($passwordRecovery);

        $this->passwordRecovery = $passwordRecovery;
    }

    /**
     * @return CreatePasswordRecovery_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return PasswordRecovery
     */
    public function getPasswordRecovery()
    {
        return $this->passwordRecovery;
    }

    /**
     * @return string
     */
    private function generateRandomCode()
    {
        $length = 5;

        $randomString = '';
        for ($i = 0; $i < $length; $i++)
            $randomString .= random_int(0, 9);

        return $randomString;
    }
}

class CreatePasswordRecovery_Data
{
    /** @var User */
    public $user;
}