<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationTag;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\OrganisationTagRepo;
use DSI\Repository\TagForOrganisationsRepo;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class RemoveTagFromOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationTagRepo */
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
        $this->organisationTagRepo = new OrganisationTagRepo();

        $tagRepo = new TagForOrganisationsRepo();
        $organisationRepo = new OrganisationRepoInAPC();
        $userRepo = new UserRepo();

        if ($tagRepo->nameExists($this->data()->tag)) {
            $tag = $tagRepo->getByName($this->data()->tag);
        } else {
            $createTag = new CreateTagForOrganisations();
            $createTag->data()->name = $this->data()->tag;
            $createTag->exec();
            $tag = $createTag->getTag();
        }

        if (!$this->organisationTagRepo->organisationHasTagName($this->data()->organisationID, $this->data()->tag)) {
            $this->errorHandler->addTaggedError('tag', 'The organisation does not have this tag');
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