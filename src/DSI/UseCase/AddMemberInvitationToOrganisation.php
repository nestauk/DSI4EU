<?php

namespace DSI\UseCase;

use DSI\Entity\Organisation;
use DSI\Entity\OrganisationMemberInvitation;
use DSI\Entity\User;
use DSI\Repository\OrganisationMemberRepo;
use DSI\Repository\OrganisationMemberInvitationRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class AddMemberInvitationToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationMemberInvitationRepo */
    private $organisationMemberInvitationRepository;

    /** @var OrganisationRepo */
    private $organisationRepository;

    /** @var Organisation */
    private $organisation;

    /** @var User */
    private $user;

    public function __construct()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationMemberInvitationRepository = new OrganisationMemberInvitationRepo();
        $this->organisationRepository = new OrganisationRepoInAPC();
    }

    public function exec()
    {
        $this->checkIfOrganisationAlreadyHasTheMember();
        $this->checkIfUserHasAlreadyBeenInvited();
        $this->addMemberInvitation();
    }

    private function checkIfOrganisationAlreadyHasTheMember()
    {
        if ((new OrganisationMemberRepo())->organisationHasMember($this->organisation, $this->user)) {
            $this->errorHandler->addTaggedError('member', __('This user is already a member of the organisation'));
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function checkIfUserHasAlreadyBeenInvited()
    {
        if ($this->organisationMemberInvitationRepository->userIdHasInvitationToOrganisationId($this->user->getId(), $this->organisation->getId())) {
            $this->errorHandler->addTaggedError('member', __('This user has already been invited to join the organisation'));
            $this->errorHandler->throwIfNotEmpty();
        }
    }

    private function addMemberInvitation()
    {
        $organisationMemberInvitation = new OrganisationMemberInvitation();
        $organisationMemberInvitation->setMember($this->user);
        $organisationMemberInvitation->setOrganisation($this->organisation);
        $this->organisationMemberInvitationRepository->add($organisationMemberInvitation);
    }

    /**
     * @param Organisation $organisation
     */
    public function setOrganisation(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * @param int $organisationID
     */
    public function setOrganisationID(int $organisationID)
    {
        $this->organisation = $this->organisationRepository->getById($organisationID);
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param int $userID
     */
    public function setUserID(int $userID)
    {
        $this->user = (new UserRepo())->getById($userID);
    }
}