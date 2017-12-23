<?php

namespace DSI\UseCase;

use DSI\Entity\User;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class TwitterRegister
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserRepo */
    private $userRepo;

    /** @var TwitterRegister_Data */
    private $data;

    /** @var User */
    private $user;

    public function __construct()
    {
        $this->data = new TwitterRegister_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        if (!$this->userRepo)
            $this->userRepo = new UserRepo();

        $this->verifyTwitterUID();
        $this->errorHandler->throwIfNotEmpty();

        $this->verifyIfTwitterUIDExists();
        $this->errorHandler->throwIfNotEmpty();

        $user = new User();
        $user->setTwitterUID((string)$this->data()->twitterUID);
        if ($this->data()->firstName)
            $user->setFirstName($this->data()->firstName);
        if ($this->data()->lastName)
            $user->setLastName($this->data()->lastName);
        if ($this->data()->email)
            $user->setEmail($this->data()->email);
        $this->userRepo->insert($user);

        $this->user = $user;
    }

    /**
     * @return TwitterRegister_Data
     */
    public function data()
    {
        return $this->data;
    }

    public function setUserRepo(UserRepo $userRepo)
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

    public function verifyTwitterUID()
    {
        if (trim($this->data()->twitterUID) == '')
            $this->errorHandler->addTaggedError('twitterID', 'Invalid twitterID');
    }

    public function verifyIfTwitterUIDExists()
    {
        if ($this->userRepo->twitterUIDExists($this->data()->twitterUID))
            $this->errorHandler->addTaggedError('twitterID', 'This twitterUID is already registered');
    }
}

class TwitterRegister_Data
{
    /** @var string */
    public $twitterUID,
        $firstName,
        $lastName,
        $email;
}