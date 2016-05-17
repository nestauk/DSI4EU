<?php

namespace DSI\UseCase;

use DSI\Entity\PasswordRecovery;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\NotFound;
use DSI\Repository\PasswordRecoveryRepository;
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

        if (!isset($this->data()->user))
            throw new NotEnoughData('user');
        if (!isset($this->data()->code))
            throw new NotEnoughData('code');

        if ($this->data()->user->getId() <= 0)
            $this->errorHandler->addTaggedError('user', 'Invalid user ID');
        if (strlen($this->data()->code) == 0)
            $this->errorHandler->addTaggedError('code', 'Please type the security code');

        $this->errorHandler->throwIfNotEmpty();

        $passwordRecovery = null;
        try {
            $passwordRecovery = $this->passwordRecoveryRepo->getByUserAndCode($this->data()->user->getId(), $this->data()->code);
        } catch (NotFound $e) {
        }

        if (!$passwordRecovery) {
            $this->errorHandler->addTaggedError('code', 'The code is not valid');
            $this->errorHandler->throwIfNotEmpty();
        }

        if ($passwordRecovery->isExpired()) {
            $this->errorHandler->addTaggedError('code', 'This code has expired');
            $this->errorHandler->throwIfNotEmpty();
        }

        if ($passwordRecovery->isUsed()) {
            $this->errorHandler->addTaggedError('code', 'This code has already been used');
            $this->errorHandler->throwIfNotEmpty();
        }

        $passwordRecovery->setIsUsed(true);
        $this->passwordRecoveryRepo->save($passwordRecovery);
    }

    /**
     * @return VerifyPasswordRecovery_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class VerifyPasswordRecovery_Data
{
    /** @var User */
    public $user;

    /** @var string */
    public $code;
}