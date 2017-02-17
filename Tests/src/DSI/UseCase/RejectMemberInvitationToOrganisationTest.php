<?php

require_once __DIR__ . '/../../../config.php';

class RejectMemberInvitationToOrganisationTest extends PHPUnit_Framework_TestCase
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
        $rejectCmd = new \DSI\UseCase\RejectMemberInvitationToOrganisation();
        $this->setExpectedException(InvalidArgumentException::class);
        $rejectCmd->exec();
    }

    /** @test */
    public function cannotRejectAnNonexistentInvitation()
    {
        $e = null;

        $rejectCmd = new \DSI\UseCase\RejectMemberInvitationToOrganisation();
        $rejectCmd->data()->executor = $this->invitedUser;
        $rejectCmd->data()->organisationID = $this->organisation->getId();
        $rejectCmd->data()->userID = $this->invitedUser->getId();
        try {
            $rejectCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }

    /** @test */
    public function onlyTheInvitedUserCanRejectTheInvitation()
    {
        $e = null;
        try {
            $this->addOrganisationMemberInvitation($this->organisation->getId(), $this->invitedUser->getId());

            $rejectCmd = new \DSI\UseCase\RejectMemberInvitationToOrganisation();
            $rejectCmd->data()->executor = $this->organisationOwner;
            $rejectCmd->data()->organisationID = $this->organisation->getId();
            $rejectCmd->data()->userID = $this->invitedUser->getId();
            $rejectCmd->exec();

        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('executor'));
    }

    /** @test */
    public function successfulRejectionOfMemberInvitationToOrganisation_removesTheInvitation()
    {
        try {
            $this->addOrganisationMemberInvitation($this->organisation->getId(), $this->invitedUser->getId());
            $this->rejectInvitation($this->organisation->getId(), $this->invitedUser->getId());
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertFalse(
            $this->organisationMemberInvitationRepo->userIdHasInvitationToOrganisationId($this->invitedUser->getId(), $this->organisation->getId())
        );
    }

    /** @test */
    public function successfulRejectionOfMemberInvitationToOrganisation_doesNotAddMemberToOrganisation()
    {
        $this->addOrganisationMemberInvitation($this->organisation->getId(), $this->invitedUser->getId());
        $this->rejectInvitation($this->organisation->getId(), $this->invitedUser->getId());

        $this->assertFalse(
            $this->organisationMemberRepo->organisationHasMember($this->organisation, $this->invitedUser)
        );
    }

    private function addOrganisationMemberInvitation($organisationID, $userID)
    {
        $this->addMemberInvitationToOrganisation = new \DSI\UseCase\AddMemberInvitationToOrganisation();
        $this->addMemberInvitationToOrganisation->setUserID($userID);
        $this->addMemberInvitationToOrganisation->setOrganisationID($organisationID);
        $this->addMemberInvitationToOrganisation->exec();
    }

    private function rejectInvitation($organisationID, $userID)
    {
        $rejectCmd = new \DSI\UseCase\RejectMemberInvitationToOrganisation();
        $rejectCmd->data()->executor = $this->invitedUser;
        $rejectCmd->data()->organisationID = $organisationID;
        $rejectCmd->data()->userID = $userID;
        $rejectCmd->exec();
    }
}