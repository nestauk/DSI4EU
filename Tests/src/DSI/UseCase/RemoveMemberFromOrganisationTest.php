<?php

require_once __DIR__ . '/../../../config.php';

class RemoveMemberFromOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddMemberToOrganisation */
    private $addMemberToOrganisationCommand;

    /** @var \DSI\UseCase\RemoveMemberFromOrganisation */
    private $removeMemberFromOrganisationCommand;

    /** @var \DSI\Repository\OrganisationMemberRepository */
    private $organisationMemberRepo;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Repository\OrganisationRepository */
    private $organisationRepo;

    /** @var \DSI\Entity\User */
    private $user;

    /** @var \DSI\Entity\Organisation */
    private $organisation;

    public function setUp()
    {
        $this->addMemberToOrganisationCommand = new \DSI\UseCase\AddMemberToOrganisation();
        $this->removeMemberFromOrganisationCommand = new \DSI\UseCase\RemoveMemberFromOrganisation();
        $this->organisationMemberRepo = new \DSI\Repository\OrganisationMemberRepository();
        $this->organisationRepo = new \DSI\Repository\OrganisationRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->saveAsNew($this->user);

        $this->organisation = new \DSI\Entity\Organisation();
        $this->organisation->setOwner($this->user);
        $this->organisationRepo->saveAsNew($this->organisation);
    }

    public function tearDown()
    {
        $this->organisationMemberRepo->clearAll();
        $this->organisationRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function successfulRemoveMemberFromOrganisation()
    {
        $this->addMemberToOrganisationCommand->data()->userID = $this->user->getId();
        $this->addMemberToOrganisationCommand->data()->organisationID = $this->organisation->getId();
        $this->addMemberToOrganisationCommand->exec();

        $this->removeMemberFromOrganisationCommand->data()->userID = $this->user->getId();
        $this->removeMemberFromOrganisationCommand->data()->organisationID = $this->organisation->getId();
        $this->removeMemberFromOrganisationCommand->exec();

        $this->assertFalse(
            $this->organisationMemberRepo->organisationHasMember(
                $this->removeMemberFromOrganisationCommand->data()->organisationID,
                $this->removeMemberFromOrganisationCommand->data()->userID
            )
        );
    }

    /** @test */
    public function cannotRemoveIfUserIsNotAMember()
    {
        $e = null;

        try {
            $this->removeMemberFromOrganisationCommand->data()->userID = $this->user->getId();
            $this->removeMemberFromOrganisationCommand->data()->organisationID = $this->organisation->getId();
            $this->removeMemberFromOrganisationCommand->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('member'));
    }
}