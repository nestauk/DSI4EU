<?php

namespace DSI\UseCase;

use DSI\Entity\Project;
use DSI\Entity\ProjectMember;
use DSI\Entity\ProjectMemberInvitation;
use DSI\Entity\User;
use DSI\Repository\ProjectMemberRepo;
use DSI\Repository\ProjectMemberInvitationRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class AcceptMemberInvitationToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectMemberInvitationRepo */
    private $projectMemberInvitationRepo;

    /** @var ProjectMemberRepo */
    private $projectMemberRepo;

    /** @var ProjectRepo */
    private $projectRepository;

    /** @var UserRepo */
    private $userRepository;

    /** @var ApproveMemberInvitationToProject_Data */
    private $data;

    /** @var User */
    private $user;

    /** @var Project */
    private $project;

    public function __construct()
    {
        $this->data = new ApproveMemberInvitationToProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectMemberInvitationRepo = new ProjectMemberInvitationRepo();
        $this->projectMemberRepo = new ProjectMemberRepo();
        $this->projectRepository = new ProjectRepoInAPC();
        $this->userRepository = new UserRepo();

        $this->assertUserIsSet();
        $this->assertProjectIsSet();

        $this->user = $this->userRepository->getById($this->data()->userID);
        $this->project = $this->projectRepository->getById($this->data()->projectID);

        $this->assertExecutorIsSet();
        $this->assertExecutorCanExecute();
        $this->assertUserHasBeenInvited();

        $projectMemberInvitation = new ProjectMemberInvitation();
        $projectMemberInvitation->setMember($this->user);
        $projectMemberInvitation->setProject($this->project);
        $this->projectMemberInvitationRepo->remove($projectMemberInvitation);

        $projectMember = new ProjectMember();
        $projectMember->setMember($this->user);
        $projectMember->setProject($this->project);
        $this->projectMemberRepo->insert($projectMember);
    }

    /**
     * @return ApproveMemberInvitationToProject_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function assertUserHasBeenInvited()
    {
        if (!$this->projectMemberInvitationRepo->userHasBeenInvitedToProject(
            $this->user, $this->project
        )) {
            $this->errorHandler->addTaggedError('member', 'This user was not invited to join the project');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function assertExecutorIsSet()
    {
        if (!$this->data()->executor OR $this->data()->executor->getId() < 1)
            throw new \InvalidArgumentException('executor');
    }

    private function assertUserIsSet()
    {
        if ($this->data()->userID < 1)
            throw new \InvalidArgumentException('user');
    }

    private function assertProjectIsSet()
    {
        if ($this->data()->projectID < 1)
            throw new \InvalidArgumentException('project');
    }

    private function assertExecutorCanExecute()
    {
        if ($this->data()->executor->getId() != $this->data()->userID) {
            $this->errorHandler->addTaggedError('executor', 'Only the invited person can approve the invitation');
            throw $this->errorHandler;
        }
    }
}

class ApproveMemberInvitationToProject_Data
{
    /** @var User */
    public $executor;

    /** @var int */
    public $userID;

    /** @var int */
    public $projectID;
}