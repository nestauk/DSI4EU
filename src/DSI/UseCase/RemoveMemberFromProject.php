<?php

namespace DSI\UseCase;

use DSI\Entity\Project;
use DSI\Entity\ProjectMember;
use DSI\Entity\User;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class RemoveMemberFromProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectMemberRepository */
    private $projectMemberRepo;

    /** @var Project */
    private $project;

    /** @var User */
    private $user;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectMemberRepo = new ProjectMemberRepository();

        if (!$this->projectMemberRepo->projectHasMember($this->project, $this->user)) {
            $this->errorHandler->addTaggedError('member', 'The user is not a member of the project');
            $this->errorHandler->throwIfNotEmpty();
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
        $this->project = (new ProjectRepository())->getById($projectID);
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
        $this->user = (new UserRepository())->getById((int)$userID);
    }
}