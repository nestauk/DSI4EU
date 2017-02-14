<?php

require_once __DIR__ . '/../../../config.php';

class ApproveMemberInvitationToOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddMemberInvitationToOrganisation */
    private $addMemberInvitationToOrganisation;

    /** @var \DSI\Repository\OrganisationMemberInvitationRepository */
    private $organisationMemberInvitationRepo;

    /** @var \DSI\Repository\OrganisationMemberRepository */
    private $organisationMemberRepo;

    /** @var \DSI\Repository\OrganisationRepository */
    private $organisationRepo;

    /** @var \DSI\Entity\Organisation */
    private $organisation;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $organisationOwner,
        $invitedUser;

    public function setUp()
    {
        $this->organisationMemberInvitationRepo = new \DSI\Repository\OrganisationMemberInvitationRepository();
        $this->organisationMemberRepo = new \DSI\Repository\OrganisationMemberRepository();
        $this->organisationRepo = new \DSI\Repository\OrganisationRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->organisationOwner = new \DSI\Entity\User();
        $this->userRepo->insert($this->organisationOwner);

        $this->invitedUser = new \DSI\Entity\User();
        $this->userRepo->insert($this->invitedUser);

        $this->organisation = new \DSI\Entity\Organisation();
        $this->organisation->setOwner($this->organisationOwner);
        $this->organisationRepo->insert($this->organisation);
    }

    public function tearDown()
    {
        $this->organisationMemberInvitationRepo->clearAll();
        $this->organisationMemberRepo->clearAll();
        $this->organisationRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function cannotExecuteWithoutAnExecutor()
    {
        $approveCmd = new \DSI\UseCase\AcceptMemberInvitationToOrganisation();
        $this->setExpectedException(InvalidArgumentException::class);
        $approveCmd->exec();
    }

    /** @test */
    public function cannotApproveAnNonexistentInvitation()
    {
        $e = null;

        $approveCmd = new \DSI\UseCase\AcceptMemberInvitationToOrganisation();
        $approveCmd->data()->executor = $this->invitedUser;
        $approveCmd->data()->organisationID = $this->organisation->getId();
        $approveCmd->data()->userID = $this->invitedUser->getId();
        try {
            $approveCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }

    /** @test */
    public function onlyTheInvitedUserCanApproveTheInvitation()
    {
        $e = null;
        try {
            $this->addOrganisationMemberInvitation($this->organisation->getId(), $this->invitedUser->getId());

            $approveCmd = new \DSI\UseCase\AcceptMemberInvitationToOrganisation();
            $approveCmd->data()->executor = $this->organisationOwner;
            $approveCmd->data()->organisationID = $this->organisation->getId();
            $approveCmd->data()->userID = $this->invitedUser->getId();
            $approveCmd->exec();

        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('executor'));
    }

    /** @test */
    public function successfulApprovalOfMemberInvitationToOrganisation_removesTheInvitation()
    {
        try {
            $this->addOrganisationMemberInvitation($this->organisation->getId(), $this->invitedUser->getId());
            $this->approveInvitation($this->organisation->getId(), $this->invitedUser->getId());
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertFalse(
            $this->organisationMemberInvitationRepo->memberHasInvitationToOrganisation($this->invitedUser->getId(), $this->organisation->getId())
        );
    }

    /** @test */
    public function successfulApprovalOfMemberInvitationToOrganisation_addsMemberToOrganisation()
    {
        $this->addOrganisationMemberInvitation($this->organisation->getId(), $this->invitedUser->getId());
        $this->approveInvitation($this->organisation->getId(), $this->invitedUser->getId());

        $this->assertTrue(
            $this->organisationMemberRepo->organisationIDHasMemberID($this->organisation->getId(), $this->invitedUser->getId())
        );
    }

    private function addOrganisationMemberInvitation($organisationID, $userID)
    {
        $this->addMemberInvitationToOrganisation = new \DSI\UseCase\AddMemberInvitationToOrganisation();
        $this->addMemberInvitationToOrganisation->data()->userID = $userID;
        $this->addMemberInvitationToOrganisation->data()->organisationID = $organisationID;
        $this->addMemberInvitationToOrganisation->exec();
    }

    private function approveInvitation($organisationID, $userID)
    {
        $approveCmd = new \DSI\UseCase\AcceptMemberInvitationToOrganisation();
        $approveCmd->data()->executor = $this->invitedUser;
        $approveCmd->data()->organisationID = $organisationID;
        $approveCmd->data()->userID = $userID;
        $approveCmd->exec();
    }
}