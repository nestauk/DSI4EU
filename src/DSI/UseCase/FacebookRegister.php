<?php

namespace DSI\UseCase;

use DSI\Entity\User;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class FacebookRegister
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserRepository */
    private $userRepo;

    /** @var FacebookRegister_Data */
    private $data;

    /** @var User */
    private $user;

    public function __construct()
    {
        $this->data = new FacebookRegister_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        if (!$this->userRepo)
            $this->userRepo = new UserRepository();

        $this->verifyFacebookUID();
        $this->errorHandler->throwIfNotEmpty();

        $this->verifyIfFacebookUIDExists();
        $this->errorHandler->throwIfNotEmpty();

        $user = new User();
        $user->setFacebookUID($this->data()->facebookUID);
        $user->setFirstName($this->data()->firstName);
        $user->setLastName($this->data()->lastName);
        $user->setEmail($this->data()->email);
        $this->userRepo->saveAsNew($user);

        $this->user = $user;
    }

    /**
     * @return FacebookRegister_Data
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

    public function verifyFacebookUID()
    {
        if (trim($this->data()->facebookUID) == '')
            $this->errorHandler->addTaggedError('facebookID', 'Invalid facebookID');
    }

    public function verifyIfFacebookUIDExists()
    {
        if ($this->userRepo->facebookUIDExists($this->data()->facebookUID))
            $this->errorHandler->addTaggedError('facebookID', 'This facebookUID is already registered');
    }
}

class FacebookRegister_Data
{
    /** @var string */
    public $facebookUID,
        $firstName,
        $lastName,
        $email;
}