<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectImpactHelpTag;
use DSI\Repository\ImpactTagRepo;
use DSI\Repository\ProjectImpactHelpTagRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Service\ErrorHandler;

class AddImpactHelpTagToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectImpactHelpTagRepo */
    private $projectImpactTagRepository;

    /** @var ProjectRepo */
    private $projectRepository;

    /** @var AddImpactHelpTagToProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddImpactHelpTagToProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectImpactTagRepository = new ProjectImpactHelpTagRepo();
        $this->projectRepository = new ProjectRepoInAPC();

        $tagRepo = new ImpactTagRepo();

        if ($tagRepo->nameExists($this->data()->tag)) {
            $tag = $tagRepo->getByName($this->data()->tag);
        } else {
            $createTag = new CreateImpactTag();
            $createTag->data()->name = $this->data()->tag;
            $createTag->exec();
            $tag = $createTag->getTag();
        }

        if ($this->projectImpactTagRepository->projectHasTagName($this->data()->projectID, $this->data()->tag)) {
            $this->errorHandler->addTaggedError('tag', __('The project already has this tag'));
            $this->errorHandler->throwIfNotEmpty();
        }

        $projectImpactTag = new ProjectImpactHelpTag();
        $projectImpactTag->setTag($tag);
        $projectImpactTag->setProject($this->projectRepository->getById($this->data()->projectID));
        $this->projectImpactTagRepository->add($projectImpactTag);
    }

    /**
     * @return AddImpactHelpTagToProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class AddImpactHelpTagToProject_Data
{
    /** @var string */
    public $tag;

    /** @var int */
    public $projectID;
}