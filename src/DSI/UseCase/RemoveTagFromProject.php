<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectTag;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Repository\ProjectTagRepo;
use DSI\Repository\TagForProjectsRepo;
use DSI\Service\ErrorHandler;

class RemoveTagFromProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectTagRepo */
    private $projectTagRepo;

    /** @var RemoveTagFromProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RemoveTagFromProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectTagRepo = new ProjectTagRepo();

        $tagRepo = new TagForProjectsRepo();
        $projectRepo = new ProjectRepoInAPC();

        if ($tagRepo->nameExists($this->data()->tag)) {
            $tag = $tagRepo->getByName($this->data()->tag);
        } else {
            $createTag = new CreateTagForProjects();
            $createTag->data()->name = $this->data()->tag;
            $createTag->exec();
            $tag = $createTag->getTag();
        }

        if (!$this->projectTagRepo->projectHasTagName($this->data()->projectID, $this->data()->tag)) {
            $this->errorHandler->addTaggedError('tag', 'The project does not have this tag');
            $this->errorHandler->throwIfNotEmpty();
        }

        $projectTag = new ProjectTag();
        $projectTag->setTag($tag);
        $projectTag->setProject($projectRepo->getById($this->data()->projectID));
        $this->projectTagRepo->remove($projectTag);
    }

    /**
     * @return RemoveTagFromProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class RemoveTagFromProject_Data
{
    /** @var string */
    public $tag;

    /** @var int */
    public $projectID;
}