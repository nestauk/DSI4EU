<?php

require_once __DIR__ . '/../../../config.php';

class CreateImpactTagTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreateImpactTag */
    private $createTagCommand;

    /** @var \DSI\Repository\ImpactTagRepository */
    private $tagRepo;

    public function setUp()
    {
        $this->createTagCommand = new \DSI\UseCase\CreateImpactTag();
        $this->tagRepo = new \DSI\Repository\ImpactTagRepository();
    }

    public function tearDown()
    {
        (new \DSI\Repository\ImpactTagRepository())->clearAll();
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