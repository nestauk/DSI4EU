<?php

namespace DSI\UseCase;

use DSI\Entity\DsiFocusTag;
use DSI\Repository\DsiFocusTagRepository;
use DSI\Service\ErrorHandler;

class CreateDsiFocusTag
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var DsiFocusTagRepository */
    private $tagRepo;

    /** @var DsiFocusTag */
    private $tag;

    /** @var CreateDsiFocusTag_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CreateDsiFocusTag_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->tagRepo = new DsiFocusTagRepository();

        if($this->tagRepo->nameExists($this->data()->name)){
            $this->errorHandler->addTaggedError('tag', __('Tag name already exists'));
            $this->errorHandler->throwIfNotEmpty();
        }

        $tag = new DsiFocusTag();
        $tag->setName((string)$this->data()->name);
        $this->tagRepo->insert($tag);

        $this->tag = $tag;
    }

    /**
     * @return CreateDsiFocusTag_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return DsiFocusTag
     */
    public function getTag()
    {
        return $this->tag;
    }
}

class CreateDsiFocusTag_Data
{
    /** @var string */
    public $name;
}