<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationMember;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class RemoveMemberFromOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationMemberRepository */
    private $organisationMemberRepo;

    /** @var RemoveMemberFromOrganisation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RemoveMemberFromOrganisation_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationMemberRepo = new OrganisationMemberRepository();

        $organisationRepo = new OrganisationRepository();
        $userRepo = new UserRepository();

        if (!$this->organisationMemberRepo->organisationHasMember($this->data()->organisationID, $this->data()->userID)) {
            $this->errorHandler->addTaggedError('member', 'User is not a member of the organisation');
            $this->errorHandler->throwIfNotEmpty();
        }

        $organisationMember = new OrganisationMember();
        $organisationMember->setMember($userRepo->getById($this->data()->userID));
        $organisationMember->setOrganisation($organisationRepo->getById($this->data()->organisationID));
        $this->organisationMemberRepo->remove($organisationMember);
    }

    /**
     * @return RemoveMemberFromOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class RemoveMemberFromOrganisation_Data
{
    /** @var int */
    public $userID;

    /** @var int */
    public $organisationID;
}