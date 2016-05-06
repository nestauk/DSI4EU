<?php

namespace DSI\UseCase;

use DSI\Entity\User;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class GitHubRegister
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserRepository */
    private $userRepo;

    /** @var GitHubRegister_Data */
    private $data;

    /** @var User */
    private $user;

    public function __construct()
    {
        $this->data = new GitHubRegister_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        if (!$this->userRepo)
            $this->userRepo = new UserRepository();

        $this->verifyGitHubUID();
        $this->errorHandler->throwIfNotEmpty();

        $this->verifyIfGitHubUIDExists();
        $this->errorHandler->throwIfNotEmpty();

        $user = new User();
        $user->setGitHubUID($this->data()->gitHubUID);
        $user->setFirstName($this->data()->firstName);
        $user->setLastName($this->data()->lastName);
        $user->setEmail($this->data()->email);
        $this->userRepo->saveAsNew($user);

        $this->user = $user;
    }

    /**
     * @return GitHubRegister_Data
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

    public function verifyGitHubUID()
    {
        if (trim($this->data()->gitHubUID) == '')
            $this->errorHandler->addTaggedError('gitHubID', 'Invalid gitHubID');
    }

    public function verifyIfGitHubUIDExists()
    {
        if ($this->userRepo->gitHubUIDExists($this->data()->gitHubUID))
            $this->errorHandler->addTaggedError('gitHubID', 'This gitHubUID is already registered');
    }
}

class GitHubRegister_Data
{
    /** @var string */
    public $gitHubUID,
        $firstName,
        $lastName,
        $email;
}