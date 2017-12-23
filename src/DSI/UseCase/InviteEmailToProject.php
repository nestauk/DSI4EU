<?php

namespace DSI\UseCase;

use DSI\Entity\Project;
use DSI\Entity\User;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class InviteEmailToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserRepo */
    private $userRepository;

    /** @var User */
    private $user;

    /** @var Project */
    private $project;

    /** @var User */
    private $byUser;

    /** @var string */
    private $email;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->userRepository = new UserRepo();

        $this->assertValidEmailAddress();

        if ($this->userRepository->emailAddressExists($this->email)) {
            $this->user = $this->userRepository->getByEmail($this->email);
            $addMemberToProject = new AddMemberInvitationToProject();
            $addMemberToProject->setProjectID($this->project->getId());
            $addMemberToProject->setUser($this->user);
            $addMemberToProject->exec();
        } else {
            $createProjectEmailInvitation = new CreateProjectEmailInvitation();
            $createProjectEmailInvitation->setProject($this->project);
            $createProjectEmailInvitation->setByUser($this->byUser);
            $createProjectEmailInvitation->setEmail($this->email);
            $createProjectEmailInvitation->setSendEmail(true);
            $createProjectEmailInvitation->exec();
        }
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
        if (!isValidEmail($this->email)) {
            $this->errorHandler->addTaggedError('email', __('Please type a valid email address'));
            throw $this->errorHandler;
        }
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @param User $byUser
     */
    public function setByUser(User $byUser)
    {
        $this->byUser = $byUser;
    }

    /**
     * @param String $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
}