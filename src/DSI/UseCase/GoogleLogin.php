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

class GoogleLogin
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserRepository */
    private $userRepo;

    /** @var GoogleLogin_Data */
    private $data;

    /** @var User */
    private $user;

    public function __construct()
    {
        $this->data = new GoogleLogin_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        if (!$this->userRepo)
            $this->userRepo = new UserRepository();

        $this->verifyGoogleUID();
        $this->errorHandler->throwIfNotEmpty();

        $this->getUserByGoogleUID();
        $this->errorHandler->throwIfNotEmpty();
    }

    /**
     * @return GoogleLogin_Data
     */
    public function data()
    {
        return $this->data;
    }

    public function verifyGoogleUID()
    {
        if (trim($this->data()->googleUID) == '')
            $this->errorHandler->addTaggedError('googleID', 'Invalid google ID');
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
    private function getUserByGoogleUID()
    {
        try {
            $this->user = $this->userRepo->getByGoogleUId($this->data()->googleUID);
        } catch (NotFound $e) {
            $this->errorHandler->addTaggedError('googleID', 'Google ID not registered');
        }
    }
}

class GoogleLogin_Data
{
    /** @var string */
    public $googleUID;
}