<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationMember;
use DSI\Entity\OrganisationMemberInvitation;
use DSI\Entity\User;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationMemberInvitationRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\OrganisationRepositoryInAPC;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class AcceptMemberInvitationToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationMemberInvitationRepository */
    private $organisationMemberInvitationRepository;

    /** @var OrganisationMemberRepository */
    private $organisationMemberRepository;

    /** @var OrganisationRepository */
    private $organisationRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var ApproveMemberInvitationToOrganisation_Data */
    private $data;

    public function __construct()
    {
        $this->data = new ApproveMemberInvitationToOrganisation_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationMemberInvitationRepository = new OrganisationMemberInvitationRepository();
        $this->organisationMemberRepository = new OrganisationMemberRepository();
        $this->organisationRepository = new OrganisationRepositoryInAPC();
        $this->userRepository = new UserRepository();

        $this->assertExecutorIsSet();
        $this->assertExecutorCanExecute();
        $this->assertUserHasBeenInvited();

        $member = $this->userRepository->getById($this->data()->userID);
        $organisation = $this->organisationRepository->getById($this->data()->organisationID);

        $organisationMemberInvitation = new OrganisationMemberInvitation();
        $organisationMemberInvitation->setMember($member);
        $organisationMemberInvitation->setOrganisation($organisation);
        $this->organisationMemberInvitationRepository->remove($organisationMemberInvitation);

        $organisationMember = new OrganisationMember();
        $organisationMember->setMember($member);
        $organisationMember->setOrganisation($organisation);
        $this->organisationMemberRepository->insert($organisationMember);
    }

    /**
     * @return ApproveMemberInvitationToOrganisation_Data
     */
    public function data()
    {
        return $this->data;
    }

    private function assertUserHasBeenInvited()
    {
        if (!$this->organisationMemberInvitationRepository->userIdHasInvitationToOrganisationId($this->data()->userID, $this->data()->organisationID)) {
            $this->errorHandler->addTaggedError('member', 'This user was not invited to join the organisation');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function assertExecutorIsSet()
    {
        if (!$this->data()->executor OR $this->data()->executor->getId() < 1)
            throw new \InvalidArgumentException('executor');
    }

    private function assertExecutorCanExecute()
    {
        if ($this->data()->executor->getId() != $this->data()->userID) {
            $this->errorHandler->addTaggedError('executor', 'Only the invited person can approve the invitation');
            throw $this->errorHandler;
        }
    }
}

class ApproveMemberInvitationToOrganisation_Data
{
    /** @var User */
    public $executor;

    /** @var int */
    public $userID;

    /** @var int */
    public $organisationID;
}