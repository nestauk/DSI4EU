<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectMember;
use DSI\Entity\User;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class AddEmailToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectMemberRepository */
    private $projectMemberRepo;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var User */
    private $user;

    /** @var AddEmailToProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddEmailToProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectMemberRepo = new ProjectMemberRepository();
        $this->projectRepository = new ProjectRepository();
        $this->userRepository = new UserRepository();

        $this->assertValidEmailAddress();

        if ($this->userRepository->emailAddressExists($this->data()->email)) {
            $this->user = $this->userRepository->getByEmail($this->data()->email);
            $addMemberToProject = new AddMemberInvitationToProject();
            $addMemberToProject->setProjectID($this->data()->projectID);
            $addMemberToProject->setUser($this->user);
            $addMemberToProject->exec();
        } else {
            $createProjectEmailInvitation = new CreateProjectEmailInvitation();
            $createProjectEmailInvitation->data()->projectID = $this->data()->projectID;
            $createProjectEmailInvitation->data()->byUserID = $this->data()->byUserID;
            $createProjectEmailInvitation->data()->email = $this->data()->email;
            $createProjectEmailInvitation->data()->sendEmail = true;
            $createProjectEmailInvitation->exec();
        }
    }

    /**
     * @return AddEmailToProject_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    private function assertValidEmailAddress()
    {
        if (!isValidEmail($this->data()->email)) {
            $this->errorHandler->addTaggedError('email', __('Please type a valid email address'));
            throw $this->errorHandler;
        }
    }
}

class AddEmailToProject_Data
{
    /** @var int */
    public $projectID;

    /** @var int */
    public $byUserID;

    /** @var string */
    public $email;
}