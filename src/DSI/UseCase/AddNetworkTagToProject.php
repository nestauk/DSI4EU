<?php

namespace DSI\UseCase;

use DSI\Entity\Project;
use DSI\Entity\ProjectNetworkTag;
use DSI\Repository\NetworkTagRepo;
use DSI\Repository\ProjectNetworkTagRepo;
use DSI\Service\ErrorHandler;

class AddNetworkTagToProject
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ProjectNetworkTagRepo */
    private $projectNetworkTagRepo;

    /** @var String */
    private $tag;

    /** @var Project */
    private $project;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->projectNetworkTagRepo = new ProjectNetworkTagRepo();

        $networkTagRepo = new NetworkTagRepo();

        if ($networkTagRepo->nameExists($this->tag)) {
            $tag = $networkTagRepo->getByName($this->tag);
        } else {
            $createTag = new CreateNetworkTag();
            $createTag->data()->name = $this->tag;
            $createTag->exec();
            $tag = $createTag->getTag();
        }

        if ($this->projectNetworkTagRepo->projectHasTagName($this->project->getId(), $this->tag)) {
            $this->errorHandler->addTaggedError('tag', __('The project already has this tag'));
            $this->errorHandler->throwIfNotEmpty();
        }

        $projectNetworkTag = new ProjectNetworkTag();
        $projectNetworkTag->setTag($tag);
        $projectNetworkTag->setProject($this->project);
        $this->projectNetworkTagRepo->add($projectNetworkTag);
    }

    /**
     * @param String $tag
     */
    public function setTag(String $tag)
    {
        $this->tag = $tag;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }
}