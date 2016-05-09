<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectTag;
use DSI\Repository\ProjectRepository;
use DSI\Repository\ProjectTagRepository;
use DSI\Repository\TagForProjectsRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class RemoveTagFromProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectTagRepository */
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
        $this->projectTagRepo = new ProjectTagRepository();

        $tagRepo = new TagForProjectsRepository();
        $projectRepo = new ProjectRepository();
        $userRepo = new UserRepository();

        if ($tagRepo->nameExists($this->data()->tag)) {
            $tag = $tagRepo->getByName($this->data()->tag);
        } else {
            $createTag = new CreateTagForProjects();
            $createTag->data()->name = $this->data()->tag;
            $createTag->exec();
            $tag = $createTag->getTag();
        }

        if (!$this->projectTagRepo->projectHasTagName($this->data()->projectID, $this->data()->tag)) {
            $this->errorHandler->addTaggedError('tag', 'Project does not have this tag');
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