<?php

namespace DSI\Entity;

class ProjectLink
{
    /** @var Project */
    private $project;

    /** @var string */
    private $link;

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

    /**
     * @return string
     */
    public function getLink(): string
    {
        return (string)$this->link;
    }

    /**
     * @return int
     */
    public function getLinkService(): int
    {
        if (preg_match("<^(https?:\/\/)?((w{3}\.)?)facebook\.com\/>", $this->link))
            return ProjectLink_Service::Facebook;
        if (preg_match("<^(https?:\/\/)?((w{3}\.)?)twitter\.com\/>", $this->link))
            return ProjectLink_Service::Twitter;
        if (preg_match("<^(https?:\/\/)?plus\.google\.com\/>", $this->link))
            return ProjectLink_Service::GooglePlus;
        if (preg_match("<^(https?:\/\/)?((w{3}\.)?)github\.com\/>", $this->link))
            return ProjectLink_Service::GitHub;

        return ProjectLink_Service::Other;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link)
    {
        $this->link = $link;
    }
}

class ProjectLink_Service
{
    const Facebook = 1;
    const Twitter = 2;
    const GooglePlus = 3;
    const GitHub = 4;

    const Other = 5;
}