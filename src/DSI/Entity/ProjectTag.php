<?php

namespace DSI\Entity;

class ProjectTag
{
    /** @var Project */
    private $project;

    /** @var TagForProjects */
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

    public function getTag(): TagForProjects
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

    public function setTag(TagForProjects $tag)
    {
        $this->tag = $tag;
    }
}