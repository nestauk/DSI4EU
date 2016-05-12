<?php

namespace DSI\UseCase;

use DSI\Entity\Organisation;
use DSI\Entity\OrganisationType;
use DSI\Entity\User;
use DSI\NotEnoughData;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\OrganisationSizeRepository;
use DSI\Repository\OrganisationTypeRepository;
use DSI\Service\ErrorHandler;

class UpdateOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UpdateOrganisation_Data */
    private $data;

    /** @var OrganisationRepository */
    private $organisationRepo;

    public function __construct()
    {
        $this->data = new UpdateOrganisation_Data();
        $this->organisationRepo = new OrganisationRepository();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();

        $this->checkIfAllInfoHaveBeenSent();
        $this->checkIfUserCanEditTheOrganisation();
        $this->checkIfNameIsNotEmpty();
        $this->saveOrganisationDetails();
    }

    /**
     * @return UpdateOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function saveOrganisationDetails()
    {
        $this->data()->organisation->setName($this->data()->name);
        if (isset($this->data()->description))
            $this->data()->organisation->setDescription($this->data()->description);
        if ($this->data()->organisationTypeId)
            $this->data()->organisation->setOrganisationType(
                (new OrganisationTypeRepository())->getById(
                    $this->data()->organisationTypeId
                )
            );
        if ($this->data()->organisationSizeId)
            $this->data()->organisation->setOrganisationSize(
                (new OrganisationSizeRepository())->getById(
                    $this->data()->organisationSizeId
                )
            );

        $this->organisationRepo->save($this->data()->organisation);
    }

    private function checkIfAllInfoHaveBeenSent()
    {
        if (!isset($this->data()->name))
            throw new NotEnoughData('name');
        if (!isset($this->data()->user))
            throw new NotEnoughData('user');
        if (!isset($this->data()->organisation))
            throw new NotEnoughData('organisation');
    }

    private function checkIfUserCanEditTheOrganisation()
    {
        if ($this->data()->user->getId() != $this->data()->organisation->getOwner()->getId()) {
            $this->errorHandler->addTaggedError('user', 'Only the owner can make changes to the organisation');
            throw $this->errorHandler;
        }
    }

    private function checkIfNameIsNotEmpty()
    {
        if ($this->data()->name == '')
            $this->errorHandler->addTaggedError('name', 'Please type a organisation name');

        $this->errorHandler->throwIfNotEmpty();
    }
}

class UpdateOrganisation_Data
{
    /** @var int */
    public $organisationTypeId,
        $organisationSizeId;

    /** @var string */
    public $name,
        $description;

    /** @var Organisation */
    public $organisation;

    /** @var User */
    public $user;
}