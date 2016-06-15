<?php

namespace DSI\UseCase;

use DSI\Entity\Organisation;
use DSI\Entity\OrganisationMember;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Service\ErrorHandler;

class CreateOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationRepository */
    private $organisationRepo;

    /** @var Organisation */
    private $organisation;

    /** @var CreateOrganisation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CreateOrganisation_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationRepo = new OrganisationRepository();

        if (!isset($this->data()->name))
            throw new NotEnoughData('organisation name');
        if (!isset($this->data()->owner))
            throw new NotEnoughData('owner');

        if ($this->data()->owner->getId() <= 0) {
            $this->errorHandler->addTaggedError('owner', 'Invalid user ID');
            throw $this->errorHandler;
        }

        if($this->data()->name == ''){
            $this->errorHandler->addTaggedError('name', 'Please type a organisation name');
            throw $this->errorHandler;
        }

        $organisation = new Organisation();
        $organisation->setName((string)$this->data()->name);
        $organisation->setDescription((string)$this->data()->description);
        $organisation->setOwner($this->data()->owner);
        $this->organisationRepo->insert($organisation);

        $organisationMemberRepository = new OrganisationMemberRepository();
        $organisationMember = new OrganisationMember();
        $organisationMember->setMember($this->data()->owner);
        $organisationMember->setOrganisation($organisation);
        $organisationMemberRepository->insert($organisationMember);

        $this->organisation = $organisation;
    }

    /**
     * @return CreateOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return Organisation
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }
}

class CreateOrganisation_Data
{
    /** @var string */
    public $name,
        $description;

    /** @var User */
    public $owner;
}