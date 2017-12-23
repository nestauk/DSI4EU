<?php

namespace DSI\UseCase;

use DSI\Entity\Organisation;
use DSI\Entity\OrganisationMember;
use DSI\Entity\User;
use DSI\Repository\OrganisationMemberRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;

class SetAdminStatusToOrganisationMember
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationMemberRepo */
    private $organisationMemberRepo;

    /** @var User */
    private $executor,
        $member;

    /** @var Organisation */
    private $organisation;

    /** @var  boolean */
    private $isAdmin;

    public function __construct()
    {
        $this->errorHandler = new ErrorHandler();
        $this->organisationMemberRepo = new OrganisationMemberRepo();
    }

    public function exec()
    {
        $this->assertDataHasBeenSent();
        $this->assertExecutorCanMakeChanges();
        $this->makeSureUserIsMember();
        $this->setAdminFlag();
    }

    /**
     * @return bool
     */
    private function userCanChangeStatus(): bool
    {
        if ($this->executor->isSysAdmin())
            return true;

        if ($this->organisation->getOwnerID() == $this->executor->getId())
            return true;

        $member = (new OrganisationMemberRepo())->getByMemberIdAndOrganisationId(
            $this->executor->getId(),
            $this->organisation->getId()
        );
        if ($member AND $member->isAdmin())
            return true;

        return false;
    }

    private function makeSureUserIsMember()
    {
        if (!$this->organisationMemberRepo->organisationHasMember($this->organisation, $this->member)) {
            $addMemberToOrganisation = new AddMemberToOrganisation();
            $addMemberToOrganisation->data()->organisationID = $this->organisation->getId();
            $addMemberToOrganisation->data()->userID = $this->member->getId();
            $addMemberToOrganisation->exec();
        }
    }

    private function setAdminFlag()
    {
        $organisationMember = new OrganisationMember();
        $organisationMember->setMember($this->member);
        $organisationMember->setOrganisation($this->organisation);
        $organisationMember->setIsAdmin($this->isAdmin);
        $this->organisationMemberRepo->save($organisationMember);
    }

    private function assertExecutorCanMakeChanges()
    {
        if (!$this->userCanChangeStatus()) {
            $this->errorHandler->addTaggedError('member', 'You cannot change member status');
            throw $this->errorHandler;
        }
    }

    private function assertDataHasBeenSent()
    {
        if (!$this->executor)
            throw new \InvalidArgumentException('No executor');
    }

    /**
     * @param User $executor
     */
    public function setExecutor(User $executor)
    {
        $this->executor = $executor;
    }

    /**
     * @param User $member
     */
    public function setMember(User $member)
    {
        $this->member = $member;
    }

    /**
     * @param int $memberID
     */
    public function setMemberID(int $memberID)
    {
        $this->member = (new UserRepo())->getById($memberID);
    }

    /**
     * @param Organisation $organisation
     */
    public function setOrganisation(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin(bool $isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }
}