<?php

namespace DSI\UseCase;

use DSI\Entity\Organisation;
use DSI\Entity\OrganisationMemberInvitation;
use DSI\Entity\User;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationMemberInvitationRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\OrganisationRepositoryInAPC;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class AddMemberInvitationToOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationMemberInvitationRepository */
    private $organisationMemberInvitationRepository;

    /** @var OrganisationRepository */
    private $organisationRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var Organisation */
    private $organisation;

    /** @var User */
    private $user;


    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationMemberInvitationRepository = new OrganisationMemberInvitationRepository();
        $this->organisationRepository = new OrganisationRepositoryInAPC();

        $this->checkIfOrganisationAlreadyHasTheMember();
        $this->checkIfUserHasAlreadyBeenInvited();
        $this->addMemberInvitation();
    }

    private function checkIfOrganisationAlreadyHasTheMember()
    {
        if ((new OrganisationMemberRepository())->organisationHasMember($this->organisation, $this->user)) {
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
        $this->user = (new UserRepository())->getById($userID);
    }
}