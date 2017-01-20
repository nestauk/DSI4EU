<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectDsiFocusTag;
use DSI\Repository\DsiFocusTagRepository;
use DSI\Repository\ProjectDsiFocusTagRepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\ErrorHandler;

class AddDsiFocusTagToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectDsiFocusTagRepository */
    private $projectDsiFocusTagRepository;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var AddDsiFocusTagBToProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddDsiFocusTagBToProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectDsiFocusTagRepository = new ProjectDsiFocusTagRepository();
        $this->projectRepository = new ProjectRepository();

        $tagRepo = new DsiFocusTagRepository();

        if ($tagRepo->nameExists($this->data()->tag)) {
            $tag = $tagRepo->getByName($this->data()->tag);
        } else {
            $createTag = new CreateDsiFocusTag();
            $createTag->data()->name = $this->data()->tag;
            $createTag->exec();
            $tag = $createTag->getTag();
        }

        if ($this->projectDsiFocusTagRepository->projectHasTagName($this->data()->projectID, $this->data()->tag)) {
            $this->errorHandler->addTaggedError('tag', __('The project already has this tag'));
            $this->errorHandler->throwIfNotEmpty();
        }

        $projectDsiFocusTag = new ProjectDsiFocusTag();
        $projectDsiFocusTag->setTag($tag);
        $projectDsiFocusTag->setProject($this->projectRepository->getById($this->data()->projectID));
        $this->projectDsiFocusTagRepository->add($projectDsiFocusTag);
    }

    /**
     * @return AddDsiFocusTagBToProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class AddDsiFocusTagBToProject_Data
{
    /** @var string */
    public $tag;

    /** @var int */
    public $projectID;
}