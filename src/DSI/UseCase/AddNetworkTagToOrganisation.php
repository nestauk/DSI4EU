<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationNetworkTag;
use DSI\Repository\NetworkTagRepository;
use DSI\Repository\OrganisationNetworkTagRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Service\ErrorHandler;

class AddNetworkTagToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationNetworkTagRepository*/
    private $organisationNetworkTagRepo;

    /** @var OrganisationRepository */
    private $organisationRepo;

    /** @var AddNetworkTagToOrganisation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddNetworkTagToOrganisation_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationNetworkTagRepo = new OrganisationNetworkTagRepository();
        $this->organisationRepo = new OrganisationRepository();

        $networkTagRepo = new NetworkTagRepository();

        if ($networkTagRepo->nameExists($this->data()->tag)) {
            $tag = $networkTagRepo->getByName($this->data()->tag);
        } else {
            $createTag = new CreateNetworkTag();
            $createTag->data()->name = $this->data()->tag;
            $createTag->exec();
            $tag = $createTag->getTag();
        }

        if($this->organisationNetworkTagRepo->organisationHasTagName($this->data()->organisationID, $this->data()->tag)) {
            $this->errorHandler->addTaggedError('tag', 'Organisation already has this tag');
            $this->errorHandler->throwIfNotEmpty();
        }
            
        $organisationNetworkTag = new OrganisationNetworkTag();
        $organisationNetworkTag->setTag($tag);
        $organisationNetworkTag->setOrganisation( $this->organisationRepo->getById($this->data()->organisationID) );
        $this->organisationNetworkTagRepo->add($organisationNetworkTag);
    }

    /**
     * @return AddNetworkTagToOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class AddNetworkTagToOrganisation_Data
{
    /** @var string */
    public $tag;

    /** @var int */
    public $organisationID;
}