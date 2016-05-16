<?php

require_once __DIR__ . '/../../../config.php';

class AddMemberRequestToOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddMemberRequestToOrganisation */
    private $addMemberRequestToOrganisation;

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
        $this->addMemberRequestToOrganisation = new \DSI\UseCase\AddMemberRequestToOrganisation();
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
        $this->organisationRepo->clearAll();
        $this->userRepo->clearAll();
        (new \DSI\Repository\OrganisationMemberRequestRepository())->clearAll();
    }

    /** @test */
    public function successfulAdditionOfMemberToOrganisation()
    {
        $this->addMemberRequestToOrganisation->data()->userID = $this->user_2->getId();
        $this->addMemberRequestToOrganisation->data()->organisationID = $this->organisation->getId();
        $this->addMemberRequestToOrganisation->exec();

        $this->assertTrue(
            (new \DSI\Repository\OrganisationMemberRequestRepository())->organisationHasRequestFromMember(
                $this->addMemberRequestToOrganisation->data()->organisationID,
                $this->addMemberRequestToOrganisation->data()->userID
            )
        );
    }

    /** @test */
    public function cannotAddSameMemberTwice()
    {
        $e = null;
        $this->addMemberRequestToOrganisation->data()->userID = $this->user_2->getId();
        $this->addMemberRequestToOrganisation->data()->organisationID = $this->organisation->getId();
        $this->addMemberRequestToOrganisation->exec();

        try {
            $this->addMemberRequestToOrganisation->data()->userID = $this->user_2->getId();
            $this->addMemberRequestToOrganisation->data()->organisationID = $this->organisation->getId();
            $this->addMemberRequestToOrganisation->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }
}