<?php

require_once __DIR__ . '/../../../config.php';

class CreateOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreateOrganisation */
    private $createOrgCmd;

    /** @var \DSI\Repository\OrganisationMemberRepo */
    private $organisationMemberRepo;

    /** @var \DSI\Repository\OrganisationRepo */
    private $organisationRepo;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->createOrgCmd = new \DSI\UseCase\CreateOrganisation();
        $this->organisationMemberRepo = new \DSI\Repository\OrganisationMemberRepo();
        $this->organisationRepo = new \DSI\Repository\OrganisationRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

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
    public function cannotCreateOrganisation()
    {
        $canCreateProjects = \Services\App::canCreateProjects();
        \Services\App::setCanCreateProjects(false);

        $this->createOrgCmd->data()->name = 'test';
        $this->createOrgCmd->data()->description = 'test';
        $this->createOrgCmd->data()->owner = $this->user;

        $e = null;
        try {
            $this->createOrgCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('name'));

        \Services\App::setCanCreateProjects($canCreateProjects);
    }

    /** @ test */
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

    /** @ test */
    public function ownerIsAlsoMemberOfTheOrganisation()
    {
        $this->createOrgCmd->data()->name = 'test';
        $this->createOrgCmd->data()->description = 'test';
        $this->createOrgCmd->data()->owner = $this->user;
        $this->createOrgCmd->exec();
        $organisation = $this->createOrgCmd->getOrganisation();

        $this->assertTrue(
            $this->organisationMemberRepo->organisationHasMember($organisation, $this->user)
        );
    }

    /** @ test */
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

    /** @ test */
    public function organisationIsWaitingApprovalOnCreation()
    {
        $this->createOrgCmd->data()->name = 'test';
        $this->createOrgCmd->data()->description = 'test';
        $this->createOrgCmd->data()->owner = $this->user;
        $this->createOrgCmd->exec();
        $organisation = $this->createOrgCmd->getOrganisation();

        $organisation = (new \DSI\Repository\OrganisationRepo())
            ->getById($organisation->getId());

        $this->assertTrue($organisation->isWaitingApproval());
    }

    /** @ test */
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