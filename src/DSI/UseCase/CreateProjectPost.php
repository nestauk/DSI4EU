<?php

namespace DSI\UseCase;

use DSI\Entity\Project;
use DSI\Entity\ProjectPost;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\Repository\ProjectPostRepository;
use DSI\Service\ErrorHandler;

class CreateProjectPost
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectPostRepository */
    private $projectPostRepo;

    /** @var ProjectPost */
    private $projectPost;

    /** @var CreateProjectPost_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CreateProjectPost_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectPostRepo = new ProjectPostRepository();

        $this->checkForUnsentData();
        $this->checkForInvalidData();
        $this->insertProjectPost();
    }

    /**
     * @return CreateProjectPost_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return Project
     */
    public function getProjectPost()
    {
        return $this->projectPost;
    }

    private function insertProjectPost()
    {
        $post = new ProjectPost();
        $post->setProject($this->data()->project);
        $post->setUser($this->data()->user);
        $post->setTitle($this->data()->title);
        $post->setText($this->data()->text);
        $this->projectPostRepo->insert($post);

        $this->projectPost = $post;
    }

    private function checkForInvalidData()
    {
        if ($this->data()->project->getId() <= 0)
            $this->errorHandler->addTaggedError('project', 'Invalid project ID');
        if ($this->data()->user->getId() <= 0)
            $this->errorHandler->addTaggedError('user', 'Invalid user ID');
        if ($this->data()->project->getOwner()->getId() != $this->data()->user->getId())
            $this->errorHandler->addTaggedError('user', 'Only the owner can add a post');

        $this->errorHandler->throwIfNotEmpty();
    }

    private function checkForUnsentData()
    {
        if (!isset($this->data()->project))
            throw new NotEnoughData('project');
        if (!isset($this->data()->user))
            throw new NotEnoughData('owner');
    }
}

class CreateProjectPost_Data
{
    /** @var string */
    public $title,
        $text;

    /** @var Project */
    public $project;

    /** @var User */
    public $user;
}