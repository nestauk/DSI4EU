<?php

require_once __DIR__ . '/../../../config.php';

class RemoveDsiFocusTagFromProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddDsiFocusTagToProject */
    private $addTagToProjectCommand;

    /** @var \DSI\UseCase\RemoveDsiFocusTagFromProject */
    private $removeTagFromProjectCommand;

    /** @var \DSI\Repository\DsiFocusTagRepo */
    private $tagRepo;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Repository\ProjectDsiFocusTagRepo */
    private $projectTagRepo;

    /** @var \DSI\Repository\ProjectRepo */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    public function setUp()
    {
        $this->addTagToProjectCommand = new \DSI\UseCase\AddDsiFocusTagToProject();
        $this->removeTagFromProjectCommand = new \DSI\UseCase\RemoveDsiFocusTagFromProject();
        $this->projectTagRepo = new \DSI\Repository\ProjectDsiFocusTagRepo();
        $this->tagRepo = new \DSI\Repository\DsiFocusTagRepo();
        $this->projectRepo = new \DSI\Repository\ProjectRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

        $user = new \DSI\Entity\User();
        $this->userRepo->insert($user);

        $this->project = new \DSI\Entity\Project();
        $this->project->setOwner($user);
        $this->projectRepo->insert($this->project);
    }

    public function tearDown()
    {
        $this->tagRepo->clearAll();
        $this->projectRepo->clearAll();
        $this->userRepo->clearAll();
        $this->projectTagRepo->clearAll();
    }

    /** @test */
    public function successfulRemoveTagFromProject()
    {
        $this->addTagToProjectCommand->data()->tag = 'test';
        $this->addTagToProjectCommand->data()->projectID = $this->project->getId();
        $this->addTagToProjectCommand->exec();

        $this->removeTagFromProjectCommand->data()->tag = 'test';
        $this->removeTagFromProjectCommand->data()->projectID = $this->project->getId();
        $this->removeTagFromProjectCommand->exec();

        $this->assertFalse(
            $this->projectTagRepo->projectHasTagName(
                $this->removeTagFromProjectCommand->data()->projectID,
                $this->removeTagFromProjectCommand->data()->tag
            )
        );
    }

    /** @test */
    public function cannotRemoveIfUserDoesNotHaveIt()
    {
        $e = null;

        try {
            $this->removeTagFromProjectCommand->data()->tag = 'test';
            $this->removeTagFromProjectCommand->data()->projectID = $this->project->getId();
            $this->removeTagFromProjectCommand->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('tag'));
    }
}