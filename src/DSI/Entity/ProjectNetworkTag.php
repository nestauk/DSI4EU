<?php

namespace DSI\Entity;

class ProjectNetworkTag
{
    /** @var Project */
    private $project;

    /** @var NetworkTag */
    private $tag;

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

    public function getTag(): NetworkTag
    {
        return $this->tag;
    }

    /**
     * @return int
     */
    public function getTagID(): int
    {
        return $this->tag->getId();
    }

    public function setTag(NetworkTag $tag)
    {
        $this->tag = $tag;
    }
}