<?php

namespace DSI\UseCase;

use DSI\Entity\NetworkTag;
use DSI\Repository\NetworkTagRepo;
use DSI\Service\ErrorHandler;

class CreateNetworkTag
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var NetworkTagRepo */
    private $networkTagRepo;

    /** @var NetworkTag */
    private $tag;

    /** @var CreateNetworkTag_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CreateNetworkTag_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->networkTagRepo = new NetworkTagRepo();

        if($this->networkTagRepo->nameExists($this->data()->name)){
            $this->errorHandler->addTaggedError('tag', __('Tag name already exists'));
            $this->errorHandler->throwIfNotEmpty();
        }

        $tag = new NetworkTag();
        $tag->setName((string)$this->data()->name);
        $this->networkTagRepo->saveAsNew($tag);

        $this->tag = $tag;
    }

    /**
     * @return CreateNetworkTag_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return NetworkTag
     */
    public function getTag()
    {
        return $this->tag;
    }
}

class CreateNetworkTag_Data
{
    /** @var string */
    public $name;
}