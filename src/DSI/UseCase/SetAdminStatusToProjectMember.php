<?php

namespace DSI\UseCase;

use DSI\Entity\Project;
use DSI\Entity\ProjectMember;
use DSI\Entity\User;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;
use Guzzle\Common\Exception\InvalidArgumentException;

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
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectMemberRepo = new ProjectMemberRepository();
        $this->projectRepository = new ProjectRepository();
        $this->userRepository = new UserRepository();

        if (!$this->data()->executor)
            throw new \InvalidArgumentException('No executor');

        if (!$this->userCanChangeStatus()) {
            $this->errorHandler->addTaggedError('member', 'You cannot change member status');
            throw $this->errorHandler;
        }

        if (!$this->projectMemberRepo->projectHasMember($this->data()->project->getId(), $this->data()->member->getId())) {
            $this->errorHandler->addTaggedError('member', 'This user is not a member of the project');
            $this->errorHandler->throwIfNotEmpty();
        }

        $projectMember = new ProjectMember();
        $projectMember->setMember($this->data()->member);
        $projectMember->setProject($this->data()->project);
        $projectMember->setIsAdmin($this->data()->isAdmin);
        $this->projectMemberRepo->save($projectMember);
    }

    /**
     * @return SetAdminStatusToProjectMember_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function userCanChangeStatus()
    {
        if ($this->data()->project->getOwner()->getId() == $this->data()->executor->getId())
            return true;

        $member = (new ProjectMemberRepository())->getByProjectIDAndMemberID(
            $this->data()->project->getId(),
            $this->data()->executor->getId()
        );
        if ($member->isAdmin())
            return true;

        return false;
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