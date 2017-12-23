<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectPostComment;
use DSI\Entity\ProjectPostCommentReply;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\Repository\ProjectPostCommentReplyRepo;
use DSI\Repository\ProjectPostCommentRepo;
use DSI\Service\ErrorHandler;

class AddReplyToProjectPostComment
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectPostCommentReplyRepo */
    private $projectPostCommentReplyRepo;

    /** @var ProjectPostCommentReply */
    private $projectPostCommentReply;

    /** @var AddReplyToProjectPostComment_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddReplyToProjectPostComment_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectPostCommentReplyRepo = new ProjectPostCommentReplyRepo();

        $this->checkForUnsentData();
        $this->checkForInvalidData();
        $this->saveReply();
        $this->updateCommentRepliesCount();
    }

    /**
     * @return AddReplyToProjectPostComment_Data
     */
    public function data()
    {
        return $this->data;
    }

    public function getProjectPostCommentReply()
    {
        return $this->projectPostCommentReply;
    }

    private function saveReply()
    {
        $reply = new ProjectPostCommentReply();
        $reply->setProjectPostComment($this->data()->projectPostComment);
        $reply->setUser($this->data()->user);
        $reply->setComment($this->data()->reply);
        $this->projectPostCommentReplyRepo->insert($reply);

        $this->projectPostCommentReply = $this->projectPostCommentReplyRepo->getById($reply->getId());
    }

    private function checkForInvalidData()
    {
        if ($this->data()->projectPostComment->getId() <= 0)
            $this->errorHandler->addTaggedError('projectPostComment', 'Invalid comment ID');
        if ($this->data()->user->getId() <= 0)
            $this->errorHandler->addTaggedError('user', 'Invalid user ID');
        if ($this->data()->reply == '')
            $this->errorHandler->addTaggedError('comment', __('Please type a comment'));

        $this->errorHandler->throwIfNotEmpty();
    }

    private function checkForUnsentData()
    {
        if (!isset($this->data()->projectPostComment))
            throw new NotEnoughData('projectPostComment');
        if (!isset($this->data()->user))
            throw new NotEnoughData('user');
        if (!isset($this->data()->reply))
            throw new NotEnoughData('comment');
    }

    private function updateCommentRepliesCount()
    {
        $comment = $this->data()->projectPostComment;
        $replies = $this->projectPostCommentReplyRepo->getByCommentID(
            $comment->getId()
        );
        $comment->setRepliesCount(count($replies));
        (new ProjectPostCommentRepo())->save($comment);
    }
}

class AddReplyToProjectPostComment_Data
{
    /** @var string */
    public $reply;

    /** @var ProjectPostComment */
    public $projectPostComment;

    /** @var User */
    public $user;
}