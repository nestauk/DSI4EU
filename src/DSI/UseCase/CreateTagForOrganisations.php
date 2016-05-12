<?php

namespace DSI\UseCase;

use DSI\Entity\TagForOrganisations;
use DSI\Repository\TagForOrganisationsRepository;
use DSI\Service\ErrorHandler;

class CreateTagForOrganisations
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var TagForOrganisationsRepository */
    private $tagRepo;

    /** @var TagForOrganisations */
    private $tag;

    /** @var CreateTagForOrganisations_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CreateTagForOrganisations_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->tagRepo = new TagForOrganisationsRepository();

        if($this->tagRepo->nameExists($this->data()->name)){
            $this->errorHandler->addTaggedError('tag', 'Tag Already Exists');
            $this->errorHandler->throwIfNotEmpty();
        }

        $tag = new TagForOrganisations();
        $tag->setName((string)$this->data()->name);
        $this->tagRepo->saveAsNew($tag);

        $this->tag = $tag;
    }

    /**
     * @return CreateTagForOrganisations_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return TagForOrganisations
     */
    public function getTag()
    {
        return $this->tag;
    }
}

class CreateTagForOrganisations_Data
{
    /** @var string */
    public $name;
}