<?php

namespace DSI\Entity;

class ProjectMember
{
    /** @var Project */
    private $project;

    /** @var User */
    private $member;

    /** @var  bool */
    private $isAdmin;

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

    public function getMember(): User
    {
        return $this->member;
    }

    /**
     * @return int
     */
    public function getMemberID(): int
    {
        return $this->member->getId();
    }

    public function setMember(User $member)
    {
        $this->member = $member;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return (bool)$this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = (bool)$isAdmin;
    }
}