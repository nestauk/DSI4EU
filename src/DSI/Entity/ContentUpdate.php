<?php

namespace DSI\Entity;

class ContentUpdate
{
    const New_Content = 'new';
    const Updated_Content = 'update';

    /** @var integer */
    private $id;

    /** @var Project */
    private $project;

    /** @var Organisation */
    private $organisation;

    /** @var string */
    private $updated;

    /** @var string */
    private $timestamp;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ContentUpdate
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    public function hasProject(): bool
    {
        return $this->project ? true : false;
    }

    public function hasOrganisation(): bool
    {
        return $this->organisation ? true : false;
    }

    /**
     * @return int
     */
    public function getProjectID(): int
    {
        return $this->project ? $this->project->getId() : 0;
    }

    /**
     * @param Project $project
     * @return ContentUpdate
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * @return Organisation
     */
    public function getOrganisation(): Organisation
    {
        return $this->organisation;
    }

    /**
     * @return int
     */
    public function getOrganisationID(): int
    {
        return $this->organisation ? $this->organisation->getId() : 0;
    }

    /**
     * @param Organisation $organisation
     * @return ContentUpdate
     */
    public function setOrganisation(Organisation $organisation)
    {
        $this->organisation = $organisation;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdated(): string
    {
        return $this->updated;
    }

    /**
     * @param string $updated
     * @return ContentUpdate
     */
    public function setUpdated(string $updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * @param string $timestamp
     * @return ContentUpdate
     */
    public function setTimestamp(string $timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /** @return User | null */
    public function getOwner()
    {
        if ($this->hasProject())
            return $this->getProject()->getOwner();
        elseif ($this->hasOrganisation())
            return $this->getOrganisation()->getOwner();
        else return null;
    }
}