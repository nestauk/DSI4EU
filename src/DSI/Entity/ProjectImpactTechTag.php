<?php

namespace DSI\Entity;

class ProjectImpactTechTag
{
    /** @var Project */
    private $project;

    /** @var ImpactTag */
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

    public function getTag(): ImpactTag
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

    public function setTag(ImpactTag $tag)
    {
        $this->tag = $tag;
    }
}