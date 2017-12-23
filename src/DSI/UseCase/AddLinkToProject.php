<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectLink;
use DSI\Repository\ProjectLinkRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\ProjectRepoInAPC;
use DSI\Service\ErrorHandler;

class AddLinkToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectLinkRepo */
    private $projectLinkRepository;

    /** @var AddLinkToProject_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddLinkToProject_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectLinkRepository = new ProjectLinkRepo();

        $projectRepository = new ProjectRepoInAPC();

        if($this->projectLinkRepository->projectHasLink($this->data()->projectID, $this->data()->link)) {
            $this->errorHandler->addTaggedError('skill', __('The project already has this link'));
            $this->errorHandler->throwIfNotEmpty();
        }
            
        $projectLink = new ProjectLink();
        $projectLink->setLink($this->data()->link);
        $projectLink->setProject( $projectRepository->getById($this->data()->projectID) );
        $this->projectLinkRepository->add($projectLink);
    }

    /**
     * @return AddLinkToProject_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class AddLinkToProject_Data
{
    /** @var string */
    public $link;

    /** @var int */
    public $projectID;
}