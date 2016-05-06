<?php

namespace DSI\UseCase;

use DSI\Entity\User;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class GoogleRegister
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserRepository */
    private $userRepo;

    /** @var GoogleRegister_Data */
    private $data;

    /** @var User */
    private $user;

    public function __construct()
    {
        $this->data = new GoogleRegister_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        if (!$this->userRepo)
            $this->userRepo = new UserRepository();

        $this->verifyGoogleUID();
        $this->errorHandler->throwIfNotEmpty();

        $this->verifyIfGoogleUIDExists();
        $this->errorHandler->throwIfNotEmpty();

        $user = new User();
        $user->setGoogleUID($this->data()->googleUID);
        $user->setFirstName($this->data()->firstName);
        $user->setLastName($this->data()->lastName);
        $user->setEmail($this->data()->email);
        $this->userRepo->saveAsNew($user);

        $this->user = $user;
    }

    /**
     * @return GoogleRegister_Data
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

    public function verifyGoogleUID()
    {
        if (trim($this->data()->googleUID) == '')
            $this->errorHandler->addTaggedError('googleID', 'Invalid googleID');
    }

    public function verifyIfGoogleUIDExists()
    {
        if ($this->userRepo->googleUIDExists($this->data()->googleUID))
            $this->errorHandler->addTaggedError('googleID', 'This googleUID is already registered');
    }
}

class GoogleRegister_Data
{
    /** @var string */
    public $googleUID,
        $firstName,
        $lastName,
        $email;
}