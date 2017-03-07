<?php

namespace DSI\UseCase;

use DSI\NotEnoughData;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class UpdateUserEmailAddress
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UpdateUserEmailAddress_Data */
    private $data;

    /** @var UserRepository */
    private $userRepo;

    public function __construct()
    {
        $this->data = new UpdateUserEmailAddress_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->userRepo = new UserRepository();

        if (trim($this->data()->email) == '') {
            $this->errorHandler->addTaggedError('email', __('Please type an email address'));
            throw $this->errorHandler;
        }

        if (!isValidEmail($this->data()->email)) {
            $this->errorHandler->addTaggedError('email', __('Please type a valid email address'));
            throw $this->errorHandler;
        }

        if ($this->userRepo->emailAddressExists(
            $this->data()->email,
            [$this->data()->userID]
        )
        ) {
            $this->errorHandler->addTaggedError('email', __('The email address is already registered'));
            throw $this->errorHandler;
        }

        $this->errorHandler->throwIfNotEmpty();

        $this->saveUserDetails();
    }

    /**
     * @return UpdateUserEmailAddress_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function saveUserDetails()
    {
        $user = $this->userRepo->getById($this->data()->userID);
        $user->setEmail($this->data()->email);
        $this->userRepo->save($user);
    }
}

class UpdateUserEmailAddress_Data
{
    /** @var string */
    public $email;

    /** @var int */
    public $userID;
}