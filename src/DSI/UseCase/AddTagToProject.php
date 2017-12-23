<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectTag;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Repository\ProjectTagRepo;
use DSI\Repository\TagForProjectsRepo;
use DSI\Service\ErrorHandler;

class AddTagToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectTagRepo */
    private $projectTagRepository;

    /** @var ProjectRepo */
    private $projectRepository;

    /** @var AddTagToProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddTagToProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectTagRepository = new ProjectTagRepo();
        $this->projectRepository = new ProjectRepoInAPC();

        $tagRepo = new TagForProjectsRepo();

        if ($tagRepo->nameExists($this->data()->tag)) {
            $tag = $tagRepo->getByName($this->data()->tag);
        } else {
            $createTag = new CreateTagForProjects();
            $createTag->data()->name = $this->data()->tag;
            $createTag->exec();
            $tag = $createTag->getTag();
        }

        if($this->projectTagRepository->projectHasTagName($this->data()->projectID, $this->data()->tag)) {
            $this->errorHandler->addTaggedError('tag', __('The project already has this tag'));
            $this->errorHandler->throwIfNotEmpty();
        }
            
        $projectTag = new ProjectTag();
        $projectTag->setTag($tag);
        $projectTag->setProject( $this->projectRepository->getById($this->data()->projectID) );
        $this->projectTagRepository->add($projectTag);
    }

    /**
     * @return AddTagToProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class AddTagToProject_Data
{
    /** @var string */
    public $tag;

    /** @var int */
    public $projectID;
}