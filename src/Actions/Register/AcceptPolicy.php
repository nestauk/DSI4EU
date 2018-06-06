<?php

namespace Actions\Register;

use DSI\Entity\User;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;
use Models\EmailSubscription;
use Models\UserAccept;

class AcceptPolicy
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserRepo */
    private $userRepo;

    /** @var User */
    public $user;

    /** @var bool */
    public $emailSubscription,
        $acceptTerms;

    public function __construct()
    {
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        if (!$this->userRepo)
            $this->userRepo = new UserRepo();

        $this->verifyTerms();
        $this->errorHandler->throwIfNotEmpty();

        $this->user->setEmailSubscription($this->emailSubscription);
        $this->userRepo->save($this->user);

        $this->saveEmailSubscription();
        $this->saveUserAccept();
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

    public function verifyTerms()
    {
        if (!$this->acceptTerms) {
            $this->errorHandler->addTaggedError('accept-terms', __('You must accept the terms to continue'));
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function saveEmailSubscription()
    {
        if ($this->emailSubscription) {
            $emailSubscription = new EmailSubscription();
            $emailSubscription->{EmailSubscription::UserID} = $this->user->getId();
            $emailSubscription->{EmailSubscription::Subscribed} = true;
            $emailSubscription->save();
        }
    }

    private function saveUserAccept()
    {
        $userAccept = new UserAccept();
        $userAccept->{UserAccept::UserID} = $this->user->getId();
        $userAccept->save();
    }
}