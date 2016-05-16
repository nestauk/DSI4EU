<?php

require_once __DIR__ . '/../../../config.php';

class RejectMemberRequestToOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddMemberRequestToOrganisation */
    private $addMemberRequestToOrganisation;

    /** @var \DSI\Repository\OrganisationMemberRequestRepository */
    private $organisationMemberRequestRepo;

    /** @var \DSI\Repository\OrganisationMemberRepository */
    private $organisationMemberRepo;

    /** @var \DSI\Repository\OrganisationRepository */
    private $organisationRepo;

    /** @var \DSI\Entity\Organisation */
    private $organisation;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user_1, $user_2;

    public function setUp()
    {
        $this->organisationMemberRequestRepo = new \DSI\Repository\OrganisationMemberRequestRepository();
        $this->organisationMemberRepo = new \DSI\Repository\OrganisationMemberRepository();
        $this->organisationRepo = new \DSI\Repository\OrganisationRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user_1 = new \DSI\Entity\User();
        $this->userRepo->insert($this->user_1);
        $this->user_2 = new \DSI\Entity\User();
        $this->userRepo->insert($this->user_2);

        $this->organisation = new \DSI\Entity\Organisation();
        $this->organisation->setOwner($this->user_1);
        $this->organisationRepo->saveAsNew($this->organisation);
    }

    public function tearDown()
    {
        $this->organisationMemberRequestRepo->clearAll();
        $this->organisationMemberRepo->clearAll();
        $this->organisationRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function cannotRejectAnNonexistentRequest()
    {
        $e = null;

        $rejectCmd = new \DSI\UseCase\RejectMemberRequestToOrganisation();
        $rejectCmd->data()->organisationID = $this->organisation->getId();
        $rejectCmd->data()->userID = $this->user_2->getId();
        try {
            $rejectCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }

    /** @test */
    public function successfulRejectionOfMemberRequestToOrganisation_removesTheRequest()
    {
        $this->addOrganisationMemberRequest($this->organisation->getId(), $this->user_2->getId());
        $this->rejectRequest($this->organisation->getId(), $this->user_2->getId());

        $this->assertFalse(
            $this->organisationMemberRequestRepo->organisationHasRequestFromMember($this->organisation->getId(), $this->user_2->getId())
        );
    }

    /** @test */
    public function successfulRejectionOfMemberRequestToOrganisation_doesNotAddMemberToOrganisation()
    {
        $this->addOrganisationMemberRequest($this->organisation->getId(), $this->user_2->getId());
        $this->rejectRequest($this->organisation->getId(), $this->user_2->getId());

        $this->assertFalse(
            $this->organisationMemberRepo->organisationHasMember($this->organisation->getId(), $this->user_2->getId())
        );
    }


    private function addOrganisationMemberRequest($organisationID, $userID)
    {
        $this->addMemberRequestToOrganisation = new \DSI\UseCase\AddMemberRequestToOrganisation();
        $this->addMemberRequestToOrganisation->data()->userID = $userID;
        $this->addMemberRequestToOrganisation->data()->organisationID = $organisationID;
        $this->addMemberRequestToOrganisation->exec();
    }

    private function rejectRequest($organisationID, $userID)
    {
        $rejectCmd = new \DSI\UseCase\RejectMemberRequestToOrganisation();
        $rejectCmd->data()->organisationID = $organisationID;
        $rejectCmd->data()->userID = $userID;
        $rejectCmd->exec();
    }
}