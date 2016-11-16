<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectImpactTechTag;
use DSI\Repository\ImpactTagRepository;
use DSI\Repository\ProjectImpactTechTagRepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\ErrorHandler;

class RemoveImpactTechTagFromProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectImpactTechTagRepository */
    private $projectImpactTagRepo;

    /** @var RemoveImpactTagCFromProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RemoveImpactTagCFromProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectImpactTagRepo = new ProjectImpactTechTagRepository();

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

        $projectTag = new ProjectImpactTechTag();
        $projectTag->setTag($tag);
        $projectTag->setProject($projectRepo->getById($this->data()->projectID));
        $this->projectImpactTagRepo->remove($projectTag);
    }

    /**
     * @return RemoveImpactTagCFromProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class RemoveImpactTagCFromProject_Data
{
    /** @var string */
    public $tag;

    /** @var int */
    public $projectID;
}