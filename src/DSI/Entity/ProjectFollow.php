<?php

namespace DSI\Entity;

class ProjectFollow
{
    /** @var Project */
    private $project;

    /** @var User */
    private $user;

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @return int
     */
    public function getProjectID(): int
    {
        return $this->project->getId();
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getUserID(): int
    {
        return $this->user->getId();
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }
}