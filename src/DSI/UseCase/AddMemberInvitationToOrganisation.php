<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationMemberInvitation;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationMemberInvitationRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class AddMemberInvitationToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationMemberInvitationRepository*/
    private $organisationMemberInvitationRepository;

    /** @var OrganisationRepository */
    private $organisationRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var AddMemberInvitationToOrganisation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new AddMemberInvitationToOrganisation_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationMemberInvitationRepository = new OrganisationMemberInvitationRepository();
        $this->organisationRepository = new OrganisationRepository();
        $this->userRepository = new UserRepository();

        $this->checkIfOrganisationAlreadyHasTheMember();
        $this->checkIfUserHasAlreadyBeenInvited();
        $this->addMemberInvitation();
    }

    /**
     * @return AddMemberInvitationToOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function checkIfOrganisationAlreadyHasTheMember()
    {
        if ((new OrganisationMemberRepository())->organisationIDHasMemberID($this->data()->organisationID, $this->data()->userID)) {
            $this->errorHandler->addTaggedError('member', 'This user is already a member of the organisation');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function checkIfUserHasAlreadyBeenInvited()
    {
        if ($this->organisationMemberInvitationRepository->memberHasInvitationToOrganisation($this->data()->userID, $this->data()->organisationID)) {
            $this->errorHandler->addTaggedError('member', 'This user has already been invited to join the organisation');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function addMemberInvitation()
    {
        $organisationMemberInvitation = new OrganisationMemberInvitation();
        $organisationMemberInvitation->setMember($this->userRepository->getById($this->data()->userID));
        $organisationMemberInvitation->setOrganisation($this->organisationRepository->getById($this->data()->organisationID));
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);
    }
}

class AddMemberInvitationToOrganisation_Data
{
    /** @var int */
    public $userID;

    /** @var int */
    public $organisationID;
}