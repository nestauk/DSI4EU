<?php

require_once __DIR__ . '/../../../config.php';

class CreateProjectPostTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreateProjectPost */
    private $createPostCmd;

    /** @var \DSI\Repository\ProjectPostRepository */
    private $projectPostRepo;

    /** @var \DSI\Repository\ProjectRepository */
    private $projectRepo;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Entity\User */
    private $user, $user2;

    public function setUp()
    {
        $this->createPostCmd = new \DSI\UseCase\CreateProjectPost();
        $this->projectPostRepo = new \DSI\Repository\ProjectPostRepository();
        $this->projectRepo = new \DSI\Repository\ProjectRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);
        $this->user2 = new \DSI\Entity\User();
        $this->userRepo->insert($this->user2);

        $this->project= new \DSI\Entity\Project();
        $this->project->setOwner($this->user);
        $this->projectRepo->insert($this->project);
    }

    public function tearDown()
    {
        $this->projectPostRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function successfulCreation()
    {
        $this->createPostCmd->data()->project = $this->project;
        $this->createPostCmd->data()->user = $this->user;
        $this->createPostCmd->data()->title = 'Post Title';
        $this->createPostCmd->data()->text = 'Post Text';
        $this->createPostCmd->exec();

        $this->assertCount(1, $this->projectPostRepo->getAll());

        $this->createPostCmd->data()->project = $this->project;
        $this->createPostCmd->data()->user = $this->user;
        $this->createPostCmd->data()->title = 'Post Title';
        $this->createPostCmd->data()->text = 'Post Text';
        $this->createPostCmd->exec();

        $this->assertCount(2, $this->projectPostRepo->getAll());
    }

    /** @test */
    public function onlyTheOwnerCanAddAPost()
    {
        $e = null;
        $this->createPostCmd->data()->project = $this->project;
        $this->createPostCmd->data()->user = $this->user2;
        $this->createPostCmd->data()->title = 'Post Title';
        $this->createPostCmd->data()->text = 'Post Text';
        try {
            $this->createPostCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('user'));
    }
}