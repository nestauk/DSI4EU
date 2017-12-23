<?php

require_once __DIR__ . '/../../../../config.php';

class FollowOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\OrganisationFollowRepo */
    private $organisationFollowRepo;

    /** @var \DSI\Repository\OrganisationRepo */
    private $organisationRepo;

    /** @var \DSI\Entity\Organisation */
    private $organisation;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user,
        $user2;

    public function setUp()
    {
        $this->organisationRepo = new \DSI\Repository\OrganisationRepo();
        $this->organisationFollowRepo = new \DSI\Repository\OrganisationFollowRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);

        $this->user2 = new \DSI\Entity\User();
        $this->userRepo->insert($this->user2);

        $this->organisation = new \DSI\Entity\Organisation();
        $this->organisation->setOwner($this->user);
        $this->organisationRepo->insert($this->organisation);
    }

    public function tearDown()
    {
        $this->organisationFollowRepo->clearAll();
        $this->organisationRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function userCanFollowOrganisation()
    {
        $this->assertCount(0, $this->organisationFollowRepo->getByUser($this->user));

        $exec = new \DSI\UseCase\Organisations\FollowOrganisation();
        $exec->setExecutor($this->user);
        $exec->setUser($this->user);
        $exec->setOrganisation($this->organisation);
        $exec->exec();

        $this->assertCount(1, $this->organisationFollowRepo->getByUser($this->user));
    }

    /** @test */
    public function onlySameUserCanFollowOrganisation()
    {
        $this->assertCount(0, $this->organisationFollowRepo->getByUser($this->user));
        $e = null;

        $exec = new \DSI\UseCase\Organisations\FollowOrganisation();
        $exec->setExecutor($this->user2);
        $exec->setUser($this->user);
        $exec->setOrganisation($this->organisation);
        try{
            $exec->exec();
        } catch (\DSI\Service\ErrorHandler $e){
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('user'));
        $this->assertCount(0, $this->organisationFollowRepo->getByUser($this->user));
    }

    /** @test */
    public function mustHaveExecutor()
    {
        $e = null;
        $exec = new \DSI\UseCase\Organisations\FollowOrganisation();
        $exec->setUser($this->user);
        $exec->setOrganisation($this->organisation);
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
        $exec = new \DSI\UseCase\Organisations\FollowOrganisation();
        $exec->setExecutor($this->user);
        $exec->setUser($this->user);
        try {
            $exec->exec();
        } catch (InvalidArgumentException $e) {
        }

        $this->assertNotNull($e);
        $this->assertCount(1, $this->organisationRepo->getAll());
    }

    /** @test */
    public function mustHaveUser()
    {
        $e = null;
        $exec = new \DSI\UseCase\Organisations\FollowOrganisation();
        $exec->setExecutor($this->user);
        $exec->setOrganisation($this->organisation);
        try {
            $exec->exec();
        } catch (InvalidArgumentException $e) {
        }

        $this->assertNotNull($e);
        $this->assertCount(1, $this->organisationRepo->getAll());
    }
}