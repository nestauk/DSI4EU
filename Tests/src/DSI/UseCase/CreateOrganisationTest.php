<?php

require_once __DIR__ . '/../../../config.php';

class CreateOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreateOrganisation */
    private $createOrgCmd;

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
        $this->createOrgCmd = new \DSI\UseCase\CreateOrganisation();
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
        $this->createOrgCmd->data()->name = 'test';
        $this->createOrgCmd->data()->description = 'test';
        $this->createOrgCmd->data()->owner = $this->user;
        $this->createOrgCmd->exec();

        $this->assertCount(1, $this->organisationRepo->getAll());

        $this->createOrgCmd->data()->name = 'test2';
        $this->createOrgCmd->data()->description = 'test';
        $this->createOrgCmd->data()->owner = $this->user;
        $this->createOrgCmd->exec();

        $this->assertCount(2, $this->organisationRepo->getAll());
    }

    /** @test */
    public function ownerIsAlsoMemberOfTheOrganisation()
    {
        $this->createOrgCmd->data()->name = 'test';
        $this->createOrgCmd->data()->description = 'test';
        $this->createOrgCmd->data()->owner = $this->user;
        $this->createOrgCmd->exec();
        $organisation = $this->createOrgCmd->getOrganisation();

        $this->assertTrue(
            $this->organisationMemberRepo->organisationHasMember($organisation->getId(), $this->user->getId())
        );
    }

    /** @test */
    public function ownerIsAdminOfTheOrganisation()
    {
        $this->createOrgCmd->data()->name = 'test';
        $this->createOrgCmd->data()->description = 'test';
        $this->createOrgCmd->data()->owner = $this->user;
        $this->createOrgCmd->exec();
        $organisation = $this->createOrgCmd->getOrganisation();

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
        $this->createOrgCmd->data()->name = '';
        $this->createOrgCmd->data()->description = 'test';
        $this->createOrgCmd->data()->owner = $this->user;
        try {
            $this->createOrgCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('name'));
    }
}