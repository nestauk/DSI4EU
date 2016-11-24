<?php

namespace DSI\Entity;

class ProjectPost
{
    /** @var int */
    private $id;

    /** @var Project */
    private $project;

    /** @var User */
    private $user;

    /** @var string */
    private $time,
        $title,
        $text;

    /** @var int */
    private $commentsCount;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getTime($format = null)
    {
        if ($format !== null) {
            return date($format, strtotime($this->time));
        }

        return $this->time;
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getCommentsCount()
    {
        return (int)$this->commentsCount;
    }

    /**
     * @param int $commentsCount
     */
    public function setCommentsCount($commentsCount)
    {
        $this->commentsCount = (int)$commentsCount;
    }
}