<?php

namespace DSI\UseCase;

use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class UpdateUserBasicDetails
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UpdateUserBasicDetails_Data */
    private $data;

    /** @var UserRepository */
    private $userRepo;

    public function __construct()
    {
        $this->data = new UpdateUserBasicDetails_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->userRepo = new UserRepository();

        if (isset($this->data()->firstName)) {
            if ($this->data()->firstName == '') {
                $this->errorHandler->addTaggedError('firstName', 'Please type the first name');
            }
        }
        if (isset($this->data()->lastName)) {
            if ($this->data()->lastName == '') {
                $this->errorHandler->addTaggedError('lastName', 'Please type the last name');
            }
        }

        $this->errorHandler->throwIfNotEmpty();

        $this->saveUserDetails();
    }

    /**
     * @return UpdateUserBasicDetails_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function saveUserDetails()
    {
        $user = $this->userRepo->getById($this->data()->userID);
        $user->setFirstName($this->data()->firstName);
        $user->setLastName($this->data()->lastName);
        $user->setShowEmail($this->data()->showEmail);
        $user->setCityName($this->data()->cityName);
        $user->setCountryName($this->data()->countryName);
        $user->setJobTitle($this->data()->jobTitle);
        $user->setCompany($this->data()->company);
        $user->setBio($this->data()->bio);
        $this->userRepo->save($user);
    }
}

class UpdateUserBasicDetails_Data
{
    /** @var string */
    public $firstName,
        $lastName,
        $showEmail,
        $cityName,
        $countryName,
        $jobTitle,
        $company,
        $bio;

    /** @var int */
    public $userID;
}