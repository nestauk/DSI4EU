<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectImpactTagB;
use DSI\Repository\ImpactTagRepository;
use DSI\Repository\ProjectImpactTagBRepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\ErrorHandler;

class RemoveImpactTagBFromProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectImpactTagBRepository */
    private $projectImpactTagRepo;

    /** @var RemoveImpactTagBFromProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RemoveImpactTagBFromProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectImpactTagRepo = new ProjectImpactTagBRepository();

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
            $this->errorHandler->addTaggedError('tag', 'Project does not have this tag');
            $this->errorHandler->throwIfNotEmpty();
        }

        $projectTag = new ProjectImpactTagB();
        $projectTag->setTag($tag);
        $projectTag->setProject($projectRepo->getById($this->data()->projectID));
        $this->projectImpactTagRepo->remove($projectTag);
    }

    /**
     * @return RemoveImpactTagBFromProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class RemoveImpactTagBFromProject_Data
{
    /** @var string */
    public $tag;

    /** @var int */
    public $projectID;
}