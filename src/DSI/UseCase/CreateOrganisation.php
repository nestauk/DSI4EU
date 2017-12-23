<?php

namespace DSI\UseCase;

use DSI\Entity\ContentUpdate;
use DSI\Entity\Organisation;
use DSI\Entity\OrganisationMember;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\Repository\ContentUpdateRepository;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\OrganisationRepositoryInAPC;
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

    /** @var bool */
    public $forceCreation;

    public function __construct()
    {
        $this->data = new CreateOrganisation_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        if (!$this->forceCreation) {
            $this->errorHandler->addTaggedError('name', __("We are sorry, but at the moment you cannot add a new organisation. We are working on getting this fixed as soon as possible."));
            throw $this->errorHandler;
        }

        $this->organisationRepo = new OrganisationRepositoryInAPC();

        if (!isset($this->data()->name))
            throw new NotEnoughData('organisation name');
        if (!isset($this->data()->owner))
            throw new NotEnoughData('owner');

        if ($this->data()->owner->getId() <= 0) {
            $this->errorHandler->addTaggedError('owner', 'Invalid user ID');
            throw $this->errorHandler;
        }

        if ($this->data()->name == '') {
            $this->errorHandler->addTaggedError('name', __('Please type a organisation name'));
            throw $this->errorHandler;
        }

        $organisation = new Organisation();
        $organisation->setName((string)$this->data()->name);
        $organisation->setDescription((string)$this->data()->description);
        $organisation->setOwner($this->data()->owner);
        $organisation->setIsWaitingApproval(true);
        $this->organisationRepo->insert($organisation);

        $contentUpdate = new ContentUpdate();
        $contentUpdate->setOrganisation($organisation);
        $contentUpdate->setUpdated(ContentUpdate::Updated_New);
        (new ContentUpdateRepository())->insert($contentUpdate);

        $organisationMemberRepository = new OrganisationMemberRepository();
        $organisationMember = new OrganisationMember();
        $organisationMember->setMember($this->data()->owner);
        $organisationMember->setOrganisation($organisation);
        $organisationMember->setIsAdmin(true);
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