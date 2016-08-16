<?php

namespace DSI\Entity;

class ProjectPostComment
{
    /** @var int */
    private $id,
        $repliesCount;

    /** @var ProjectPost */
    private $projectPost;

    /** @var User */
    private $user;

    /** @var string */
    private $time,
        $comment;

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * @return int
     */
    public function getRepliesCount()
    {
        return (int)$this->repliesCount;
    }

    /**
     * @param int $repliesCount
     */
    public function setRepliesCount($repliesCount)
    {
        $this->repliesCount = (int)$repliesCount;
    }

    /**
     * @return ProjectPost
     */
    public function getProjectPost()
    {
        return $this->projectPost;
    }

    /**
     * @return int
     */
    public function getProjectPostId()
    {
        return $this->projectPost ? $this->projectPost->getId() : 0;
    }

    /**
     * @param ProjectPost $projectPost
     */
    public function setProjectPost(ProjectPost $projectPost)
    {
        $this->projectPost = $projectPost;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user ? $this->user->getId() : 0;
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
    public function getTime()
    {
        return (string)$this->time;
    }

    /**
     * @return string
     */
    public function getUnixTime()
    {
        return (string)strtotime($this->time);
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = (string)$time;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return (string)$this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = (string)$comment;
    }
}