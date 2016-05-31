<?php

namespace DSI\Entity;

class ProjectPostCommentReply
{
    /** @var int */
    private $id;

    /** @var ProjectPostComment */
    private $projectPostComment;

    /** @var User */
    private $user;

    /** @var string */
    private $time,
        $comment;

    public function getId()
    {
        return (int)$this->id;
    }

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    public function getProjectPostComment()
    {
        return $this->projectPostComment;
    }

    public function getProjectPostCommentID()
    {
        return $this->projectPostComment ? $this->projectPostComment->getId() : 0;
    }

    public function setProjectPostComment(ProjectPostComment $projectPostComment)
    {
        $this->projectPostComment = $projectPostComment;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getUserId()
    {
        return $this->user ? $this->user->getId() : 0;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getTime()
    {
        return (string)$this->time;
    }

    public function setTime($time)
    {
        $this->time = (string)$time;
    }

    public function getComment()
    {
        return (string)$this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = (string)$comment;
    }
}