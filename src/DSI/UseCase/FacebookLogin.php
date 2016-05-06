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

class FacebookLogin
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserRepository */
    private $userRepo;

    /** @var FacebookLogin_Data */
    private $data;

    /** @var User */
    private $user;

    public function __construct()
    {
        $this->data = new FacebookLogin_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        if (!$this->userRepo)
            $this->userRepo = new UserRepository();

        $this->verifyFacebookUID();
        $this->errorHandler->throwIfNotEmpty();

        $this->getUserByFacebookUID();
        $this->errorHandler->throwIfNotEmpty();
    }

    /**
     * @return FacebookLogin_Data
     */
    public function data()
    {
        return $this->data;
    }

    public function verifyFacebookUID()
    {
        if (trim($this->data()->facebookUID) == '')
            $this->errorHandler->addTaggedError('facebookID', 'Invalid Facebook ID');
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
    private function getUserByFacebookUID()
    {
        try {
            $this->user = $this->userRepo->getByFacebookUId($this->data()->facebookUID);
        } catch (NotFound $e) {
            $this->errorHandler->addTaggedError('facebookID', 'Facebook ID not registered');
        }
    }
}

class FacebookLogin_Data
{
    /** @var string */
    public $facebookUID;
}