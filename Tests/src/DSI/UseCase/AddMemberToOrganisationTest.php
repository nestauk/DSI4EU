<?php

require_once __DIR__ . '/../../../config.php';

class AddMemberToOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddMemberToOrganisation */
    private $addMemberToOrganisation;

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
        $this->addMemberToOrganisation = new \DSI\UseCase\AddMemberToOrganisation();
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
        (new \DSI\Repository\OrganisationMemberRepository())->clearAll();
    }

    /** @test */
    public function successfulAdditionOfMemberToOrganisation()
    {
        $this->addMemberToOrganisation->data()->userID = $this->user_2->getId();
        $this->addMemberToOrganisation->data()->organisationID = $this->organisation->getId();
        $this->addMemberToOrganisation->exec();

        $this->assertTrue(
            (new \DSI\Repository\OrganisationMemberRepository())->organisationHasMember(
                $this->addMemberToOrganisation->data()->organisationID,
                $this->addMemberToOrganisation->data()->userID
            )
        );
    }

    /** @test */
    public function cannotAddSameMemberTwice()
    {
        $e = null;
        $this->addMemberToOrganisation->data()->userID = $this->user_2->getId();
        $this->addMemberToOrganisation->data()->organisationID = $this->organisation->getId();
        $this->addMemberToOrganisation->exec();

        try {
            $this->addMemberToOrganisation->data()->userID = $this->user_2->getId();
            $this->addMemberToOrganisation->data()->organisationID = $this->organisation->getId();
            $this->addMemberToOrganisation->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }
}