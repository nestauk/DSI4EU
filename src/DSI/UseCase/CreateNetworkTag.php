<?php

namespace DSI\UseCase;

use DSI\Entity\NetworkTag;
use DSI\Repository\NetworkTagRepository;
use DSI\Service\ErrorHandler;

class CreateNetworkTag
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var NetworkTagRepository */
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
        $this->networkTagRepo = new NetworkTagRepository();

        if($this->networkTagRepo->nameExists($this->data()->name)){
            $this->errorHandler->addTaggedError('tag', 'Tag Already Exists');
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