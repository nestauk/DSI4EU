<?php

require_once __DIR__ . '/../../../../config.php';

class FollowProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\ProjectFollowRepo */
    private $projectFollowRepository;

    /** @var \DSI\Repository\ProjectRepo */
    private $projectRepository;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user,
        $user2;

    public function setUp()
    {
        $this->projectRepository = new \DSI\Repository\ProjectRepo();
        $this->projectFollowRepository = new \DSI\Repository\ProjectFollowRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);

        $this->user2 = new \DSI\Entity\User();
        $this->userRepo->insert($this->user2);

        $this->project = new \DSI\Entity\Project();
        $this->project->setOwner($this->user);
        $this->projectRepository->insert($this->project);
    }

    public function tearDown()
    {
        $this->projectFollowRepository->clearAll();
        $this->projectRepository->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function userCanFollowProject()
    {
        $this->assertCount(0, $this->projectFollowRepository->getByUser($this->user));

        $exec = new \DSI\UseCase\Projects\FollowProject();
        $exec->setExecutor($this->user);
        $exec->setUser($this->user);
        $exec->setProject($this->project);
        $exec->exec();

        $this->assertCount(1, $this->projectFollowRepository->getByUser($this->user));
    }

    /** @test */
    public function onlySameUserCanFollowProject()
    {
        $this->assertCount(0, $this->projectFollowRepository->getByUser($this->user));
        $e = null;

        $exec = new \DSI\UseCase\Projects\FollowProject();
        $exec->setExecutor($this->user2);
        $exec->setUser($this->user);
        $exec->setProject($this->project);
        try{
            $exec->exec();
        } catch (\DSI\Service\ErrorHandler $e){
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('user'));
        $this->assertCount(0, $this->projectFollowRepository->getByUser($this->user));
    }

    /** @test */
    public function mustHaveExecutor()
    {
        $e = null;
        $exec = new \DSI\UseCase\Projects\FollowProject();
        $exec->setUser($this->user);
        $exec->setProject($this->project);
        try {
            $exec->exec();
        } catch (InvalidArgumentException $e) {
        }

        $this->assertNotNull($e);
        $this->assertCount(1, $this->projectRepository->getAll());
    }

    /** @test */
    public function mustHaveProject()
    {
        $e = null;
        $exec = new \DSI\UseCase\Projects\FollowProject();
        $exec->setExecutor($this->user);
        $exec->setUser($this->user);
        try {
            $exec->exec();
        } catch (InvalidArgumentException $e) {
        }

        $this->assertNotNull($e);
        $this->assertCount(1, $this->projectRepository->getAll());
    }

    /** @test */
    public function mustHaveUser()
    {
        $e = null;
        $exec = new \DSI\UseCase\Projects\FollowProject();
        $exec->setExecutor($this->user);
        $exec->setProject($this->project);
        try {
            $exec->exec();
        } catch (InvalidArgumentException $e) {
        }

        $this->assertNotNull($e);
        $this->assertCount(1, $this->projectRepository->getAll());
    }
}