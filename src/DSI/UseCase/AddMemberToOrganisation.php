<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationMember;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class AddMemberToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationMemberRepository */
    private $organisationMemberRepo;

    /** @var OrganisationRepository */
    private $organisationRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var AddMemberToOrganisation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddMemberToOrganisation_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationMemberRepo = new OrganisationMemberRepository();
        $this->organisationRepository = new OrganisationRepository();
        $this->userRepository = new UserRepository();

        if ($this->organisationMemberRepo->organisationIDHasMemberID($this->data()->organisationID, $this->data()->userID)) {
            $this->errorHandler->addTaggedError('member', 'This user is already a member of the organisation');
            $this->errorHandler->throwIfNotEmpty();
        }

        $organisationMember = new OrganisationMember();
        $organisationMember->setMember($this->userRepository->getById($this->data()->userID));
        $organisationMember->setOrganisation($this->organisationRepository->getById($this->data()->organisationID));
        $this->organisationMemberRepo->insert($organisationMember);
    }

    /**
     * @return AddMemberToOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class AddMemberToOrganisation_Data
{
    /** @var int */
    public $userID;

    /** @var int */
    public $organisationID;
}