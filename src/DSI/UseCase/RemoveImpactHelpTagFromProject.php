<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectImpactHelpTag;
use DSI\Repository\ImpactTagRepository;
use DSI\Repository\ProjectImpactHelpTagRepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\ErrorHandler;

class RemoveImpactHelpTagFromProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectImpactHelpTagRepository */
    private $projectImpactTagRepo;

    /** @var RemoveImpactHelpTagFromProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RemoveImpactHelpTagFromProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectImpactTagRepo = new ProjectImpactHelpTagRepository();

        $tagRepo = new ImpactTagRepository();
        $projectRepo = new ProjectRepository();

        if ($tagRepo->nameExists($this->data()->tag)) {
            $tag = $tagRepo->getByName($this->data()->tag);
        } else {
            $createTag = new CreateImpactTag();
            $createTag->data()->name = $this->data()->tag;
            $createTag->exec();
            $tag = $createTag->getTag();
        }

        if (!$this->projectImpactTagRepo->projectHasTagName($this->data()->projectID, $this->data()->tag)) {
            $this->errorHandler->addTaggedError('tag', __('Project does not have this tag'));
            $this->errorHandler->throwIfNotEmpty();
        }

        $projectTag = new ProjectImpactHelpTag();
        $projectTag->setTag($tag);
        $projectTag->setProject($projectRepo->getById($this->data()->projectID));
        $this->projectImpactTagRepo->remove($projectTag);
    }

    /**
     * @return RemoveImpactHelpTagFromProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class RemoveImpactHelpTagFromProject_Data
{
    /** @var string */
    public $tag;

    /** @var int */
    public $projectID;
}