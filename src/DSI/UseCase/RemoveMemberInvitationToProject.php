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

class RemoveMemberInvitationToProject
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

    private $userID,
        $projectID;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectMemberInvitationRepo = new ProjectMemberInvitationRepo();
        $this->projectMemberRepo = new ProjectMemberRepo();
        $this->projectRepository = new ProjectRepoInAPC();
        $this->userRepository = new UserRepo();

        $user = $this->userRepository->getById($this->userID);
        $project = $this->projectRepository->getById($this->projectID);

        $this->assertUserHasBeenInvitedToProject($user, $project);
        $this->deleteMemberInvitation($user, $project);
    }

    private function assertUserHasBeenInvitedToProject(User $user, Project $project)
    {
        if (!$this->projectMemberInvitationRepo->userHasBeenInvitedToProject($user, $project)) {
            $this->errorHandler->addTaggedError('member', 'This user was not invited to join the project');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    /**
     * @param User $member
     * @param Project $project
     */
    private function deleteMemberInvitation(User $member, Project $project)
    {
        $projectMemberInvitation = new ProjectMemberInvitation();
        $projectMemberInvitation->setMember($member);
        $projectMemberInvitation->setProject($project);
        $this->projectMemberInvitationRepo->remove($projectMemberInvitation);
    }

    /**
     * @param mixed $userID
     */
    public function setUserID($userID)
    {
        $this->userID = (int)$userID;
    }

    /**
     * @param mixed $projectID
     */
    public function setProjectID($projectID)
    {
        $this->projectID = (int)$projectID;
    }
}