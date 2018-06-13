<?php

require_once __DIR__ . '/../../../config.php';

class CreateImpactTagTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreateImpactTag */
    private $createTagCommand;

    /** @var \DSI\Repository\ImpactTagRepo */
    private $tagRepo;

    public function setUp()
    {
        $this->createTagCommand = new \DSI\UseCase\CreateImpactTag();
        $this->tagRepo = new \DSI\Repository\ImpactTagRepo();
    }

    public function tearDown()
    {
        (new \DSI\Repository\ImpactTagRepo())->clearAll();
    }

    /** @test */
    public function successfulCreation()
    {
        $this->createTagCommand
            ->setName('test')
            ->exec();

        $this->assertCount(1, $this->tagRepo->getAll());

        $this->createTagCommand
            ->setName('test2')
            ->exec();

        $this->assertCount(2, $this->tagRepo->getAll());
    }

    /** @test */
    public function cannotAddSameTagTwice()
    {
        $e = null;

        $this->createTagCommand
            ->setName('test')
            ->exec();

        $this->assertCount(1, $this->tagRepo->getAll());

        $this->createTagCommand->setName('test');
        try {
            $this->createTagCommand->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('tag'));
    }
}