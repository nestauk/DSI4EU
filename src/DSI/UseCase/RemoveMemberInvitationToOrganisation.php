<?php

namespace DSI\UseCase;

use DSI\Entity\Organisation;
use DSI\Entity\OrganisationMemberInvitation;
use DSI\Entity\User;
use DSI\Repository\OrganisationMemberInvitationRepo;
use DSI\Repository\OrganisationMemberRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class RemoveMemberInvitationToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationMemberInvitationRepo */
    private $organisationMemberInvitationRepo;

    /** @var OrganisationMemberRepo */
    private $organisationMemberRepo;

    /** @var OrganisationRepo */
    private $organisationRepo;

    /** @var UserRepo */
    private $userRepository;

    private $userID,
        $organisationID;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationMemberInvitationRepo = new OrganisationMemberInvitationRepo();
        $this->organisationMemberRepo = new OrganisationMemberRepo();
        $this->organisationRepo = new OrganisationRepoInAPC();
        $this->userRepository = new UserRepo();

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