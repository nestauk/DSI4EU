<?php

namespace DSI\UseCase;

use DSI\Entity\Organisation;
use DSI\Entity\OrganisationNetworkTag;
use DSI\Repository\NetworkTagRepo;
use DSI\Repository\OrganisationNetworkTagRepo;
use DSI\Service\ErrorHandler;

class AddNetworkTagToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationNetworkTagRepo */
    private $organisationNetworkTagRepo;

    /** @var String */
    private $tag;

    /** @var Organisation */
    private $organisation;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationNetworkTagRepo = new OrganisationNetworkTagRepo();

        $networkTagRepo = new NetworkTagRepo();

        if ($networkTagRepo->nameExists($this->tag)) {
            $tag = $networkTagRepo->getByName($this->tag);
        } else {
            $createTag = new CreateNetworkTag();
            $createTag->data()->name = $this->tag;
            $createTag->exec();
            $tag = $createTag->getTag();
        }

        if ($this->organisationNetworkTagRepo->organisationHasTagName($this->organisation->getId(), $this->tag)) {
            $this->errorHandler->addTaggedError('tag', __('The organisation already has this tag'));
            $this->errorHandler->throwIfNotEmpty();
        }

        $organisationNetworkTag = new OrganisationNetworkTag();
        $organisationNetworkTag->setTag($tag);
        $organisationNetworkTag->setOrganisation($this->organisation);
        $this->organisationNetworkTagRepo->add($organisationNetworkTag);
    }

    /**
     * @param String $tag
     */
    public function setTag(String $tag)
    {
        $this->tag = $tag;
    }

    /**
     * @param Organisation $organisation
     */
    public function setOrganisation(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }
}