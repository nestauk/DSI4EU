<?php

namespace DSI\UseCase;

use DSI\Entity\Organisation;
use DSI\Entity\OrganisationMember;
use DSI\Entity\User;
use DSI\Repository\OrganisationMemberRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

class SetAdminStatusToOrganisationMember
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationMemberRepository */
    private $organisationMemberRepo;

    /** @var OrganisationRepository */
    private $organisationRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var SetAdminStatusToOrganisationMember_Data */
    private $data;

    public function __construct()
    {
        $this->data = new SetAdminStatusToOrganisationMember_Data();

        $this->errorHandler = new ErrorHandler();
        $this->organisationMemberRepo = new OrganisationMemberRepository();
        $this->organisationRepository = new OrganisationRepository();
        $this->userRepository = new UserRepository();
    }

    public function exec()
    {
        $this->assertDataHasBeenSent();
        $this->assertExecutorCanMakeChanges();
        $this->makeSureUserIsMember();
        $this->setAdminFlag();
    }

    /**
     * @return SetAdminStatusToOrganisationMember_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    private function userCanChangeStatus(): bool
    {
        if ($this->data()->executor->isSysAdmin())
            return true;

        if ($this->data()->organisation->getOwnerID() == $this->data()->executor->getId())
            return true;

        $member = (new OrganisationMemberRepository())->getByMemberIdAndOrganisationId(
            $this->data()->executor->getId(),
            $this->data()->organisation->getId()
        );
        if ($member AND $member->isAdmin())
            return true;

        return false;
    }

    private function makeSureUserIsMember()
    {
        if (!$this->organisationMemberRepo->organisationHasMember($this->data()->organisation->getId(), $this->data()->member->getId())) {
            $addMemberToOrganisation = new AddMemberToOrganisation();
            $addMemberToOrganisation->data()->organisationID = $this->data()->organisation->getId();
            $addMemberToOrganisation->data()->userID = $this->data()->member->getId();
            $addMemberToOrganisation->exec();
        }
    }

    private function setAdminFlag()
    {
        $organisationMember = new OrganisationMember();
        $organisationMember->setMember($this->data()->member);
        $organisationMember->setOrganisation($this->data()->organisation);
        $organisationMember->setIsAdmin($this->data()->isAdmin);
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
        if (!$this->data()->executor)
            throw new \InvalidArgumentException('No executor');
    }
}

class SetAdminStatusToOrganisationMember_Data
{
    /** @var User */
    public $member;

    /** @var Organisation */
    public $organisation;

    /** @var bool */
    public $isAdmin;

    /** @var User */
    public $executor;
}