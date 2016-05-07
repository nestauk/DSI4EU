<?php

require_once __DIR__ . '/../../../config.php';

class CreateProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreateProject */
    private $createProjectCommand;

    /** @var \DSI\Repository\ProjectRepository */
    private $projectRepo;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->createProjectCommand = new \DSI\UseCase\CreateProject();
        $this->projectRepo = new \DSI\Repository\ProjectRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->saveAsNew($this->user);
    }

    public function tearDown()
    {
        (new \DSI\Repository\ProjectRepository())->clearAll();
        (new \DSI\Repository\UserRepository())->clearAll();
    }

    /** @test */
    public function successfulCreation()
    {
        $this->createProjectCommand->data()->name = 'test';
        $this->createProjectCommand->data()->description = 'test';
        $this->createProjectCommand->data()->owner = $this->user;
        $this->createProjectCommand->exec();

        $this->assertCount(1, $this->projectRepo->getAll());

        $this->createProjectCommand->data()->name = 'test2';
        $this->createProjectCommand->data()->description = 'test';
        $this->createProjectCommand->data()->owner = $this->user;
        $this->createProjectCommand->exec();

        $this->assertCount(2, $this->projectRepo->getAll());
    }

    /** @test */
    public function cannotAddWithAnEmptyName()
    {
        $e = null;
        $this->createProjectCommand->data()->name = '';
        $this->createProjectCommand->data()->description = 'test';
        $this->createProjectCommand->data()->owner = $this->user;
        try {
            $this->createProjectCommand->exec();
        } catch(\DSI\Service\ErrorHandler $e){
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('name'));
    }
}