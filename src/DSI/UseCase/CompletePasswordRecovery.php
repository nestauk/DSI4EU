<?php

namespace DSI\UseCase;

use DSI\Entity\PasswordRecovery;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\NotFound;
use DSI\Repository\PasswordRecoveryRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class CompletePasswordRecovery
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var PasswordRecoveryRepository */
    private $passwordRecoveryRepo;

    /** @var PasswordRecovery */
    private $passwordRecovery;

    /** @var CompletePasswordRecovery_Data */
    private $data;

    /** @var User */
    private $user;

    public function __construct()
    {
        $this->data = new CompletePasswordRecovery_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->passwordRecoveryRepo = new PasswordRecoveryRepository();

        $this->checkDataHasBeenSubmitted();
        $this->checkValidData();
        $this->checkIfEmailIsRegistered();

        $verifyPasswordRecovery = new VerifyPasswordRecovery();
        $verifyPasswordRecovery->data()->email = $this->data()->email;
        $verifyPasswordRecovery->data()->code = $this->data()->code;
        $verifyPasswordRecovery->exec();

        $passwordRecovery = $verifyPasswordRecovery->getPasswordRecovery();
        $this->user = $passwordRecovery->getUser();

        $this->updateUserPassword($this->user);
        $this->markPasswordRecoveryCodeAsUsed($passwordRecovery);
    }

    /**
     * @return CompletePasswordRecovery_Data
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
        if (!isset($this->data()->password))
            throw new NotEnoughData('password');
        if (!isset($this->data()->retypePassword))
            throw new NotEnoughData('retypePassword');
    }

    private function checkValidData()
    {
        if (!filter_var($this->data()->email, FILTER_VALIDATE_EMAIL))
            $this->errorHandler->addTaggedError('email', 'Please type a valid email address');

        if (strlen($this->data()->code) == 0)
            $this->errorHandler->addTaggedError('code', 'Please type the security code');

        if (strlen($this->data()->password) < 8)
            $this->errorHandler->addTaggedError('password', 'Password must have at least 8 characters');

        if ($this->data()->password != $this->data()->retypePassword)
            $this->errorHandler->addTaggedError('retypePassword', 'Passwords do not match');

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
    private function getPasswordRecovery(User $user, $code)
    {
        try {
            return $this->passwordRecoveryRepo->getByUserAndCode($user->getId(), $code);
        } catch (NotFound $e) {
        }
        return null;
    }

    private function checkIfCodeIsValid(User $user)
    {
        $passwordRecovery = $this->getPasswordRecovery($user, $this->data()->code);

        if (!$passwordRecovery) {
            $this->errorHandler->addTaggedError('code', 'The code is not valid');
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
            $this->errorHandler->addTaggedError('code', 'This code has expired');
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
     * @param User $user
     */
    private function updateUserPassword(User $user)
    {
        $updateUserPassword = new UpdateUserPassword();
        $updateUserPassword->data()->userID = $user->getId();
        $updateUserPassword->data()->password = $this->data()->password;
        $updateUserPassword->data()->retypePassword = $this->data()->retypePassword;
        $updateUserPassword->exec();
    }

    /**
     * @param PasswordRecovery $passwordRecovery
     */
    private function markPasswordRecoveryCodeAsUsed(PasswordRecovery $passwordRecovery)
    {
        $passwordRecovery->setIsUsed(true);
        $this->passwordRecoveryRepo->save($passwordRecovery);
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}

class CompletePasswordRecovery_Data
{
    /** @var string */
    public $email,
        $code,
        $password,
        $retypePassword;
}