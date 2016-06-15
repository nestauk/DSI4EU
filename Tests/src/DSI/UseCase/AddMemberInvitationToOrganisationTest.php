<?php

require_once __DIR__ . '/../../../config.php';

class AddMemberInvitationToOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddMemberInvitationToOrganisation */
    private $addMemberInvitationToOrganisation;

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
        $this->addMemberInvitationToOrganisation = new \DSI\UseCase\AddMemberInvitationToOrganisation();
        $this->organisationRepo = new \DSI\Repository\OrganisationRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user_1 = new \DSI\Entity\User();
        $this->userRepo->insert($this->user_1);
        $this->user_2 = new \DSI\Entity\User();
        $this->userRepo->insert($this->user_2);

        $this->organisation = new \DSI\Entity\Organisation();
        $this->organisation->setOwner($this->user_1);
        $this->organisationRepo->insert($this->organisation);
    }

    public function tearDown()
    {
        $this->organisationRepo->clearAll();
        $this->userRepo->clearAll();
        (new \DSI\Repository\OrganisationMemberInvitationRepository())->clearAll();
    }

    /** @test */
    public function successfulAdditionOfMemberToOrganisation()
    {
        $this->addMemberInvitationToOrganisation->data()->userID = $this->user_2->getId();
        $this->addMemberInvitationToOrganisation->data()->organisationID = $this->organisation->getId();
        $this->addMemberInvitationToOrganisation->exec();

        $this->assertTrue(
            (new \DSI\Repository\OrganisationMemberInvitationRepository())->memberHasInvitationToOrganisation(
                $this->addMemberInvitationToOrganisation->data()->userID,
                $this->addMemberInvitationToOrganisation->data()->organisationID
            )
        );
    }

    /** @test */
    public function cannotAddSameMemberTwice()
    {
        $e = null;
        $this->addMemberInvitationToOrganisation->data()->userID = $this->user_2->getId();
        $this->addMemberInvitationToOrganisation->data()->organisationID = $this->organisation->getId();
        $this->addMemberInvitationToOrganisation->exec();

        try {
            $this->addMemberInvitationToOrganisation->data()->userID = $this->user_2->getId();
            $this->addMemberInvitationToOrganisation->data()->organisationID = $this->organisation->getId();
            $this->addMemberInvitationToOrganisation->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }
}