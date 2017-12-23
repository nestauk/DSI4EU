<?php

namespace DSI\UseCase;

use DSI\Entity\OrganisationMember;
use DSI\Entity\OrganisationMemberInvitation;
use DSI\Entity\User;
use DSI\Repository\OrganisationMemberRepo;
use DSI\Repository\OrganisationMemberInvitationRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class AcceptMemberInvitationToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationMemberInvitationRepo */
    private $organisationMemberInvitationRepository;

    /** @var OrganisationMemberRepo */
    private $organisationMemberRepository;

    /** @var OrganisationRepo */
    private $organisationRepository;

    /** @var UserRepo */
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
        $this->organisationMemberInvitationRepository = new OrganisationMemberInvitationRepo();
        $this->organisationMemberRepository = new OrganisationMemberRepo();
        $this->organisationRepository = new OrganisationRepoInAPC();
        $this->userRepository = new UserRepo();

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