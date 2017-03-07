<?php

namespace DSI\UseCase;

use DSI\Entity\Project;
use DSI\Entity\ProjectMember;
use DSI\Entity\User;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectRepositoryInAPC;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class SetAdminStatusToProjectMember
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
    private $member,
        $executor;

    /** @var Project */
    private $project;

    /** @var bool */
    private $isAdmin;

    public function __construct()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectMemberRepo = new ProjectMemberRepository();
        $this->projectRepository = new ProjectRepositoryInAPC();
        $this->userRepository = new UserRepository();
    }

    public function exec()
    {
        $this->assertDataHasBeenSent();
        $this->assertExecutorCanMakeChanges();
        $this->makeSureUserIsMember();
        $this->setAdminFlag();
    }

    /**
     * @return bool
     */
    private function userCanChangeStatus(): bool
    {
        if ($this->executor->isSysAdmin())
            return true;

        if ($this->project->getOwnerID() == $this->executor->getId())
            return true;

        return false;
    }

    private function makeSureUserIsMember()
    {
        if (!$this->projectMemberRepo->projectHasMember($this->project, $this->member)) {
            $addMemberToProject = new AddMemberToProject();
            $addMemberToProject->setProject($this->project);
            $addMemberToProject->setUser($this->member);
            $addMemberToProject->exec();
        }
    }

    private function setAdminFlag()
    {
        $projectMember = new ProjectMember();
        $projectMember->setMember($this->member);
        $projectMember->setProject($this->project);
        $projectMember->setIsAdmin($this->isAdmin);
        $this->projectMemberRepo->save($projectMember);
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
        if (!$this->executor)
            throw new \InvalidArgumentException('No executor');
    }

    /**
     * @param User $user
     */
    public function setMember(User $user)
    {
        $this->member = $user;
    }

    /**
     * @param int $userID
     */
    public function setMemberId($userID)
    {
        $this->member = $this->userRepository->getById($userID);
    }

    /**
     * @param User $executor
     */
    public function setExecutor(User $executor)
    {
        $this->executor = $executor;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = (bool)$isAdmin;
    }
}