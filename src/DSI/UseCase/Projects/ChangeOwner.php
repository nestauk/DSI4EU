<?php

namespace DSI\UseCase\Projects;

use DSI\Entity\Project;
use DSI\Entity\User;
use DSI\Repository\ProjectMemberRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;
use DSI\UseCase\AddMemberToProject;

class ChangeOwner
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectMemberRepo */
    private $projectMemberRepository;

    /** @var ProjectRepo */
    private $projectRepository;

    /** @var UserRepo */
    private $userRepository;

    /** @var ChangeOwner_Data */
    private $data;

    public function __construct()
    {
        $this->data = new ChangeOwner_Data();

        $this->errorHandler = new ErrorHandler();
        $this->projectMemberRepository = new ProjectMemberRepo();
        $this->projectRepository = new ProjectRepoInAPC();
        $this->userRepository = new UserRepo();
    }

    public function exec()
    {
        $this->assertDataHasBeenSent();
        $this->assertExecutorCanMakeChanges();
        $this->makeSureUserIsMember();
        $this->setOwner();
    }

    /**
     * @return ChangeOwner_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    private function userCanChangeStatus(): bool
    {
        if ($this->data()->executor->isSysAdmin())
            return true;

        if ($this->data()->project->getOwnerID() == $this->data()->executor->getId())
            return true;

        return false;
    }

    private function makeSureUserIsMember()
    {
        if (!$this->projectMemberRepository->projectHasMember($this->data()->project, $this->data()->member)) {
            $addMemberToOrganisation = new AddMemberToProject();
            $addMemberToOrganisation->setProject($this->data()->project);
            $addMemberToOrganisation->setUser($this->data()->member);
            $addMemberToOrganisation->exec();
        }
    }

    private function setOwner()
    {
        $this->data()->project->setOwner($this->data()->member);
        $this->projectRepository->save($this->data()->project);
    }

    private function assertExecutorCanMakeChanges()
    {
        if (!$this->userCanChangeStatus()) {
            $this->errorHandler->addTaggedError('member', 'You cannot change member status');
            throw $this->errorHandler;
        }
    }

    private function assertDataHasBeenSent()
    {
        if (!$this->data()->executor)
            throw new \InvalidArgumentException('No executor');
    }
}

class ChangeOwner_Data
{
    /** @var User */
    public $member;

    /** @var Project */
    public $project;

    /** @var User */
    public $executor;
}