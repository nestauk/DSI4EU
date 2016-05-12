<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationTag;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\OrganisationTagRepository;
use DSI\Repository\TagForOrganisationsRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class RemoveTagFromOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationTagRepository */
    private $organisationTagRepo;

    /** @var RemoveTagFromOrganisation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RemoveTagFromOrganisation_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationTagRepo = new OrganisationTagRepository();

        $tagRepo = new TagForOrganisationsRepository();
        $organisationRepo = new OrganisationRepository();
        $userRepo = new UserRepository();

        if ($tagRepo->nameExists($this->data()->tag)) {
            $tag = $tagRepo->getByName($this->data()->tag);
        } else {
            $createTag = new CreateTagForOrganisations();
            $createTag->data()->name = $this->data()->tag;
            $createTag->exec();
            $tag = $createTag->getTag();
        }

        if (!$this->organisationTagRepo->organisationHasTagName($this->data()->organisationID, $this->data()->tag)) {
            $this->errorHandler->addTaggedError('tag', 'Organisation does not have this tag');
            $this->errorHandler->throwIfNotEmpty();
        }

        $organisationTag = new OrganisationTag();
        $organisationTag->setTag($tag);
        $organisationTag->setOrganisation($organisationRepo->getById($this->data()->organisationID));
        $this->organisationTagRepo->remove($organisationTag);
    }

    /**
     * @return RemoveTagFromOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class RemoveTagFromOrganisation_Data
{
    /** @var string */
    public $tag;

    /** @var int */
    public $organisationID;
}