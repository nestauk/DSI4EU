<?php

namespace DSI\UseCase\Organisations;

use DSI\Entity\Organisation;
use DSI\Entity\User;
use DSI\Repository\OrganisationMemberRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationRepoInAPC;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;
use DSI\UseCase\AddMemberToOrganisation;

class ChangeOwner
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var OrganisationMemberRepo */
    private $organisationMemberRepo;

    /** @var OrganisationRepo */
    private $organisationRepository;

    /** @var UserRepo */
    private $userRepository;

    /** @var ChangeOwner_Data */
    private $data;

    public function __construct()
    {
        $this->data = new ChangeOwner_Data();

        $this->errorHandler = new ErrorHandler();
        $this->organisationMemberRepo = new OrganisationMemberRepo();
        $this->organisationRepository = new OrganisationRepoInAPC();
        $this->userRepository = new UserRepo();
    }

    public function exec()
    {
        $this->assertDataHasBeenSent();
        $this->assertExecutorCanMakeChanges();
        $this->makeSureUserIsMember();
        $this->setOwner();
    }

    /**
     * @return ChangeOwner_Data
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

        return false;
    }

    private function makeSureUserIsMember()
    {
        if (!$this->organisationMemberRepo->organisationHasMember($this->data()->organisation, $this->data()->member)) {
            $addMemberToOrganisation = new AddMemberToOrganisation();
            $addMemberToOrganisation->data()->organisationID = $this->data()->organisation->getId();
            $addMemberToOrganisation->data()->userID = $this->data()->member->getId();
            $addMemberToOrganisation->exec();
        }
    }

    private function setOwner()
    {
        $this->data()->organisation->setOwner($this->data()->member);
        $this->organisationRepository->save($this->data()->organisation);
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

class ChangeOwner_Data
{
    /** @var User */
    public $member;

    /** @var Organisation */
    public $organisation;

    /** @var User */
    public $executor;
}