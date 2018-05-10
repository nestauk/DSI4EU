<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectMember;
use DSI\Entity\User;
use DSI\Repository\ProjectEmailInvitationRepo;
use DSI\Repository\ProjectMemberRepo;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;
use Models\EmailSubscription;

class Register
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserRepo */
    private $userRepo;

    /** @var Register_Data */
    private $data;

    /** @var User */
    private $user;

    public function __construct()
    {
        $this->data = new Register_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        if (!$this->userRepo)
            $this->userRepo = new UserRepo();

        $this->verifyCaptcha();
        $this->verifyTerms();
        $this->verifyEmail();
        $this->verifyPassword();
        $this->errorHandler->throwIfNotEmpty();

        $this->verifyIfEmailExists();

        $user = new User();
        $user->setEmail($this->data()->email);
        $user->setPassword($this->data()->password);
        $user->setEmailSubscription($this->data()->emailSubscription);
        $this->userRepo->insert($user);
        $this->user = $user;

        $this->saveEmailSubscription();
        $this->sendWelcomeEmail();
        $this->checkProjectInvitations();
    }

    /**
     * @return Register_Data
     */
    public function data()
    {
        return $this->data;
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

    public function verifyCaptcha()
    {
        if (!$this->data()->recaptchaResponse) {
            $this->errorHandler->addTaggedError('captcha', __('Please resolve the captcha'));
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    public function verifyTerms()
    {
        if (!$this->data()->acceptTerms) {
            $this->errorHandler->addTaggedError('accept-terms', __('You must accept the terms before creating your account'));
            $this->errorHandler->throwIfNotEmpty();
        }
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
        if (strlen($this->data()->password) < 8)
            $this->errorHandler->addTaggedError('password', __('Password must have at least 8 characters'));
    }

    public function verifyIfEmailExists()
    {
        if ($this->userRepo->emailAddressExists($this->data()->email))
            $this->errorHandler->addTaggedError('email', __('The email address is already registered'));

        $this->errorHandler->throwIfNotEmpty();
    }

    private function sendWelcomeEmail()
    {
        if ($this->data()->sendEmail) {
            $sendEmail = new SendWelcomeEmailAfterRegistration();
            $sendEmail->data()->emailAddress = $this->data()->email;
            $sendEmail->exec();
        }
    }

    private function checkProjectInvitations()
    {
        $projectEmailInvitationRepo = new ProjectEmailInvitationRepo();
        $projectInvitations = $projectEmailInvitationRepo->getByEmail(
            $this->data()->email
        );

        foreach ($projectInvitations AS $projectInvitation) {
            $projectMember = new ProjectMember();
            $projectMember->setMember($this->user);
            $projectMember->setProject($projectInvitation->getProject());
            (new ProjectMemberRepo())->insert($projectMember);

            $projectEmailInvitationRepo->remove($projectInvitation);
        }
    }

    private function saveEmailSubscription()
    {
        if ($this->data()->emailSubscription) {
            $emailSubscription = new EmailSubscription();
            $emailSubscription->{EmailSubscription::UserID} = $this->user->getId();
            $emailSubscription->{EmailSubscription::Subscribed} = true;
            $emailSubscription->save();
        }
    }
}

class Register_Data
{
    /** @var string */
    public $email,
        $password;

    /** @var bool */
    public $sendEmail,
        $emailSubscription,
        $acceptTerms;

    /** @var bool */
    public $recaptchaResponse;
}