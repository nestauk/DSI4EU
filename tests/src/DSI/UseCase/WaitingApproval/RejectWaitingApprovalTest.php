<?php

require_once __DIR__ . '/../../../../config.php';

class RejectWaitingApprovalTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\ProjectRepo */
    private $projectRepo;

    /** @var \DSI\Repository\OrganisationRepo */
    private $orgRepo;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->projectRepo = new \DSI\Repository\ProjectRepo();
        $this->orgRepo = new \DSI\Repository\OrganisationRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

        $this->user = new \DSI\Entity\User();
        $this->user->setFirstName('FName');
        $this->user->setLastName('LName');
        $this->user->setEmail('user@example.org');
        $this->user->setRole('sys-admin');
        $this->userRepo->insert($this->user);
    }

    public function tearDown()
    {
        $this->orgRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
        (new \DSI\Repository\ProjectMemberRepo())->clearAll();
        (new \DSI\Repository\OrganisationMemberRepo())->clearAll();
        (new \DSI\Repository\ContentUpdateRepo())->clearAll();
    }

    /** @test */
    public function canRejectWaitingApprovalProject()
    {
        (new \DSI\UseCase\CreateProject())
            ->setOwner($this->user)
            ->setName('Test')
            ->setDescription('Test')
            ->exec();

        $projects = $this->projectRepo
            ->getAll();
        $contentUpdates = (new \DSI\Repository\ContentUpdateRepo())
            ->getAll();

        $this->assertCount(1, $projects);
        $this->assertCount(1, $contentUpdates);
        $this->assertTrue($projects[0]->isWaitingApproval());

        (new \DSI\UseCase\WaitingApproval\RejectWaitingApproval())
            ->setExecutor($this->user)
            ->setContentUpdate($contentUpdates[0])
            ->exec();

        $projects = $this->projectRepo
            ->getAll();
        $contentUpdates = (new \DSI\Repository\ContentUpdateRepo())
            ->getAll();

        $this->assertCount(0, $projects);
        $this->assertCount(0, $contentUpdates);
    }

    /** @test */
    public function canApproveWaitingApprovalOrganisation()
    {
        (new \DSI\UseCase\CreateOrganisation())
            ->setOwner($this->user)
            ->setName('Test')
            ->setDescription('Test')
            ->exec();

        $organisations = $this->orgRepo
            ->getAll();
        $contentUpdates = (new \DSI\Repository\ContentUpdateRepo())
            ->getAll();

        $this->assertCount(1, $organisations);
        $this->assertCount(1, $contentUpdates);
        $this->assertTrue($organisations[0]->isWaitingApproval());

        (new \DSI\UseCase\WaitingApproval\RejectWaitingApproval())
            ->setExecutor($this->user)
            ->setContentUpdate($contentUpdates[0])
            ->exec();

        $organisations = $this->orgRepo
            ->getAll();
        $contentUpdates = (new \DSI\Repository\ContentUpdateRepo())
            ->getAll();

        $this->assertCount(0, $organisations);
        $this->assertCount(0, $contentUpdates);
    }
}