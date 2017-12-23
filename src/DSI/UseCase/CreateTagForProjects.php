<?php

namespace DSI\UseCase;

use DSI\Entity\TagForProjects;
use DSI\Repository\TagForProjectsRepo;
use DSI\Service\ErrorHandler;

class CreateTagForProjects
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var TagForProjectsRepo */
    private $tagRepo;

    /** @var TagForProjects */
    private $tag;

    /** @var CreateTagForProjects_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CreateTagForProjects_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->tagRepo = new TagForProjectsRepo();

        if($this->tagRepo->nameExists($this->data()->name)){
            $this->errorHandler->addTaggedError('tag', __('Tag name already exists'));
            $this->errorHandler->throwIfNotEmpty();
        }

        $tag = new TagForProjects();
        $tag->setName((string)$this->data()->name);
        $this->tagRepo->saveAsNew($tag);

        $this->tag = $tag;
    }

    /**
     * @return CreateTagForProjects_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return TagForProjects
     */
    public function getTag()
    {
        return $this->tag;
    }
}

class CreateTagForProjects_Data
{
    /** @var string */
    public $name;
}