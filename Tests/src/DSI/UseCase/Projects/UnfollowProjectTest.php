<?php

require_once __DIR__ . '/../../../../config.php';

class UnfollowProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\ProjectFollowRepository */
    private $projectFollowRepository;

    /** @var \DSI\Repository\ProjectRepository */
    private $projectRepository;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user,
        $user2;

    public function setUp()
    {
        $this->projectRepository = new \DSI\Repository\ProjectRepository();
        $this->projectFollowRepository = new \DSI\Repository\ProjectFollowRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

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
    public function userCanUnfollowProject()
    {
        $this->followProject($this->user, $this->project);

        $this->assertTrue($this->projectFollowRepository->userFollowsProject(
            $this->user, $this->project
        ));

        $exec = new \DSI\UseCase\Projects\UnfollowProject();
        $exec->setExecutor($this->user);
        $exec->setUser($this->user);
        $exec->setProject($this->project);
        $exec->exec();

        $this->assertFalse($this->projectFollowRepository->userFollowsProject(
            $this->user, $this->project
        ));
    }

    /** @test */
    public function userCannotUnfollowProjectThatHeDoesntFollow()
    {
        $e = null;

        $exec = new \DSI\UseCase\Projects\UnfollowProject();
        $exec->setExecutor($this->user);
        $exec->setUser($this->user);
        $exec->setProject($this->project);
        try{
            $exec->exec();
        } catch (\DSI\Service\ErrorHandler $e){
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('user'));
        $this->assertFalse($this->projectFollowRepository->userFollowsProject(
            $this->user, $this->project
        ));
    }

    /** @test */
    public function onlySameUserCanUnfollowProject()
    {
        $e = null;

        $exec = new \DSI\UseCase\Projects\UnfollowProject();
        $exec->setExecutor($this->user2);
        $exec->setUser($this->user);
        $exec->setProject($this->project);
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
        $exec = new \DSI\UseCase\Projects\UnfollowProject();
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
        $exec = new \DSI\UseCase\Projects\UnfollowProject();
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
        $exec = new \DSI\UseCase\Projects\UnfollowProject();
        $exec->setExecutor($this->user);
        $exec->setProject($this->project);
        try {
            $exec->exec();
        } catch (InvalidArgumentException $e) {
        }

        $this->assertNotNull($e);
        $this->assertCount(1, $this->projectRepository->getAll());
    }

    private function followProject($user, $project)
    {
        $exec = new \DSI\UseCase\Projects\FollowProject();
        $exec->setExecutor($user);
        $exec->setUser($user);
        $exec->setProject($project);
        $exec->exec();
    }
}