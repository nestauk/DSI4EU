<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationNetworkTag;
use DSI\Repository\NetworkTagRepository;
use DSI\Repository\OrganisationNetworkTagRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class RemoveNetworkTagFromOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationNetworkTagRepository */
    private $organisationNetworkTagRepo;

    /** @var RemoveNetworkTagFromOrganisation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RemoveNetworkTagFromOrganisation_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationNetworkTagRepo = new OrganisationNetworkTagRepository();

        $networkTagRepo = new NetworkTagRepository();
        $organisationRepo = new OrganisationRepository();
        $userRepo = new UserRepository();

        if ($networkTagRepo->nameExists($this->data()->tag)) {
            $tag = $networkTagRepo->getByName($this->data()->tag);
        } else {
            $createTag = new CreateNetworkTag();
            $createTag->data()->name = $this->data()->tag;
            $createTag->exec();
            $tag = $createTag->getTag();
        }

        if (!$this->organisationNetworkTagRepo->organisationHasTagName($this->data()->organisationID, $this->data()->tag)) {
            $this->errorHandler->addTaggedError('tag', 'Organisation does not have this tag');
            $this->errorHandler->throwIfNotEmpty();
        }

        $organisationTag = new OrganisationNetworkTag();
        $organisationTag->setTag($tag);
        $organisationTag->setOrganisation($organisationRepo->getById($this->data()->organisationID));
        $this->organisationNetworkTagRepo->remove($organisationTag);
    }

    /**
     * @return RemoveNetworkTagFromOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class RemoveNetworkTagFromOrganisation_Data
{
    /** @var string */
    public $tag;

    /** @var int */
    public $organisationID;
}