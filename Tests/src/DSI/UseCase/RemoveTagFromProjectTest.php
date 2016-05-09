<?php

require_once __DIR__ . '/../../../config.php';

class RemoveTagFromProjectTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddTagToProject */
    private $addTagToProjectCommand;

    /** @var \DSI\UseCase\RemoveTagFromProject */
    private $removeTagFromProjectCommand;

    /** @var \DSI\Repository\TagForProjectsRepository*/
    private $tagRepo;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Repository\ProjectTagRepository */
    private $projectTagRepo;

    /** @var \DSI\Repository\ProjectRepository */
    private $projectRepo;

    /** @var \DSI\Entity\Project */
    private $project;

    public function setUp()
    {
        $this->addTagToProjectCommand = new \DSI\UseCase\AddTagToProject();
        $this->removeTagFromProjectCommand = new \DSI\UseCase\RemoveTagFromProject();
        $this->projectTagRepo = new \DSI\Repository\ProjectTagRepository();
        $this->tagRepo = new \DSI\Repository\TagForProjectsRepository();
        $this->projectRepo = new \DSI\Repository\ProjectRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $user = new \DSI\Entity\User();
        $this->userRepo->saveAsNew($user);

        $this->project = new \DSI\Entity\Project();
        $this->project->setOwner($user);
        $this->projectRepo->saveAsNew($this->project);
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