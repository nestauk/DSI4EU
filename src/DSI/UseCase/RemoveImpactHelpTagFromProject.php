<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectImpactHelpTag;
use DSI\Repository\ImpactTagRepo;
use DSI\Repository\ProjectImpactHelpTagRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Service\ErrorHandler;

class RemoveImpactHelpTagFromProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectImpactHelpTagRepo */
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
        $this->projectImpactTagRepo = new ProjectImpactHelpTagRepo();

        $tagRepo = new ImpactTagRepo();
        $projectRepo = new ProjectRepoInAPC();

        if ($tagRepo->nameExists($this->data()->tag)) {
            $tag = $tagRepo->getByName($this->data()->tag);
        } else {
            $createTag = (new CreateImpactTag())
                ->setName($this->data()->tag)
                ->exec();
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