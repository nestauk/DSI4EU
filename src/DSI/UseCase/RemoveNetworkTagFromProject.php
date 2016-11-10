<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectNetworkTag;
use DSI\Repository\NetworkTagRepository;
use DSI\Repository\ProjectNetworkTagRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class RemoveNetworkTagFromProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectNetworkTagRepository */
    private $projectNetworkTagRepo;

    /** @var RemoveNetworkTagFromProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RemoveNetworkTagFromProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectNetworkTagRepo = new ProjectNetworkTagRepository();

        $networkTagRepo = new NetworkTagRepository();
        $projectRepo = new ProjectRepository();
        $userRepo = new UserRepository();

        if ($networkTagRepo->nameExists($this->data()->tag)) {
            $tag = $networkTagRepo->getByName($this->data()->tag);
        } else {
            $createTag = new CreateNetworkTag();
            $createTag->data()->name = $this->data()->tag;
            $createTag->exec();
            $tag = $createTag->getTag();
        }

        if (!$this->projectNetworkTagRepo->projectHasTagName($this->data()->projectID, $this->data()->tag)) {
            $this->errorHandler->addTaggedError('tag', 'Project does not have this tag');
            $this->errorHandler->throwIfNotEmpty();
        }

        $projectTag = new ProjectNetworkTag();
        $projectTag->setTag($tag);
        $projectTag->setProject($projectRepo->getById($this->data()->projectID));
        $this->projectNetworkTagRepo->remove($projectTag);
    }

    /**
     * @return RemoveNetworkTagFromProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class RemoveNetworkTagFromProject_Data
{
    /** @var string */
    public $tag;

    /** @var int */
    public $projectID;
}