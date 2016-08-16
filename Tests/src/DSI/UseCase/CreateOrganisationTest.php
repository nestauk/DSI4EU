<?php

require_once __DIR__ . '/../../../config.php';

class CreateOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreateOrganisation */
    private $createOrganisationCommand;

    /** @var \DSI\Repository\OrganisationMemberRepository */
    private $organisationMemberRepo;

    /** @var \DSI\Repository\OrganisationRepository */
    private $organisationRepo;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->createOrganisationCommand = new \DSI\UseCase\CreateOrganisation();
        $this->organisationMemberRepo = new \DSI\Repository\OrganisationMemberRepository();
        $this->organisationRepo = new \DSI\Repository\OrganisationRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);
    }

    public function tearDown()
    {
        $this->organisationMemberRepo->clearAll();
        $this->organisationRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function successfulCreation()
    {
        $this->createOrganisationCommand->data()->name = 'test';
        $this->createOrganisationCommand->data()->description = 'test';
        $this->createOrganisationCommand->data()->owner = $this->user;
        $this->createOrganisationCommand->exec();

        $this->assertCount(1, $this->organisationRepo->getAll());

        $this->createOrganisationCommand->data()->name = 'test2';
        $this->createOrganisationCommand->data()->description = 'test';
        $this->createOrganisationCommand->data()->owner = $this->user;
        $this->createOrganisationCommand->exec();

        $this->assertCount(2, $this->organisationRepo->getAll());
    }

    /** @test */
    public function ownerIsAlsoMemberOfTheOrganisation()
    {
        $this->createOrganisationCommand->data()->name = 'test';
        $this->createOrganisationCommand->data()->description = 'test';
        $this->createOrganisationCommand->data()->owner = $this->user;
        $this->createOrganisationCommand->exec();
        $organisation = $this->createOrganisationCommand->getOrganisation();

        $this->assertTrue(
            $this->organisationMemberRepo->organisationHasMember($organisation->getId(), $this->user->getId())
        );
    }

    /** @test */
    public function ownerIsAdminOfTheOrganisation()
    {
        $this->createOrganisationCommand->data()->name = 'test';
        $this->createOrganisationCommand->data()->description = 'test';
        $this->createOrganisationCommand->data()->owner = $this->user;
        $this->createOrganisationCommand->exec();
        $organisation = $this->createOrganisationCommand->getOrganisation();

        $this->assertTrue(
            $this->organisationMemberRepo->organisationHasMember(
                $organisation->getId(), $this->user->getId()
            )
        );
        $this->assertTrue(
            $this->organisationMemberRepo->getByMemberIdAndOrganisationId(
                $this->user->getId(), $organisation->getId()
            )->isAdmin()
        );
    }

    /** @test */
    public function cannotAddWithAnEmptyName()
    {
        $e = null;
        $this->createOrganisationCommand->data()->name = '';
        $this->createOrganisationCommand->data()->description = 'test';
        $this->createOrganisationCommand->data()->owner = $this->user;
        try {
            $this->createOrganisationCommand->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('name'));
    }
}