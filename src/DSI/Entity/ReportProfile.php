<?php

namespace DSI\Entity;

class ReportProfile
{
    /** @var integer */
    private $id;

    /** @var User */
    private $byUser,
        $reportedUser;

    /** @var string */
    private $comment, $time;

    /** @var User */
    private $reviewedByUser;

    /** @var string */
    private $reviewedTime,
        $review;

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
        if ($id <= 0)
            throw new \InvalidArgumentException('id: ' . $id);

        $this->id = (int)$id;
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
    public function getByUserId()
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
     * @return User
     */
    public function getReportedUser()
    {
        return $this->reportedUser;
    }

    /**
     * @return int
     */
    public function getReportedUserId()
    {
        return $this->reportedUser ? $this->reportedUser->getId() : 0;
    }

    /**
     * @param User $reportedUser
     */
    public function setReportedUser(User $reportedUser)
    {
        $this->reportedUser = $reportedUser;
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

    /**
     * @return string
     */
    public function getTime()
    {
        return (string)$this->time;
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = (string)$time;
    }

    /**
     * @return User
     */
    public function getReviewedByUser()
    {
        return $this->reviewedByUser;
    }

    /**
     * @return int
     */
    public function getReviewedByUserId()
    {
        return $this->reviewedByUser ? $this->reviewedByUser->getId() : 0;
    }

    /**
     * @param User $reviewedByUser
     */
    public function setReviewedByUser(User $reviewedByUser)
    {
        $this->reviewedByUser = $reviewedByUser;
    }

    /**
     * @return string
     */
    public function getReviewedTime()
    {
        return (string)$this->reviewedTime;
    }

    /**
     * @param string $reviewedTime
     */
    public function setReviewedTime($reviewedTime)
    {
        $this->reviewedTime = (string)$reviewedTime;
    }

    /**
     * @return string
     */
    public function getReview()
    {
        return (string)$this->review;
    }

    /**
     * @param string $review
     */
    public function setReview($review)
    {
        $this->review = (string)$review;
    }
}