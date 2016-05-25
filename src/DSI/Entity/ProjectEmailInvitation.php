<?php

namespace DSI\Entity;

class ProjectEmailInvitation
{
    /** @var Project */
    private $project;

    /** @var User */
    private $byUser;

    /** @var string */
    private $email,
        $date;

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return int
     */
    public function getProjectID()
    {
        return $this->project ? $this->project->getId() : 0;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @return User
     */
    public function getByUser()
    {
        return $this->byUser;
    }

    /**
     * @return int
     */
    public function getByUserID()
    {
        return $this->byUser ? $this->byUser->getId() : 0;
    }

    /**
     * @param User $byUser
     */
    public function setByUser(User $byUser)
    {
        $this->byUser = $byUser;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return (string)$this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = (string)$email;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return (string)$this->date;
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = (string)$date;
    }
}