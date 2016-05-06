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
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class TwitterLogin
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserRepository */
    private $userRepo;

    /** @var TwitterLogin_Data */
    private $data;

    /** @var User */
    private $user;

    public function __construct()
    {
        $this->data = new TwitterLogin_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        if (!$this->userRepo)
            $this->userRepo = new UserRepository();

        $this->verifyTwitterUID();
        $this->errorHandler->throwIfNotEmpty();

        $this->getUserByTwitterUID();
        $this->errorHandler->throwIfNotEmpty();
    }

    /**
     * @return TwitterLogin_Data
     */
    public function data()
    {
        return $this->data;
    }

    public function verifyTwitterUID()
    {
        if (trim($this->data()->twitterUID) == '')
            $this->errorHandler->addTaggedError('twitterID', 'Invalid twitter ID');
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
    private function getUserByTwitterUID()
    {
        try {
            $this->user = $this->userRepo->getByTwitterUId($this->data()->twitterUID);
        } catch (NotFound $e) {
            $this->errorHandler->addTaggedError('twitterID', 'Twitter ID not registered');
        }
    }
}

class TwitterLogin_Data
{
    /** @var string */
    public $twitterUID;
}