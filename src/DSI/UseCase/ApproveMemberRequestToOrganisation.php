<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationMember;
use DSI\Entity\OrganisationMemberRequest;
use DSI\Entity\User;
use DSI\Repository\OrganisationMemberRepo;
use DSI\Repository\OrganisationMemberRequestRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class ApproveMemberRequestToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationMemberRequestRepo */
    private $organisationMemberRequestRepo;

    /** @var OrganisationMemberRepo */
    private $organisationMemberRepo;

    /** @var OrganisationRepo */
    private $organisationRepository;

    /** @var UserRepo */
    private $userRepository;

    /** @var ApproveMemberRequestToOrganisation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new ApproveMemberRequestToOrganisation_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationMemberRequestRepo = new OrganisationMemberRequestRepo();
        $this->organisationMemberRepo = new OrganisationMemberRepo();
        $this->organisationRepository = new OrganisationRepoInAPC();
        $this->userRepository = new UserRepo();

        if (!$this->organisationMemberRequestRepo->organisationHasRequestFromMember($this->data()->organisationID, $this->data()->userID)) {
            $this->errorHandler->addTaggedError('member', 'This user has not made a request to join the organisation');
            $this->errorHandler->throwIfNotEmpty();
        }

        $member = $this->userRepository->getById($this->data()->userID);
        $organisation = $this->organisationRepository->getById($this->data()->organisationID);

        $organisationMemberRequest = new OrganisationMemberRequest();
        $organisationMemberRequest->setMember($member);
        $organisationMemberRequest->setOrganisation($organisation);
        $this->organisationMemberRequestRepo->remove($organisationMemberRequest);

        $organisationMember = new OrganisationMember();
        $organisationMember->setMember($member);
        $organisationMember->setOrganisation($organisation);
        $this->organisationMemberRepo->insert($organisationMember);
    }

    /**
     * @return ApproveMemberRequestToOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class ApproveMemberRequestToOrganisation_Data
{
    /** @var User */
    public $executor;

    /** @var int */
    public $userID;

    /** @var int */
    public $organisationID;
}