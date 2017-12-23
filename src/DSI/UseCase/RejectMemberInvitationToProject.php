<?php

namespace DSI\UseCase;

use DSI\Entity\Project;
use DSI\Entity\ProjectMemberInvitation;
use DSI\Entity\User;
use DSI\Repository\ProjectMemberRepo;
use DSI\Repository\ProjectMemberInvitationRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class RejectMemberInvitationToProject
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

    /** @var RejectMemberInvitationToProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RejectMemberInvitationToProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectMemberInvitationRepo = new ProjectMemberInvitationRepo();
        $this->projectMemberRepo = new ProjectMemberRepo();
        $this->projectRepository = new ProjectRepoInAPC();
        $this->userRepository = new UserRepo();

        $this->assertExecutorIsSet();

        $user = $this->userRepository->getById($this->data()->userID);
        $project = $this->projectRepository->getById($this->data()->projectID);

        $this->assertExecutorCanExecute();
        $this->assertUserHasBeenInvitedToProject($user, $project);

        $this->deleteMemberInvitation($user, $project);
    }

    /**
     * @return RejectMemberInvitationToProject_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function assertUserHasBeenInvitedToProject(User $user, Project $project)
    {
        if (!$this->projectMemberInvitationRepo->userHasBeenInvitedToProject($user, $project)) {
            $this->errorHandler->addTaggedError('member', 'This user was not invited to join the project');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function assertExecutorIsSet()
    {
        if (!$this->data()->executor OR $this->data()->executor->getId() < 1)
            throw new \InvalidArgumentException('executor');
    }

    private function assertExecutorCanExecute()
    {
        if ($this->data()->executor->getId() != $this->data()->userID) {
            $this->errorHandler->addTaggedError('executor', 'Only the invited person can approve the invitation');
            throw $this->errorHandler;
        }
    }

    /**
     * @param $member
     * @param $project
     * @throws \DSI\NotFound
     */
    private function deleteMemberInvitation($member, $project)
    {
        $projectMemberInvitation = new ProjectMemberInvitation();
        $projectMemberInvitation->setMember($member);
        $projectMemberInvitation->setProject($project);
        $this->projectMemberInvitationRepo->remove($projectMemberInvitation);
    }
}

class RejectMemberInvitationToProject_Data
{
    /** @var User */
    public $executor;

    /** @var int */
    public $userID;

    /** @var int */
    public $projectID;
}