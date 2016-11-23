<?php

require_once __DIR__ . '/../../../../config.php';

class UnfollowOrganisationTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\OrganisationFollowRepository */
    private $organisationFollowRepo;

    /** @var \DSI\Repository\OrganisationRepository */
    private $organisationRepo;

    /** @var \DSI\Entity\Organisation */
    private $organisation;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user,
        $user2;

    public function setUp()
    {
        $this->organisationRepo = new \DSI\Repository\OrganisationRepository();
        $this->organisationFollowRepo = new \DSI\Repository\OrganisationFollowRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

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
    public function userCanUnfollowOrganisation()
    {
        $this->followOrganisation($this->user, $this->organisation);

        $this->assertTrue($this->organisationFollowRepo->userFollowsOrganisation(
            $this->user, $this->organisation
        ));

        $exec = new \DSI\UseCase\Organisations\UnfollowOrganisation();
        $exec->setExecutor($this->user);
        $exec->setUser($this->user);
        $exec->setOrganisation($this->organisation);
        $exec->exec();

        $this->assertFalse($this->organisationFollowRepo->userFollowsOrganisation(
            $this->user, $this->organisation
        ));
    }

    /** @test */
    public function userCannotUnfollowOrganisationThatHeDoesntFollow()
    {
        $e = null;

        $exec = new \DSI\UseCase\Organisations\UnfollowOrganisation();
        $exec->setExecutor($this->user);
        $exec->setUser($this->user);
        $exec->setOrganisation($this->organisation);
        try{
            $exec->exec();
        } catch (\DSI\Service\ErrorHandler $e){
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('user'));
        $this->assertFalse($this->organisationFollowRepo->userFollowsOrganisation(
            $this->user, $this->organisation
        ));
    }

    /** @test */
    public function onlySameUserCanUnfollowOrganisation()
    {
        $e = null;

        $exec = new \DSI\UseCase\Organisations\UnfollowOrganisation();
        $exec->setExecutor($this->user2);
        $exec->setUser($this->user);
        $exec->setOrganisation($this->organisation);
        try {
            $exec->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('user'));
    }

    /** @test */
    public function mustHaveExecutor()
    {
        $e = null;
        $exec = new \DSI\UseCase\Organisations\UnfollowOrganisation();
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
        $exec = new \DSI\UseCase\Organisations\UnfollowOrganisation();
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
        $exec = new \DSI\UseCase\Organisations\UnfollowOrganisation();
        $exec->setExecutor($this->user);
        $exec->setOrganisation($this->organisation);
        try {
            $exec->exec();
        } catch (InvalidArgumentException $e) {
        }

        $this->assertNotNull($e);
        $this->assertCount(1, $this->organisationRepo->getAll());
    }

    private function followOrganisation($user, $organisation)
    {
        $exec = new \DSI\UseCase\Organisations\FollowOrganisation();
        $exec->setExecutor($user);
        $exec->setUser($user);
        $exec->setOrganisation($organisation);
        $exec->exec();
    }
}