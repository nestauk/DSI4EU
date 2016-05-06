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

class Login
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserRepository */
    private $userRepo;

    /** @var Login_Data */
    private $data;

    /** @var User */
    private $user;

    public function __construct()
    {
        $this->data = new Login_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        if (!$this->userRepo)
            $this->userRepo = new UserRepository();

        $this->verifyEmail();
        $this->verifyPassword();
        $this->errorHandler->throwIfNotEmpty();

        $this->verifyIfEmailAndPasswordMatch();
        $this->errorHandler->throwIfNotEmpty();
    }

    /**
     * @return Login_Data
     */
    public function data()
    {
        return $this->data;
    }

    public function verifyEmail()
    {
        if (trim($this->data()->email) == '')
            $this->errorHandler->addTaggedError('email', 'Please type an email address');

        if (!filter_var($this->data()->email, FILTER_VALIDATE_EMAIL))
            $this->errorHandler->addTaggedError('email', "Invalid email address");
    }

    public function verifyPassword()
    {
        if (trim($this->data()->password) == '')
            $this->errorHandler->addTaggedError('password', 'Please type a password');
    }

    public function verifyIfEmailAndPasswordMatch()
    {
        try {
            $user = $this->userRepo->getByEmail($this->data()->email);

            if (!$user->checkPassword($this->data()->password))
                $this->errorHandler->addTaggedError('password', 'Invalid email or password');

            $this->user = $user;
        } catch (NotFound $e) {
            $this->errorHandler->addTaggedError('email', 'This email address is not registered');
        }
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}

class Login_Data
{
    /** @var string */
    public $email,
        $password;
}