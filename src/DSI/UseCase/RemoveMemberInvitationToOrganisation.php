<?php

namespace DSI\UseCase;

use DSI\Entity\Organisation;
use DSI\Entity\OrganisationMemberInvitation;
use DSI\Entity\User;
use DSI\Repository\OrganisationMemberInvitationRepository;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\OrganisationRepositoryInAPC;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class RemoveMemberInvitationToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationMemberInvitationRepository */
    private $organisationMemberInvitationRepo;

    /** @var OrganisationMemberRepository */
    private $organisationMemberRepo;

    /** @var OrganisationRepository */
    private $organisationRepo;

    /** @var UserRepository */
    private $userRepository;

    private $userID,
        $organisationID;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationMemberInvitationRepo = new OrganisationMemberInvitationRepository();
        $this->organisationMemberRepo = new OrganisationMemberRepository();
        $this->organisationRepo = new OrganisationRepositoryInAPC();
        $this->userRepository = new UserRepository();

        $user = $this->userRepository->getById($this->userID);
        $organisation = $this->organisationRepo->getById($this->organisationID);

        $this->assertUserHasBeenInvitedToOrganisation($user, $organisation);
        $this->deleteMemberInvitation($user, $organisation);
    }

    private function assertUserHasBeenInvitedToOrganisation(User $user, Organisation $organisation)
    {
        if (!$this->organisationMemberInvitationRepo->userHasInvitationToOrganisation($user, $organisation)) {
            $this->errorHandler->addTaggedError('member', 'This user was not invited to join the organisation');
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    /**
     * @param User $member
     * @param Organisation $organisation
     */
    private function deleteMemberInvitation(User $member, Organisation $organisation)
    {
        $organisationMemberInvitation = new OrganisationMemberInvitation();
        $organisationMemberInvitation->setMember($member);
        $organisationMemberInvitation->setOrganisation($organisation);
        $this->organisationMemberInvitationRepo->remove($organisationMemberInvitation);
    }

    /**
     * @param mixed $userID
     */
    public function setUserID($userID)
    {
        $this->userID = (int)$userID;
    }

    /**
     * @param mixed $organisationID
     */
    public function setOrganisationID($organisationID)
    {
        $this->organisationID = (int)$organisationID;
    }
}