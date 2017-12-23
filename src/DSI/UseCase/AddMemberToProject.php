<?php

namespace DSI\UseCase;

use DSI\Entity\Project;
use DSI\Entity\ProjectMember;
use DSI\Entity\User;
use DSI\Repository\ProjectMemberRepo;
use DSI\Service\ErrorHandler;

class AddMemberToProject
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

        if ($this->projectMemberRepo->projectHasMember($this->project, $this->user)) {
            $this->errorHandler->addTaggedError('member', __('This user is already a member of the project'));
            $this->errorHandler->throwIfNotEmpty();
        }

        $projectMember = new ProjectMember();
        $projectMember->setMember($this->user);
        $projectMember->setProject($this->project);
        $this->projectMemberRepo->insert($projectMember);
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}