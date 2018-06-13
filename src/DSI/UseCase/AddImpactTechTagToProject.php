<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectImpactTechTag;
use DSI\Repository\ImpactTagRepo;
use DSI\Repository\ProjectImpactTechTagRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Service\ErrorHandler;

class AddImpactTechTagToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectImpactTechTagRepo */
    private $projectImpactTagRepository;

    /** @var ProjectRepo */
    private $projectRepository;

    /** @var AddImpactTechTagToProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddImpactTechTagToProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectImpactTagRepository = new ProjectImpactTechTagRepo();
        $this->projectRepository = new ProjectRepoInAPC();

        $tagRepo = new ImpactTagRepo();

        if ($tagRepo->nameExists($this->data()->tag)) {
            $tag = $tagRepo->getByName($this->data()->tag);
        } else {
            $createTag = (new CreateImpactTag())
                ->setName($this->data()->tag)
                ->exec();
            $tag = $createTag->getTag();
        }

        if ($this->projectImpactTagRepository->projectHasTagName($this->data()->projectID, $this->data()->tag)) {
            $this->errorHandler->addTaggedError('tag', __('The project already has this tag'));
            $this->errorHandler->throwIfNotEmpty();
        }

        $projectImpactTag = new ProjectImpactTechTag();
        $projectImpactTag->setTag($tag);
        $projectImpactTag->setProject($this->projectRepository->getById($this->data()->projectID));
        $this->projectImpactTagRepository->add($projectImpactTag);
    }

    /**
     * @return AddImpactTechTagToProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class AddImpactTechTagToProject_Data
{
    /** @var string */
    public $tag;

    /** @var int */
    public $projectID;
}