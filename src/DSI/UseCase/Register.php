<?php

namespace DSI\UseCase;

use DSI\Entity\User;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class Register
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
        if (!$this->userRepo)
            $this->userRepo = new UserRepository();

        $this->verifyEmail();
        $this->verifyPassword();

        $this->errorHandler->throwIfNotEmpty();

        $this->verifyIfEmailExists();

        $this->errorHandler->throwIfNotEmpty();

        $user = new User();
        $user->setEmail($this->data()->email);
        $user->setPassword($this->data()->password);
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

    public function setUserRepo(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
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
            $this->errorHandler->addTaggedError('email', 'Please type your email address');

        if (!filter_var($this->data()->email, FILTER_VALIDATE_EMAIL))
            $this->errorHandler->addTaggedError('email', "Invalid email address");
    }

    public function verifyPassword()
    {
        if (trim($this->data()->password) == '')
            $this->errorHandler->addTaggedError('password', 'Please type a password');
    }

    public function verifyIfEmailExists()
    {
        if ($this->userRepo->emailExists($this->data()->email))
            $this->errorHandler->addTaggedError('email', 'This email address is already registered');
    }
}

class Register_Data
{
    /** @var string */
    public $email,
        $password;
}