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

    /** @var boolean */
    private $isImpact,
        $isTechnology;

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

        $this->tag = new Tag();
        $this->tag->setName($this->name);
        $this->tag->setIsImpact($this->isImpact);
        $this->tag->setIsTechnology($this->isTechnology);
        $this->tag->save();

        /*
        $tag = new ImpactTag();
        $tag->setName((string)$this->name);
        $this->tagRepo->insert($tag);
        $this->tag = $tag;
        */

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

    /**
     * @param bool $isImpact
     * @return CreateImpactTag
     */
    public function setIsImpact(bool $isImpact)
    {
        $this->isImpact = $isImpact;
        return $this;
    }

    /**
     * @param bool $isTechnology
     * @return CreateImpactTag
     */
    public function setIsTechnology(bool $isTechnology)
    {
        $this->isTechnology = $isTechnology;
        return $this;
    }
}