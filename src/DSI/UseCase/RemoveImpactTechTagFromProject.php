<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectImpactTechTag;
use DSI\Repository\ImpactTagRepo;
use DSI\Repository\ProjectImpactTechTagRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Service\ErrorHandler;

class RemoveImpactTechTagFromProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectImpactTechTagRepo */
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
        $this->projectImpactTagRepo = new ProjectImpactTechTagRepo();

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