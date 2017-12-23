<?php

namespace DSI\UseCase;

use DSI\Entity\ImpactTag;
use DSI\Repository\ImpactTagRepo;
use DSI\Service\ErrorHandler;

class CreateImpactTag
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ImpactTagRepo */
    private $tagRepo;

    /** @var ImpactTag */
    private $tag;

    /** @var CreateImpactTag_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CreateImpactTag_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->tagRepo = new ImpactTagRepo();

        if($this->tagRepo->nameExists($this->data()->name)){
            $this->errorHandler->addTaggedError('tag', __('Tag name already exists'));
            $this->errorHandler->throwIfNotEmpty();
        }

        $tag = new ImpactTag();
        $tag->setName((string)$this->data()->name);
        $this->tagRepo->insert($tag);

        $this->tag = $tag;
    }

    /**
     * @return CreateImpactTag_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return ImpactTag
     */
    public function getTag()
    {
        return $this->tag;
    }
}

class CreateImpactTag_Data
{
    /** @var string */
    public $name;
}