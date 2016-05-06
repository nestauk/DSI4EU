<?php

require_once __DIR__ . '/../../../config.php';

class CreateSkillTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreateSkill */
    private $createSkillCommand;

    /** @var \DSI\Repository\SkillRepository */
    private $skillRepo;

    public function setUp()
    {
        $this->createSkillCommand = new \DSI\UseCase\CreateSkill();
        $this->skillRepo = new \DSI\Repository\SkillRepository();
    }

    public function tearDown()
    {
        (new \DSI\Repository\SkillRepository())->clearAll();
        (new \DSI\Repository\UserSkillRepository())->clearAll();
    }

    /** @test */
    public function successfulCreation()
    {
        $this->createSkillCommand->data()->name = 'test';
        $this->createSkillCommand->exec();

        $this->assertCount(1, $this->skillRepo->getAll());

        $this->createSkillCommand->data()->name = 'test2';
        $this->createSkillCommand->exec();

        $this->assertCount(2, $this->skillRepo->getAll());
    }

    /** @test */
    public function cannotAddSameSkillTwice()
    {
        $e = null;

        $this->createSkillCommand->data()->name = 'test';
        $this->createSkillCommand->exec();

        $this->assertCount(1, $this->skillRepo->getAll());

        $this->createSkillCommand->data()->name = 'test';
        try {
            $this->createSkillCommand->exec();
        } catch (\DSI\Service\ErrorHandler $e){
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('skill'));
    }
}