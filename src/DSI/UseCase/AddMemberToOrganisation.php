<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationMember;
use DSI\Repository\OrganisationMemberRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class AddMemberToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationMemberRepo */
    private $organisationMemberRepo;

    /** @var OrganisationRepo */
    private $organisationRepository;

    /** @var UserRepo */
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
        $this->organisationMemberRepo = new OrganisationMemberRepo();
        $this->organisationRepository = new OrganisationRepoInAPC();
        $this->userRepository = new UserRepo();

        if ($this->organisationMemberRepo->organisationIDHasMemberID($this->data()->organisationID, $this->data()->userID)) {
            $this->errorHandler->addTaggedError('member', __('This user is already a member of the organisation'));
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