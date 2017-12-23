<?php

namespace DSI\UseCase;

use DSI\Entity\Organisation;
use DSI\Entity\OrganisationMember;
use DSI\Entity\User;
use DSI\Repository\OrganisationMemberRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class RemoveMemberFromOrganisation
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationMemberRepo */
    private $organisationMemberRepo;

    /** @var Organisation */
    private $organisation;

    /** @var User */
    private $user;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationMemberRepo = new OrganisationMemberRepo();

        if (!$this->organisationMemberRepo->organisationHasMember($this->organisation, $this->user)) {
            $this->errorHandler->addTaggedError('member', 'The user is not a member of the organisation');
            $this->errorHandler->throwIfNotEmpty();
        }

        $organisationMember = new OrganisationMember();
        $organisationMember->setMember($this->user);
        $organisationMember->setOrganisation($this->organisation);
        $this->organisationMemberRepo->remove($organisationMember);
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
        $this->organisation = (new OrganisationRepoInAPC())->getById($organisationID);
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