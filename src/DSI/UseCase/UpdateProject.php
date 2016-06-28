<?php

namespace DSI\UseCase;

use DSI\Entity\Project;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\Repository\ProjectMemberRepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\ErrorHandler;

class UpdateProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UpdateProject_Data */
    private $data;

    /** @var ProjectRepository */
    private $projectRepo;

    public function __construct()
    {
        $this->data = new UpdateProject_Data();
        $this->projectRepo = new ProjectRepository();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();

        $this->checkIfAllInfoHaveBeenSent();
        $this->checkIfUserCanEditTheProject();
        $this->checkIfNameIsNotEmpty();
        $this->saveProjectDetails();
    }

    /**
     * @return UpdateProject_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function saveProjectDetails()
    {
        $this->data()->project->setName($this->data()->name);
        if (isset($this->data()->description))
            $this->data()->project->setDescription($this->data()->description);
        if (isset($this->data()->url))
            $this->data()->project->setUrl($this->data()->url);
        if (isset($this->data()->status))
            $this->data()->project->setStatus($this->data()->status);

        $this->data()->project->setStartDate($this->data()->startDate);
        $this->data()->project->setEndDate($this->data()->endDate);

        $this->projectRepo->save($this->data()->project);
    }

    private function checkIfAllInfoHaveBeenSent()
    {
        if (!isset($this->data()->name))
            throw new NotEnoughData('name');
        if (!isset($this->data()->user))
            throw new NotEnoughData('user');
        if (!isset($this->data()->project))
            throw new NotEnoughData('project');
    }

    private function checkIfUserCanEditTheProject()
    {
        if ($this->data()->user->getId() == $this->data()->project->getOwner()->getId())
            return true;
        if ((new ProjectMemberRepository())->projectHasMember(
            $this->data()->project->getId(),
            $this->data()->user->getId())
        )
            return true;

        $this->errorHandler->addTaggedError('user', 'Only the owner can make changes to the project');
        throw $this->errorHandler;
    }

    private function checkIfNameIsNotEmpty()
    {
        if ($this->data()->name == '')
            $this->errorHandler->addTaggedError('name', 'Please type a project name');

        $this->errorHandler->throwIfNotEmpty();
    }
}

class UpdateProject_Data
{
    /** @var string */
    public $name,
        $description,
        $url,
        $status,
        $startDate,
        $endDate;

    /** @var Project */
    public $project;

    /** @var User */
    public $user;
}