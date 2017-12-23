<?php

require_once __DIR__ . '/../../../../config.php';

class RemoveProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\ProjectRepo */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $owner,
        $sysadmin,
        $user;

    public function setUp()
    {
        $this->projectRepo = new \DSI\Repository\ProjectRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

        $this->owner = new \DSI\Entity\User();
        $this->userRepo->insert($this->owner);

        $this->sysadmin = new \DSI\Entity\User();
        $this->sysadmin->setRole('sys-admin');
        $this->userRepo->insert($this->sysadmin);

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);

        $this->project = new \DSI\Entity\Project();
        $this->project->setOwner($this->owner);
        $this->projectRepo->insert($this->project);
    }

    public function tearDown()
    {
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function ownerCanRemoveProject()
    {
        $this->assertCount(1, $this->projectRepo->getAll());

        $exec = new \DSI\UseCase\Projects\RemoveProject();
        $exec->data()->executor = $this->owner;
        $exec->data()->project = $this->project;
        $exec->exec();

        $this->assertCount(0, $this->projectRepo->getAll());
        $this->assertCount(0,
            (new \DSI\Repository\ProjectMemberRepo())->getByProject($this->project)
        );
    }

    /** @test */
    public function sysadminCanRemoveProject()
    {
        $this->assertCount(1, $this->projectRepo->getAll());

        $exec = new \DSI\UseCase\Projects\RemoveProject();
        $exec->data()->executor = $this->sysadmin;
        $exec->data()->project = $this->project;
        $exec->exec();

        $this->assertCount(0, $this->projectRepo->getAll());
    }

    /** @test */
    public function usersCannotRemoveProject()
    {
        $this->assertCount(1, $this->projectRepo->getAll());

        $e = null;
        $exec = new \DSI\UseCase\Projects\RemoveProject();
        $exec->data()->executor = $this->user;
        $exec->data()->project = $this->project;
        try {
            $exec->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('user'));
        $this->assertCount(1, $this->projectRepo->getAll());
    }

    /** @test */
    public function mustHaveExecutor()
    {
        $e = null;
        $exec = new \DSI\UseCase\Projects\RemoveProject();
        $exec->data()->project = $this->project;
        try {
            $exec->exec();
        } catch (InvalidArgumentException $e) {
        }

        $this->assertNotNull($e);
        $this->assertCount(1, $this->projectRepo->getAll());
    }

    /** @test */
    public function mustHaveProject()
    {
        $e = null;
        $exec = new \DSI\UseCase\Projects\RemoveProject();
        $exec->data()->executor = $this->owner;
        try {
            $exec->exec();
        } catch (InvalidArgumentException $e) {
        }

        $this->assertNotNull($e);
        $this->assertCount(1, $this->projectRepo->getAll());
    }
}