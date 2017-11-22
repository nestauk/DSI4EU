<?php

namespace DSI\UseCase;

use DSI\Entity\ContentUpdate;
use DSI\Entity\Project;
use DSI\Entity\ProjectMember;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\Repository\ContentUpdateRepository;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectRepositoryInAPC;
use DSI\Service\ErrorHandler;

class CreateProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectRepository */
    private $projectRepo;

    /** @var Project */
    private $project;

    /** @var CreateProject_Data */
    private $data;

    /** @var bool */
    public $forceCreation;

    public function __construct()
    {
        $this->data = new CreateProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        if(!$this->forceCreation){
            $this->errorHandler->addTaggedError('name', __("We are sorry, but at the moment you cannot add a new project. We are working on getting this fixed as soon as possible."));
            throw $this->errorHandler;
        }

        $this->projectRepo = new ProjectRepositoryInAPC();

        if (!isset($this->data()->name))
            throw new NotEnoughData('project name');
        if (!isset($this->data()->owner))
            throw new NotEnoughData('owner');

        if ($this->data()->owner->getId() <= 0) {
            $this->errorHandler->addTaggedError('owner', 'Invalid user ID');
            throw $this->errorHandler;
        }

        if ($this->data()->name == '') {
            $this->errorHandler->addTaggedError('name', __('Please type a project name'));
            throw $this->errorHandler;
        }

        $project = new Project();
        $project->setName((string)$this->data()->name);
        $project->setDescription((string)$this->data()->description);
        $project->setOwner($this->data()->owner);
        $this->projectRepo->insert($project);

        $contentUpdate = new ContentUpdate();
        $contentUpdate->setProject($project);
        $contentUpdate->setUpdated(ContentUpdate::Updated_New);
        (new ContentUpdateRepository())->insert($contentUpdate);

        $projectMemberRepository = new ProjectMemberRepository();
        $projectMember = new ProjectMember();
        $projectMember->setMember($this->data()->owner);
        $projectMember->setProject($project);
        $projectMember->setIsAdmin(true);
        $projectMemberRepository->insert($projectMember);

        $this->project = $project;
    }

    /**
     * @return CreateProject_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }
}

class CreateProject_Data
{
    /** @var string */
    public $name,
        $description;

    /** @var User */
    public $owner;
}