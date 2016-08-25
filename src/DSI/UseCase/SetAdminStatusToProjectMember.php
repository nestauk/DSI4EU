<?php

namespace DSI\UseCase;

use DSI\Entity\Project;
use DSI\Entity\ProjectMember;
use DSI\Entity\User;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
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

    /** @var SetAdminStatusToProjectMember_Data */
    private $data;

    public function __construct()
    {
        $this->data = new SetAdminStatusToProjectMember_Data();

        $this->errorHandler = new ErrorHandler();
        $this->projectMemberRepo = new ProjectMemberRepository();
        $this->projectRepository = new ProjectRepository();
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
     * @return SetAdminStatusToProjectMember_Data
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

        $member = (new ProjectMemberRepository())->getByProjectIDAndMemberID(
            $this->data()->project->getId(),
            $this->data()->executor->getId()
        );
        if ($member->isAdmin())
            return true;

        return false;
    }

    private function makeSureUserIsMember()
    {
        if (!$this->projectMemberRepo->projectHasMember($this->data()->project->getId(), $this->data()->member->getId())) {
            $addMemberToProject = new AddMemberToProject();
            $addMemberToProject->data()->projectID = $this->data()->project->getId();
            $addMemberToProject->data()->userID = $this->data()->member->getId();
            $addMemberToProject->exec();
        }
    }

    private function setAdminFlag()
    {
        $projectMember = new ProjectMember();
        $projectMember->setMember($this->data()->member);
        $projectMember->setProject($this->data()->project);
        $projectMember->setIsAdmin($this->data()->isAdmin);
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
        if (!$this->data()->executor)
            throw new \InvalidArgumentException('No executor');
    }
}

class SetAdminStatusToProjectMember_Data
{
    /** @var User */
    public $member;

    /** @var Project */
    public $project;

    /** @var bool */
    public $isAdmin;

    /** @var User */
    public $executor;
}