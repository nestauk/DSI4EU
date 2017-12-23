<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationMemberRequest;
use DSI\Repository\OrganisationMemberRepo;
use DSI\Repository\OrganisationMemberRequestRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class AddMemberRequestToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationMemberRequestRepo */
    private $organisationMemberRequestRepo;

    /** @var OrganisationRepo */
    private $organisationRepository;

    /** @var UserRepo */
    private $userRepository;

    /** @var AddMemberRequestToOrganisation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddMemberRequestToOrganisation_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationMemberRequestRepo = new OrganisationMemberRequestRepo();
        $this->organisationRepository = new OrganisationRepoInAPC();
        $this->userRepository = new UserRepo();

        $this->checkIfOrganisationAlreadyHasTheMember();
        $this->checkIfThereIsAlreadyARequestFromTheUser();
        $this->addMemberRequest();
    }

    /**
     * @return AddMemberRequestToOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function checkIfOrganisationAlreadyHasTheMember()
    {
        if ((new OrganisationMemberRepo())->organisationIDHasMemberID($this->data()->organisationID, $this->data()->userID)) {
            $this->errorHandler->addTaggedError('member', __('This user is already a member of the organisation'));
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function checkIfThereIsAlreadyARequestFromTheUser()
    {
        if ($this->organisationMemberRequestRepo->organisationHasRequestFromMember($this->data()->organisationID, $this->data()->userID)) {
            $this->errorHandler->addTaggedError('member', __('This user has already made a request to join the organisation'));
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function addMemberRequest()
    {
        $organisationMemberRequest = new OrganisationMemberRequest();
        $organisationMemberRequest->setMember($this->userRepository->getById($this->data()->userID));
        $organisationMemberRequest->setOrganisation($this->organisationRepository->getById($this->data()->organisationID));
        $this->organisationMemberRequestRepo->add($organisationMemberRequest);
    }
}

class AddMemberRequestToOrganisation_Data
{
    /** @var int */
    public $userID;

    /** @var int */
    public $organisationID;
}