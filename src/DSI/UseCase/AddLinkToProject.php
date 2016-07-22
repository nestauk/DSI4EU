<?php

namespace DSI\UseCase;

use DSI\Entity\ProjectLink;
use DSI\Repository\ProjectLinkRepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\ErrorHandler;

class AddLinkToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectLinkRepository */
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
        $this->projectLinkRepository = new ProjectLinkRepository();

        $projectRepository = new ProjectRepository();

        if($this->projectLinkRepository->projectHasLink($this->data()->projectID, $this->data()->link)) {
            $this->errorHandler->addTaggedError('skill', 'Project already has this link');
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