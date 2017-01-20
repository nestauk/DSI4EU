<?php

namespace DSI\UseCase;

use DSI\Entity\User;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class CreateUser
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserRepository */
    private $userRepo;

    /** @var Register_Data */
    private $data;

    /** @var User */
    private $user;

    public function __construct()
    {
        $this->data = new Register_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->userRepo = new UserRepository();

        $this->verifyEmail();
        $this->errorHandler->throwIfNotEmpty();

        $this->verifyIfEmailExists();

        $user = new User();
        $user->setFirstName($this->data()->firstName);
        $user->setLastName($this->data()->lastName);
        $user->setEmail($this->data()->email);
        $user->setJobTitle($this->data()->jobTitle);
        $user->setCompany($this->data()->organisation);
        $this->userRepo->insert($user);

        $this->user = $user;
    }

    /**
     * @return Register_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function verifyEmail()
    {
        if (trim($this->data()->email) == '')
            $this->errorHandler->addTaggedError('email', __('Please type your email address'));

        if (!filter_var($this->data()->email, FILTER_VALIDATE_EMAIL))
            $this->errorHandler->addTaggedError('email', __('Please type a valid email address'));
    }

    public function verifyIfEmailExists()
    {
        if ($this->userRepo->emailAddressExists($this->data()->email))
            $this->errorHandler->addTaggedError('email', __('The email address is already registered'));

        $this->errorHandler->throwIfNotEmpty();
    }
}

class Register_Data
{
    /** @var string */
    public $firstName,
        $lastName,
        $email,
        $jobTitle,
        $organisation;
}