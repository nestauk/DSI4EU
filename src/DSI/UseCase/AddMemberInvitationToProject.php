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

class AddMemberInvitationToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectMemberInvitationRepo */
    private $projectMemberInvitationRepo;

    /** @var ProjectRepo */
    private $projectRepository;

    /** @var UserRepo */
    private $userRepository;

    /** @var Int */
    private $userID,
        $projectID;

    /** @var User */
    private $user;

    /** @var Project */
    private $project;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectMemberInvitationRepo = new ProjectMemberInvitationRepo();
        $this->projectRepository = new ProjectRepoInAPC();
        $this->userRepository = new UserRepo();

        $this->user = $this->userRepository->getById($this->userID);
        $this->project = $this->projectRepository->getById($this->projectID);

        $this->assertProjectAlreadyHasTheMember();
        $this->assertUserHasAlreadyBeenInvited();
        $this->addMemberInvitation();
    }

    private function assertProjectAlreadyHasTheMember()
    {
        if ((new ProjectMemberRepo())->projectIDHasMemberID($this->projectID, $this->userID)) {
            $this->errorHandler->addTaggedError('member', __('This user is already a member of the project'));
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function assertUserHasAlreadyBeenInvited()
    {
        if ($this->projectMemberInvitationRepo->userHasBeenInvitedToProject($this->user, $this->project)) {
            $this->errorHandler->addTaggedError('member', __('This user has already been invited to join the project'));
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function addMemberInvitation()
    {
        $projectMemberInvitation = new ProjectMemberInvitation();
        $projectMemberInvitation->setMember($this->user);
        $projectMemberInvitation->setProject($this->project);
        $this->projectMemberInvitationRepo->add($projectMemberInvitation);
    }

    /**
     * @param Int $userID
     */
    public function setUserID($userID)
    {
        $this->userID = (int)$userID;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->userID = $user->getId();
    }

    /**
     * @param Int $projectID
     */
    public function setProjectID($projectID)
    {
        $this->projectID = (int)$projectID;
    }

    /**
     * @param Project $project
     */
    public function setProject($project)
    {
        $this->projectID = $project->getId();
    }
}