<?php

namespace DSI\UseCase;

use DSI\Entity\Project;
use DSI\Entity\ProjectEmailInvitation;
use DSI\Entity\User;
use DSI\Repository\ProjectEmailInvitationRepo;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class CancelInvitationEmailToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserRepo */
    private $userRepository;

    /** @var User */
    private $user;

    /** @var Project */
    private $project;

    /** @var string */
    private $email;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->userRepository = new UserRepo();

        $this->assertValidEmailAddress();
        $this->removeProjectEmailInvitation();
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
     * @param String $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    private function removeProjectEmailInvitation()
    {
        $projectEmailInvitation = new ProjectEmailInvitation();
        $projectEmailInvitation->setProject($this->project);
        $projectEmailInvitation->setEmail($this->email);
        (new ProjectEmailInvitationRepo())->remove($projectEmailInvitation);
    }
}