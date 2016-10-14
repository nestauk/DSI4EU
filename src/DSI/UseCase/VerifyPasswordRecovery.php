<?php

namespace DSI\UseCase;

use DSI\Entity\PasswordRecovery;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\NotFound;
use DSI\Repository\PasswordRecoveryRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class VerifyPasswordRecovery
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var PasswordRecoveryRepository */
    private $passwordRecoveryRepo;

    /** @var PasswordRecovery */
    private $passwordRecovery;

    /** @var VerifyPasswordRecovery_Data */
    private $data;

    public function __construct()
    {
        $this->data = new VerifyPasswordRecovery_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->passwordRecoveryRepo = new PasswordRecoveryRepository();

        $this->checkDataHasBeenSubmitted();
        $this->checkValidData();
        $this->checkIfEmailIsRegistered();

        $user = (new UserRepository())->getByEmail($this->data()->email);

        $this->passwordRecovery = $this->checkIfCodeIsValid($user);
        $this->checkIfCodeHasExpired($this->passwordRecovery);
        $this->checkIfCodeHasBeenUsed($this->passwordRecovery);

        // $passwordRecovery->setIsUsed(true);
        // $this->passwordRecoveryRepo->save($passwordRecovery);
    }

    /**
     * @return VerifyPasswordRecovery_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function checkDataHasBeenSubmitted()
    {
        if (!isset($this->data()->email))
            throw new NotEnoughData('email');
        if (!isset($this->data()->code))
            throw new NotEnoughData('code');
    }

    private function checkValidData()
    {
        if (!filter_var($this->data()->email, FILTER_VALIDATE_EMAIL))
            $this->errorHandler->addTaggedError('email', 'Please type a valid email address');

        if (strlen($this->data()->code) == 0)
            $this->errorHandler->addTaggedError('code', 'Please type the security code');

        $this->errorHandler->throwIfNotEmpty();
    }

    private function checkIfEmailIsRegistered()
    {
        if (!(new UserRepository())->emailAddressExists($this->data()->email)) {
            $this->errorHandler->addTaggedError('email', 'This email address is not registered');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    /**
     * @param User $user
     * @param string $code
     * @return PasswordRecovery|null
     */
    private function retrievePasswordRecovery(User $user, $code)
    {
        try {
            return $this->passwordRecoveryRepo->getByUserAndCode($user->getId(), $code);
        } catch (NotFound $e) {
        }
        return null;
    }

    private function checkIfCodeIsValid(User $user)
    {
        $passwordRecovery = $this->retrievePasswordRecovery($user, $this->data()->code);

        if (!$passwordRecovery) {
            $this->errorHandler->addTaggedError('code', 'The code is not valid or has expired');
            $this->errorHandler->throwIfNotEmpty();
        }

        return $passwordRecovery;
    }

    /**
     * @param $passwordRecovery
     * @throws ErrorHandler
     */
    private function checkIfCodeHasExpired(PasswordRecovery $passwordRecovery)
    {
        if ($passwordRecovery->isExpired()) {
            $this->errorHandler->addTaggedError('code', 'The code is not valid or has expired');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    /**
     * @param $passwordRecovery
     * @throws ErrorHandler
     */
    private function checkIfCodeHasBeenUsed(PasswordRecovery $passwordRecovery)
    {
        if ($passwordRecovery->isUsed()) {
            $this->errorHandler->addTaggedError('code', 'This code has already been used');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    /**
     * @return PasswordRecovery
     */
    public function getPasswordRecovery()
    {
        return $this->passwordRecovery;
    }
}

class VerifyPasswordRecovery_Data
{
    /** @var string */
    public $email,
        $code;
}