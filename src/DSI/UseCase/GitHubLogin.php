<?php
/**
 * Created by PhpStorm.
 * User: apandele
 * Date: 25/04/2016
 * Time: 15:45
 */

namespace DSI\UseCase;


use DSI\Entity\User;
use DSI\NotFound;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class GitHubLogin
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserRepo */
    private $userRepo;

    /** @var GitHubLogin_Data */
    private $data;

    /** @var User */
    private $user;

    public function __construct()
    {
        $this->data = new GitHubLogin_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        if (!$this->userRepo)
            $this->userRepo = new UserRepo();

        $this->verifyGitHubUID();
        $this->errorHandler->throwIfNotEmpty();

        $this->getUserByGitHubUID();
        $this->errorHandler->throwIfNotEmpty();
    }

    /**
     * @return GitHubLogin_Data
     */
    public function data()
    {
        return $this->data;
    }

    public function verifyGitHubUID()
    {
        if (trim($this->data()->gitHubUID) == '')
            $this->errorHandler->addTaggedError('gitHubID', 'Invalid gitHub ID');
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return User
     */
    private function getUserByGitHubUID()
    {
        try {
            $this->user = $this->userRepo->getByGitHubUId($this->data()->gitHubUID);
        } catch (NotFound $e) {
            $this->errorHandler->addTaggedError('gitHubID', 'GitHub ID not registered');
        }
    }
}

class GitHubLogin_Data
{
    /** @var string */
    public $gitHubUID;
}