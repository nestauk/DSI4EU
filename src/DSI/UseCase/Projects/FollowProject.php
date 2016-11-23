<?php

namespace DSI\UseCase\Projects;

use DSI\Entity\Project;
use DSI\Entity\ProjectFollow;
use DSI\Entity\User;
use DSI\Repository\ProjectFollowRepository;
use DSI\Service\ErrorHandler;

class FollowProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var Project */
    private $project;

    /** @var User */
    private $user,
        $executor;

    public function __construct()
    {
        $this->errorHandler = new ErrorHandler();
    }

    public function exec()
    {
        $this->assertDataHasBeenSent();
        $this->assertExecutorCanMakeChanges();
        $this->followProject();
    }

    /**
     * @return bool
     */
    private function userCanDeleteProject()
    {
        if ($this->executor->isSysAdmin())
            return true;

        if ($this->executor->getId() == $this->project->getOwnerID())
            return true;

        return false;
    }

    private function assertExecutorCanMakeChanges()
    {
        if ($this->executor->getId() != $this->user->getId()) {
            $this->errorHandler->addTaggedError('user', 'You cannot make this change');
            throw $this->errorHandler;
        }
    }

    private function assertDataHasBeenSent()
    {
        if (!$this->executor)
            throw new \InvalidArgumentException('No executor');
        if (!$this->user)
            throw new \InvalidArgumentException('No user');
        if (!$this->project)
            throw new \InvalidArgumentException('No project');
    }

    private function followProject()
    {
        $follow = new ProjectFollow();
        $follow->setUser($this->user);
        $follow->setProject($this->project);
        (new ProjectFollowRepository())->add($follow);
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

    /**
     * @param User $executor
     */
    public function setExecutor(User $executor)
    {
        $this->executor = $executor;
    }
}