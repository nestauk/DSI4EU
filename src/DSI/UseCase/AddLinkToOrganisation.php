<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationLink;
use DSI\Repository\OrganisationLinkRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Service\ErrorHandler;

class AddLinkToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationLinkRepo */
    private $organisationLinkRepository;

    /** @var AddLinkToOrganisation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddLinkToOrganisation_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationLinkRepository = new OrganisationLinkRepo();

        $organisationRepository = new OrganisationRepoInAPC();

        if($this->organisationLinkRepository->organisationHasLink($this->data()->organisationID, $this->data()->link)) {
            $this->errorHandler->addTaggedError('skill', __('The organisation already has this link'));
            $this->errorHandler->throwIfNotEmpty();
        }
            
        $organisationLink = new OrganisationLink();
        $organisationLink->setLink($this->data()->link);
        $organisationLink->setOrganisation( $organisationRepository->getById($this->data()->organisationID) );
        $this->organisationLinkRepository->add($organisationLink);
    }

    /**
     * @return AddLinkToOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class AddLinkToOrganisation_Data
{
    /** @var string */
    public $link;

    /** @var int */
    public $organisationID;
}