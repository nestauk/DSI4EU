<?php

namespace DSI\UseCase;

use DSI\Entity\PasswordRecovery;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\Repository\PasswordRecoveryRepository;
use DSI\Repository\UserRepository;
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

        if (!isset($this->data()->email))
            throw new NotEnoughData('email');

        $userRepository = new UserRepository();

        if (!$userRepository->emailAddressExists($this->data()->email)) {
            $this->errorHandler->addTaggedError('email', 'This email address is not registered');
            throw $this->errorHandler;
        }

        $user = $userRepository->getByEmail($this->data()->email);

        $passwordRecovery = new PasswordRecovery();
        $passwordRecovery->setCode($this->generateRandomCode());
        $passwordRecovery->setUser($user);
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
    /** @var String */
    public $email;
}