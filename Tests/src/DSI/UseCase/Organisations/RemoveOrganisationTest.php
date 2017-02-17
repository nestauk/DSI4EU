<?php

require_once __DIR__ . '/../../../../config.php';

class RemoveOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\OrganisationRepository */
    private $organisationRepo;

    /** @var \DSI\Entity\Organisation */
    private $organisation;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $owner,
        $sysadmin,
        $user;

    public function setUp()
    {
        $this->organisationRepo = new \DSI\Repository\OrganisationRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->owner = new \DSI\Entity\User();
        $this->userRepo->insert($this->owner);

        $this->sysadmin = new \DSI\Entity\User();
        $this->sysadmin->setRole('sys-admin');
        $this->userRepo->insert($this->sysadmin);

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);

        $this->organisation = new \DSI\Entity\Organisation();
        $this->organisation->setOwner($this->owner);
        $this->organisationRepo->insert($this->organisation);
    }

    public function tearDown()
    {
        $this->organisationRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function ownerCanRemoveOrganisation()
    {
        $this->assertCount(1, $this->organisationRepo->getAll());

        $exec = new \DSI\UseCase\Organisations\RemoveOrganisation();
        $exec->data()->executor = $this->owner;
        $exec->data()->organisation = $this->organisation;
        $exec->exec();

        $this->assertCount(0, $this->organisationRepo->getAll());
        $this->assertCount(0,
            (new \DSI\Repository\OrganisationMemberRepository())->getByOrganisation($this->organisation)
        );
    }

    /** @test */
    public function sysadminCanRemoveOrganisation()
    {
        $this->assertCount(1, $this->organisationRepo->getAll());

        $exec = new \DSI\UseCase\Organisations\RemoveOrganisation();
        $exec->data()->executor = $this->sysadmin;
        $exec->data()->organisation = $this->organisation;
        $exec->exec();

        $this->assertCount(0, $this->organisationRepo->getAll());
    }

    /** @test */
    public function usersCannotRemoveOrganisation()
    {
        $this->assertCount(1, $this->organisationRepo->getAll());

        $e = null;
        $exec = new \DSI\UseCase\Organisations\RemoveOrganisation();
        $exec->data()->executor = $this->user;
        $exec->data()->organisation = $this->organisation;
        try {
            $exec->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('user'));
        $this->assertCount(1, $this->organisationRepo->getAll());
    }

    /** @test */
    public function mustHaveExecutor()
    {
        $e = null;
        $exec = new \DSI\UseCase\Organisations\RemoveOrganisation();
        $exec->data()->organisation = $this->organisation;
        try {
            $exec->exec();
        } catch (InvalidArgumentException $e) {
        }

        $this->assertNotNull($e);
        $this->assertCount(1, $this->organisationRepo->getAll());
    }

    /** @test */
    public function mustHaveOrganisation()
    {
        $e = null;
        $exec = new \DSI\UseCase\Organisations\RemoveOrganisation();
        $exec->data()->executor = $this->owner;
        try {
            $exec->exec();
        } catch (InvalidArgumentException $e) {
        }

        $this->assertNotNull($e);
        $this->assertCount(1, $this->organisationRepo->getAll());
    }
}