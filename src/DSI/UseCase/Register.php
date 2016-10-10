<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectMember;
use DSI\Entity\User;
use DSI\Repository\ProjectEmailInvitationRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class Register
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserRepository */
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
            $this->userRepo = new UserRepository();

        $this->verifyCaptcha();
        $this->verifyEmail();
        $this->verifyPassword();
        $this->errorHandler->throwIfNotEmpty();

        $this->verifyIfEmailExists();

        $user = new User();
        $user->setEmail($this->data()->email);
        $user->setPassword($this->data()->password);
        $this->userRepo->insert($user);

        $this->user = $user;

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

    public function setUserRepo(UserRepository $userRepo)
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
            $this->errorHandler->addTaggedError('captcha', 'Please resolve the captcha');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    public function verifyEmail()
    {
        if (trim($this->data()->email) == '')
            $this->errorHandler->addTaggedError('email', 'Please type your email address');

        if (!filter_var($this->data()->email, FILTER_VALIDATE_EMAIL))
            $this->errorHandler->addTaggedError('email', "Invalid email address");
    }

    public function verifyPassword()
    {
        if (trim($this->data()->password) == '')
            $this->errorHandler->addTaggedError('password', 'Please type a password');
        if (strlen($this->data()->password) < 6)
            $this->errorHandler->addTaggedError('password', 'Please type a longer password');
    }

    public function verifyIfEmailExists()
    {
        if ($this->userRepo->emailAddressExists($this->data()->email))
            $this->errorHandler->addTaggedError('email', 'This email address is already registered');

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
        $projectEmailInvitationRepo = new ProjectEmailInvitationRepository();
        $projectInvitations = $projectEmailInvitationRepo->getByEmail(
            $this->data()->email
        );

        foreach ($projectInvitations AS $projectInvitation) {
            $projectMember = new ProjectMember();
            $projectMember->setMember($this->user);
            $projectMember->setProject($projectInvitation->getProject());
            (new ProjectMemberRepository())->insert($projectMember);

            $projectEmailInvitationRepo->remove($projectInvitation);
        }
    }
}

class Register_Data
{
    /** @var string */
    public $email,
        $password;

    /** @var bool */
    public $sendEmail;

    /** @var bool */
    public $recaptchaResponse;
}