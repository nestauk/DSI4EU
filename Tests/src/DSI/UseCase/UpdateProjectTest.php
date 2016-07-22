<?php

require_once __DIR__ . '/../../../config.php';

use DSI\Service\ErrorHandler;
use DSI\Repository\ProjectRepository;

class UpdateProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\UpdateProject */
    private $updateProject;

    /** @var ProjectRepository */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Entity\User */
    private $user1, $user2;

    public function setUp()
    {
        $this->updateProject = new \DSI\UseCase\UpdateProject();
        $this->projectRepo = new ProjectRepository();

        $this->user1 = new \DSI\Entity\User();
        $userRepo = new \DSI\Repository\UserRepository();
        $userRepo->insert($this->user1);

        $this->user2 = new \DSI\Entity\User();
        $userRepo = new \DSI\Repository\UserRepository();
        $userRepo->insert($this->user2);

        $createProject = new \DSI\UseCase\CreateProject();
        $createProject->data()->name = 'Project Name';
        $createProject->data()->owner = $this->user1;
        $createProject->exec();

        $this->project = $createProject->getProject();
    }

    public function tearDown()
    {
        $this->projectRepo->clearAll();
        (new \DSI\Repository\ProjectMemberRepository())->clearAll();
    }

    /** @test */
    public function successfulUpdate()
    {
        $name = 'Name';
        $description = 'Description';
        $url = 'http://example.org';

        try {
            $this->updateProject->data()->name = $name;
            $this->updateProject->data()->description = $description;
            $this->updateProject->data()->url = $url;

            $this->updateProject->data()->project = $this->project;
            $this->updateProject->data()->executor = $this->user1;

            $this->updateProject->exec();
        } catch (ErrorHandler $e) {
            $this->assertNull($e);
        }

        $project = $this->projectRepo->getById($this->project->getId());
        $this->assertEquals($name, $project->getName());
        $this->assertEquals($description, $project->getDescription());
        $this->assertEquals($url, $project->getUrl());
        $this->assertEquals($this->user1->getId(), $project->getOwner()->getId());
    }
}