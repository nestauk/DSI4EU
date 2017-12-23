<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectPost;
use DSI\Entity\ProjectPostComment;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\Repository\ProjectPostCommentRepo;
use DSI\Repository\ProjectPostRepo;
use DSI\Service\ErrorHandler;

class AddCommentToProjectPost
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectPostCommentRepo */
    private $projectPostCommentRepo;

    /** @var ProjectPostComment */
    private $projectPostComment;

    /** @var AddCommentToProjectPost_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddCommentToProjectPost_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectPostCommentRepo = new ProjectPostCommentRepo();

        $this->checkForUnsentData();
        $this->checkForInvalidData();
        $this->addComment();

        $this->updatePostCommentsCount($this->data()->projectPost);
    }

    /**
     * @return AddCommentToProjectPost_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return ProjectPostComment
     */
    public function getProjectPostComment()
    {
        return $this->projectPostComment;
    }

    private function addComment()
    {
        $postComment = new ProjectPostComment();
        $postComment->setProjectPost($this->data()->projectPost);
        $postComment->setUser($this->data()->user);
        $postComment->setComment($this->data()->comment);
        $this->projectPostCommentRepo->insert($postComment);

        $this->projectPostComment = $this->projectPostCommentRepo->getById($postComment->getId());
    }

    private function checkForInvalidData()
    {
        if ($this->data()->projectPost->getId() <= 0)
            $this->errorHandler->addTaggedError('projectPost', 'Invalid project post ID');
        if ($this->data()->user->getId() <= 0)
            $this->errorHandler->addTaggedError('user', 'Invalid user ID');
        if ($this->data()->comment == '')
            $this->errorHandler->addTaggedError('comment', __('Please type a comment'));

        $this->errorHandler->throwIfNotEmpty();
    }

    private function checkForUnsentData()
    {
        if (!isset($this->data()->projectPost))
            throw new NotEnoughData('projectPost');
        if (!isset($this->data()->user))
            throw new NotEnoughData('user');
        if (!isset($this->data()->comment))
            throw new NotEnoughData('comment');
    }

    private function updatePostCommentsCount(ProjectPost $post)
    {
        $comments = $this->projectPostCommentRepo->getByPostID($post->getId());
        $post->setCommentsCount(count($comments));
        (new ProjectPostRepo())->save($post);
    }
}

class AddCommentToProjectPost_Data
{
    /** @var string */
    public $comment;

    /** @var ProjectPost */
    public $projectPost;

    /** @var User */
    public $user;
}