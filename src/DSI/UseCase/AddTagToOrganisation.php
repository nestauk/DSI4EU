<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationTag;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\OrganisationRepositoryInAPC;
use DSI\Repository\OrganisationTagRepository;
use DSI\Repository\TagForOrganisationsRepository;
use DSI\Service\ErrorHandler;

class AddTagToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationTagRepository */
    private $organisationTagRepository;

    /** @var OrganisationRepository */
    private $organisationRepository;

    /** @var AddTagToOrganisation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddTagToOrganisation_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationTagRepository = new OrganisationTagRepository();
        $this->organisationRepository = new OrganisationRepositoryInAPC();

        $tagRepo = new TagForOrganisationsRepository();

        if ($tagRepo->nameExists($this->data()->tag)) {
            $tag = $tagRepo->getByName($this->data()->tag);
        } else {
            $createTag = new CreateTagForOrganisations();
            $createTag->data()->name = $this->data()->tag;
            $createTag->exec();
            $tag = $createTag->getTag();
        }

        if($this->organisationTagRepository->organisationHasTagName($this->data()->organisationID, $this->data()->tag)) {
            $this->errorHandler->addTaggedError('tag', __('The organisation already has this tag'));
            $this->errorHandler->throwIfNotEmpty();
        }
            
        $organisationTag = new OrganisationTag();
        $organisationTag->setTag($tag);
        $organisationTag->setOrganisation( $this->organisationRepository->getById($this->data()->organisationID) );
        $this->organisationTagRepository->add($organisationTag);
    }

    /**
     * @return AddTagToOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class AddTagToOrganisation_Data
{
    /** @var string */
    public $tag;

    /** @var int */
    public $organisationID;
}