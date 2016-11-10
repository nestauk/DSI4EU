<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectNetworkTag;
use DSI\Repository\NetworkTagRepository;
use DSI\Repository\ProjectNetworkTagRepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\ErrorHandler;

class AddNetworkTagToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectNetworkTagRepository*/
    private $projectNetworkTagRepo;

    /** @var ProjectRepository */
    private $projectRepository;

    /** @var AddNetworkTagToProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddNetworkTagToProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectNetworkTagRepo = new ProjectNetworkTagRepository();
        $this->projectRepository = new ProjectRepository();

        $networkTagRepo = new NetworkTagRepository();

        if ($networkTagRepo->nameExists($this->data()->tag)) {
            $tag = $networkTagRepo->getByName($this->data()->tag);
        } else {
            $createTag = new CreateNetworkTag();
            $createTag->data()->name = $this->data()->tag;
            $createTag->exec();
            $tag = $createTag->getTag();
        }

        if($this->projectNetworkTagRepo->projectHasTagName($this->data()->projectID, $this->data()->tag)) {
            $this->errorHandler->addTaggedError('tag', 'Project already has this tag');
            $this->errorHandler->throwIfNotEmpty();
        }
            
        $projectNetworkTag = new ProjectNetworkTag();
        $projectNetworkTag->setTag($tag);
        $projectNetworkTag->setProject( $this->projectRepository->getById($this->data()->projectID) );
        $this->projectNetworkTagRepo->add($projectNetworkTag);
    }

    /**
     * @return AddNetworkTagToProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class AddNetworkTagToProject_Data
{
    /** @var string */
    public $tag;

    /** @var int */
    public $projectID;
}