<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectImpactTagA;
use DSI\Repository\ImpactTagRepository;
use DSI\Repository\ProjectImpactTagARepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\ErrorHandler;

class AddImpactTagAToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectImpactTagARepository */
    private $projectImpactTagRepository;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var AddImpactTagAToProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddImpactTagAToProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectImpactTagRepository = new ProjectImpactTagARepository();
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

        $projectImpactTag = new ProjectImpactTagA();
        $projectImpactTag->setTag($tag);
        $projectImpactTag->setProject($this->projectRepository->getById($this->data()->projectID));
        $this->projectImpactTagRepository->add($projectImpactTag);
    }

    /**
     * @return AddImpactTagAToProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class AddImpactTagAToProject_Data
{
    /** @var string */
    public $tag;

    /** @var int */
    public $projectID;
}