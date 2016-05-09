<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectImpactTagB;
use DSI\Repository\ImpactTagRepository;
use DSI\Repository\ProjectImpactTagBRepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\ErrorHandler;

class AddImpactTagBToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectImpactTagBRepository */
    private $projectImpactTagRepository;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var AddImpactTagBToProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddImpactTagBToProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectImpactTagRepository = new ProjectImpactTagBRepository();
        $this->projectRepository = new ProjectRepository();

        $tagRepo = new ImpactTagRepository();

        if ($tagRepo->nameExists($this->data()->tag)) {
            $tag = $tagRepo->getByName($this->data()->tag);
        } else {
            $createTag = new CreateImpactTag();
            $createTag->data()->name = $this->data()->tag;
            $createTag->exec();
            $tag = $createTag->getTag();
        }

        if ($this->projectImpactTagRepository->projectHasTagName($this->data()->projectID, $this->data()->tag)) {
            $this->errorHandler->addTaggedError('tag', 'Project already has this tag');
            $this->errorHandler->throwIfNotEmpty();
        }

        $projectImpactTag = new ProjectImpactTagB();
        $projectImpactTag->setTag($tag);
        $projectImpactTag->setProject($this->projectRepository->getById($this->data()->projectID));
        $this->projectImpactTagRepository->add($projectImpactTag);
    }

    /**
     * @return AddImpactTagBToProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class AddImpactTagBToProject_Data
{
    /** @var string */
    public $tag;

    /** @var int */
    public $projectID;
}