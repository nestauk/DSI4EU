<?php

require_once __DIR__ . '/../../../config.php';

class CreateOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreateOrganisation */
    private $createOrganisationCommand;

    /** @var \DSI\Repository\OrganisationRepository */
    private $organisationRepo;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->createOrganisationCommand = new \DSI\UseCase\CreateOrganisation();
        $this->organisationRepo = new \DSI\Repository\OrganisationRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->saveAsNew($this->user);
    }

    public function tearDown()
    {
        (new \DSI\Repository\OrganisationRepository())->clearAll();
        (new \DSI\Repository\UserRepository())->clearAll();
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
    public function cannotAddWithAnEmptyName()
    {
        $e = null;
        $this->createOrganisationCommand->data()->name = '';
        $this->createOrganisationCommand->data()->description = 'test';
        $this->createOrganisationCommand->data()->owner = $this->user;
        try {
            $this->createOrganisationCommand->exec();
        } catch(\DSI\Service\ErrorHandler $e){
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('name'));
    }
}