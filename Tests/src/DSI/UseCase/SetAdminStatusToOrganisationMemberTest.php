<?php

use \DSI\UseCase\SetAdminStatusToOrganisationMember;

require_once __DIR__ . '/../../../config.php';

class SetAdminStatusToOrganisationMemberTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\OrganisationMemberRepository */
    private $organisationMemberRepository;

    /** @var \DSI\Repository\OrganisationRepository */
    private $organisationRepository;

    /** @var \DSI\Entity\Organisation */
    private $organisation;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $sysadmin, $owner, $admin, $member;

    public function setUp()
    {
        $this->organisationMemberRepository = new \DSI\Repository\OrganisationMemberRepository();
        $this->organisationRepository = new \DSI\Repository\OrganisationRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->owner = new \DSI\Entity\User();
        $this->userRepo->insert($this->owner);

        $this->admin = new \DSI\Entity\User();
        $this->userRepo->insert($this->admin);

        $this->member = new \DSI\Entity\User();
        $this->userRepo->insert($this->member);

        $this->sysadmin = new \DSI\Entity\User();
        $this->sysadmin->setRole('sys-admin');
        $this->userRepo->insert($this->sysadmin);

        $this->organisation = new \DSI\Entity\Organisation();
        $this->organisation->setOwner($this->owner);
        $this->organisationRepository->insert($this->organisation);

        $this->addMemberToOrganisation($this->organisation, $this->admin);

        $setStatusCmd = new SetAdminStatusToOrganisationMember();
        $setStatusCmd->setMember($this->admin);
        $setStatusCmd->setOrganisation($this->organisation);
        $setStatusCmd->setIsAdmin(true);
        $setStatusCmd->setExecutor($this->owner);
        $setStatusCmd->exec();
    }

    public function tearDown()
    {
        $this->organisationMemberRepository->clearAll();
        $this->organisationRepository->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function executorMustBeSent()
    {
        $setStatusCmd = new SetAdminStatusToOrganisationMember();
        $setStatusCmd->setMember($this->member);
        $setStatusCmd->setOrganisation($this->organisation);
        $setStatusCmd->setIsAdmin(true);

        $this->setExpectedException(InvalidArgumentException::class);
        $setStatusCmd->exec();
    }

    /** @test */
    public function organisationOwnerCanPromoteMemberToAdmin()
    {
        $this->addMemberToOrganisation($this->organisation, $this->member);

        $setStatusCmd = new SetAdminStatusToOrganisationMember();
        $setStatusCmd->setMember($this->member);
        $setStatusCmd->setOrganisation($this->organisation);
        $setStatusCmd->setIsAdmin(true);
        $setStatusCmd->setExecutor($this->owner);
        $setStatusCmd->exec();

        $organisationMember = $this->organisationMemberRepository->getByOrganisationIDAndMemberID(
            $this->organisation->getId(), $this->member->getId()
        );
        $this->assertTrue($organisationMember->isAdmin());
    }

    /** @test */
    public function adminCanPromoteMemberToAdmin()
    {
        $this->addMemberToOrganisation($this->organisation, $this->member);

        $setStatusCmd = new SetAdminStatusToOrganisationMember();
        $setStatusCmd->setMember($this->member);
        $setStatusCmd->setOrganisation($this->organisation);
        $setStatusCmd->setIsAdmin(true);
        $setStatusCmd->setExecutor($this->admin);
        $setStatusCmd->exec();

        $organisationMember = $this->organisationMemberRepository->getByOrganisationIDAndMemberID(
            $this->organisation->getId(), $this->member->getId()
        );
        $this->assertTrue($organisationMember->isAdmin());
    }

    /** @test */
    public function sysAdminCanPromoteMemberToAdmin()
    {
        $this->addMemberToOrganisation($this->organisation, $this->member);

        $setStatusCmd = new SetAdminStatusToOrganisationMember();
        $setStatusCmd->setMember($this->member);
        $setStatusCmd->setOrganisation($this->organisation);
        $setStatusCmd->setIsAdmin(true);
        $setStatusCmd->setExecutor($this->sysadmin);
        $setStatusCmd->exec();

        $organisationMember = $this->organisationMemberRepository->getByOrganisationIDAndMemberID(
            $this->organisation->getId(), $this->member->getId()
        );
        $this->assertTrue($organisationMember->isAdmin());
    }

    /** @test */
    public function successfulRemovalOfMemberAsAdmin()
    {
        $this->addMemberToOrganisation($this->organisation, $this->member);

        $setStatusCmd = new SetAdminStatusToOrganisationMember();
        $setStatusCmd->setMember($this->member);
        $setStatusCmd->setOrganisation($this->organisation);
        $setStatusCmd->setIsAdmin(false);
        $setStatusCmd->setExecutor($this->owner);
        $setStatusCmd->exec();

        $organisationMember = $this->organisationMemberRepository->getByOrganisationIDAndMemberID(
            $this->organisation->getId(), $this->member->getId()
        );
        $this->assertFalse($organisationMember->isAdmin());
    }

    /** @test */
    public function otherUsersCannotSetAdminStatus()
    {
        $this->addMemberToOrganisation($this->organisation, $this->member);

        $e = null;
        $setStatusCmd = new SetAdminStatusToOrganisationMember();
        $setStatusCmd->setMember($this->member);
        $setStatusCmd->setOrganisation($this->organisation);
        $setStatusCmd->setIsAdmin(false);
        $setStatusCmd->setExecutor($this->member);
        try {
            $setStatusCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }

    /** @test */
    public function userIsAutomaticallyAddedAsMember()
    {
        $setStatusCmd = new SetAdminStatusToOrganisationMember();
        $setStatusCmd->setMember($this->member);
        $setStatusCmd->setOrganisation($this->organisation);
        $setStatusCmd->setIsAdmin(true);
        $setStatusCmd->setExecutor($this->sysadmin);
        $setStatusCmd->exec();

        $organisationMember = $this->organisationMemberRepository->getByOrganisationIDAndMemberID(
            $this->organisation->getId(), $this->member->getId()
        );
        $this->assertTrue($organisationMember->isAdmin());
    }


    /**
     * @param \DSI\Entity\Organisation $organisation
     * @param \DSI\Entity\User $user
     */
    private function addMemberToOrganisation(\DSI\Entity\Organisation $organisation, \DSI\Entity\User $user)
    {
        $addMemberToOrganisation = new \DSI\UseCase\AddMemberToOrganisation();
        $addMemberToOrganisation->data()->organisationID = $organisation->getId();
        $addMemberToOrganisation->data()->userID = $user->getId();
        $addMemberToOrganisation->exec();
    }
}