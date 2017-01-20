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
        $this->errorHandler = new ErrorHandler();
        $this->userRepo = new UserRepository();
    }

    public function exec()
    {
        $this->assertValidEmailAndPassword();
        $this->assertEmailAndPasswordMatch();
        $this->assertUserIsEnabled();
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
            $this->errorHandler->addTaggedError('email', __('Please type your email address'));

        if (!filter_var($this->data()->email, FILTER_VALIDATE_EMAIL))
            $this->errorHandler->addTaggedError('email', __('Please type a valid email address'));
    }

    public function verifyPassword()
    {
        if (trim($this->data()->password) == '')
            $this->errorHandler->addTaggedError('password', __('Please type a password'));
    }

    public function assertEmailAndPasswordMatch()
    {
        try {
            $user = $this->userRepo->getByEmail($this->data()->email);

            if (!$user->checkPassword($this->data()->password))
                $this->errorHandler->addTaggedError('password', __('Invalid email or password'));

            $this->user = $user;
        } catch (NotFound $e) {
            $this->errorHandler->addTaggedError('email', __('The email address is not registered'));
        }

        $this->errorHandler->throwIfNotEmpty();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    private function assertUserIsEnabled()
    {
        if ($this->user->isDisabled()) {
            $this->errorHandler->addTaggedError('email', __('This user has been disabled. Please contact the website admin'));
            throw $this->errorHandler;
        }
    }

    private function assertValidEmailAndPassword()
    {
        $this->verifyEmail();
        $this->verifyPassword();
        $this->errorHandler->throwIfNotEmpty();
    }
}

class Login_Data
{
    /** @var string */
    public $email,
        $password;
}