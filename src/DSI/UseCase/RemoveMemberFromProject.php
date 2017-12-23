<?php

namespace DSI\UseCase;

use DSI\Entity\Project;
use DSI\Entity\ProjectMember;
use DSI\Entity\User;
use DSI\Repository\ProjectMemberRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class RemoveMemberFromProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectMemberRepo */
    private $projectMemberRepo;

    /** @var Project */
    private $project;

    /** @var User */
    private $user;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectMemberRepo = new ProjectMemberRepo();

        if (!$this->projectMemberRepo->projectHasMember($this->project, $this->user)) {
            $this->errorHandler->addTaggedError('member', 'The user is not a member of the project');
            throw $this->errorHandler;
        }

        if($this->project->getOwner()->getId() == $this->user->getId()){
            $this->errorHandler->addTaggedError('member', 'The project owner cannot be removed from the project');
            throw $this->errorHandler;
        }

        $projectMember = new ProjectMember();
        $projectMember->setMember($this->user);
        $projectMember->setProject($this->project);
        $this->projectMemberRepo->remove($projectMember);
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @param int $projectID
     */
    public function setProjectId($projectID)
    {
        $this->project = (new ProjectRepoInAPC())->getById($projectID);
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param int $userID
     */
    public function setUserId($userID)
    {
        $this->user = (new UserRepo())->getById((int)$userID);
    }
}