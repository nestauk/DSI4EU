<?php

namespace DSI\UseCase;

use DSI\Entity\ImpactTag;
use DSI\Repository\ImpactTagRepo;
use DSI\Service\ErrorHandler;
use Models\Tag;

class CreateImpactTag
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ImpactTagRepo */
    private $tagRepo;

    /** @var ImpactTag */
    private $tag;

    /** @var string */
    private $name;

    public function __construct()
    {
        $this->errorHandler = new ErrorHandler();
        $this->tagRepo = new ImpactTagRepo();
    }

    public function exec()
    {
        if ($this->tagRepo->nameExists($this->name)) {
            $this->errorHandler->addTaggedError('tag', __('Tag name already exists'));
            $this->errorHandler->throwIfNotEmpty();
        }

        $this->tag = new ImpactTag();
        $this->tag->setName($this->name);
        $this->tagRepo->insert($this->tag);

        return $this;
    }

    /**
     * @return ImpactTag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $name
     * @return CreateImpactTag
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }
}