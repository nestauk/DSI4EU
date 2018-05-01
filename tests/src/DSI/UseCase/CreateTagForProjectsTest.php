<?php

require_once __DIR__ . '/../../../config.php';

class CreateTagForProjectsTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreateTagForProjects */
    private $createTagCommand;

    /** @var \DSI\Repository\TagForProjectsRepo */
    private $tagRepo;

    public function setUp()
    {
        $this->createTagCommand = new \DSI\UseCase\CreateTagForProjects();
        $this->tagRepo = new \DSI\Repository\TagForProjectsRepo();
    }

    public function tearDown()
    {
        (new \DSI\Repository\TagForProjectsRepo())->clearAll();
    }

    /** @test */
    public function successfulCreation()
    {
        $this->createTagCommand->data()->name = 'test';
        $this->createTagCommand->exec();

        $this->assertCount(1, $this->tagRepo->getAll());

        $this->createTagCommand->data()->name = 'test2';
        $this->createTagCommand->exec();

        $this->assertCount(2, $this->tagRepo->getAll());
    }

    /** @test */
    public function cannotAddSameTagTwice()
    {
        $e = null;

        $this->createTagCommand->data()->name = 'test';
        $this->createTagCommand->exec();

        $this->assertCount(1, $this->tagRepo->getAll());

        $this->createTagCommand->data()->name = 'test';
        try {
            $this->createTagCommand->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('tag'));
    }
}