<?php

require_once __DIR__ . '/../../../config.php';

use DSI\Service\ErrorHandler;
use DSI\Repository\ProjectRepo;

class UpdateProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\UpdateProject */
    private $updateProject;

    /** @var ProjectRepo */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    /** @var \DSI\Entity\User */
    private $user1, $user2;

    public function setUp()
    {
        $this->updateProject = new \DSI\UseCase\UpdateProject();
        $this->projectRepo = new ProjectRepo();

        $this->user1 = new \DSI\Entity\User();
        $userRepo = new \DSI\Repository\UserRepo();
        $userRepo->insert($this->user1);

        $this->user2 = new \DSI\Entity\User();
        $userRepo = new \DSI\Repository\UserRepo();
        $userRepo->insert($this->user2);

        $createProject = new \DSI\UseCase\CreateProject();
        $createProject->data()->name = 'Project Name';
        $createProject->data()->owner = $this->user1;
        $createProject->forceCreation = true;
        $createProject->exec();

        $this->project = $createProject->getProject();
    }

    public function tearDown()
    {
        $this->projectRepo->clearAll();
        (new \DSI\Repository\ProjectMemberRepo())->clearAll();
        (new \DSI\Repository\ContentUpdateRepo())->clearAll();
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
        $this->assertEquals($this->user1->getId(), $project->getOwnerID());
    }

    /** @test */
    public function changingNameCreatesContentUpdate()
    {
        $this->deleteFirstContentUpdate();

        try {
            $this->updateProject->data()->name = 'New Name';
            $this->updateProject->data()->project = $this->project;
            $this->updateProject->data()->executor = $this->user1;
            $this->updateProject->exec();
        } catch (ErrorHandler $e) {
            $this->assertNull($e);
        }

        $contentUpdates = (new \DSI\Repository\ContentUpdateRepo())
            ->getAll();

        $this->assertCount(1, $contentUpdates);

        $contentUpdate = $contentUpdates[0];

        $this->assertEquals($this->project->getId(), $contentUpdate->getProjectID());
        $this->assertEquals(\DSI\Entity\ContentUpdate::Updated_Content, $contentUpdate->getUpdated());
    }

    /** @test */
    public function changingDescCreatesContentUpdate()
    {
        $this->deleteFirstContentUpdate();

        try {
            $this->updateProject->data()->description = 'New Description';
            $this->updateProject->data()->project = $this->project;
            $this->updateProject->data()->executor = $this->user1;
            $this->updateProject->exec();
        } catch (ErrorHandler $e) {
            $this->assertNull($e);
        }

        $contentUpdates = (new \DSI\Repository\ContentUpdateRepo())
            ->getAll();

        $this->assertCount(1, $contentUpdates);

        $contentUpdate = $contentUpdates[0];

        $this->assertEquals($this->project->getId(), $contentUpdate->getProjectID());
        $this->assertEquals(\DSI\Entity\ContentUpdate::Updated_Content, $contentUpdate->getUpdated());
    }

    /** @test */
    public function givenAContentUpdateExists_whenProjectIsUpdated_thenTheExistingContentUpdateIsUpdated()
    {
        try {
            $this->updateProject->data()->name = 'New Name';
            $this->updateProject->data()->project = $this->project;
            $this->updateProject->data()->executor = $this->user1;
            $this->updateProject->exec();
        } catch (ErrorHandler $e) {
            $this->assertNull($e);
        }

        $contentUpdates = (new \DSI\Repository\ContentUpdateRepo())
            ->getAll();

        $this->assertCount(1, $contentUpdates);

        $contentUpdate = $contentUpdates[0];

        $this->assertEquals($this->project->getId(), $contentUpdate->getProjectID());
        $this->assertEquals(\DSI\Entity\ContentUpdate::New_Content, $contentUpdate->getUpdated());
    }


    private function deleteFirstContentUpdate()
    {
        $contentUpdate = (new \DSI\Repository\ContentUpdateRepo())
            ->getAll();

        (new \DSI\UseCase\ContentUpdates\RemoveContentUpdate())
            ->setContentUpdate($contentUpdate[0])
            ->exec();
    }
}