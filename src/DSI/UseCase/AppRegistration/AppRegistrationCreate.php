<?php

namespace DSI\UseCase\AppRegistration;

use DSI\Entity\AppRegistration;
use DSI\Entity\User;
use DSI\Repository\AppRegistrationRepository;
use DSI\Service\ErrorHandler;

class AppRegistrationCreate
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var AppRegistrationRepository */
    private $appRegistrationRepository;

    /** @var AppRegistrationCreate_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AppRegistrationCreate_Data();
        $this->errorHandler = new ErrorHandler();
        $this->appRegistrationRepository = new AppRegistrationRepository();
    }

    public function exec()
    {
        $this->assertUsersHaveBeenSent();
        $this->createAppRegistration();
    }

    /**
     * @return AppRegistrationCreate_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function assertUsersHaveBeenSent()
    {
        if ($this->data()->loggedInUser->getId() == 0)
            throw new \InvalidArgumentException('Logged in user not sent');
        if ($this->data()->registeredUser->getId() == 0)
            throw new \InvalidArgumentException('Registered user not sent');
    }

    private function createAppRegistration()
    {
        $appRegistration = new AppRegistration();
        $appRegistration->setLoggedInUser($this->data()->loggedInUser);
        $appRegistration->setRegisteredUser($this->data()->registeredUser);
        $this->appRegistrationRepository->insert($appRegistration);
    }
}

class AppRegistrationCreate_Data
{
    /** @var User */
    public $loggedInUser,
        $registeredUser;
}