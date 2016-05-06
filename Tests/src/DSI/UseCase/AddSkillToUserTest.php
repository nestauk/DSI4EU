<?php

require_once __DIR__ . '/../../../config.php';

class AddSkillToUserTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddSkillToUser */
    private $addSkillToUserCommand;

    /** @var \DSI\Repository\SkillRepository */
    private $skillRepo;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->addSkillToUserCommand = new \DSI\UseCase\AddSkillToUser();
        $this->skillRepo = new \DSI\Repository\SkillRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->saveAsNew($this->user);
    }

    public function tearDown()
    {
        $this->skillRepo->clearAll();
        $this->userRepo->clearAll();
        (new \DSI\Repository\UserSkillRepository())->clearAll();
    }

    /** @test */
    public function successfulAdditionOfSkillToUser()
    {
        $this->addSkillToUserCommand->data()->skill = 'test';
        $this->addSkillToUserCommand->data()->userID = $this->user->getId();
        $this->addSkillToUserCommand->exec();

        $this->assertTrue(
            (new \DSI\Repository\UserSkillRepository())->userHasSkillName(
                $this->addSkillToUserCommand->data()->userID,
                $this->addSkillToUserCommand->data()->skill
            )
        );
    }

    /** @test */
    public function cannotAddSameSkillTwice()
    {
        $e = null;
        $this->addSkillToUserCommand->data()->skill = 'test';
        $this->addSkillToUserCommand->data()->userID = $this->user->getId();
        $this->addSkillToUserCommand->exec();

        try {
            $this->addSkillToUserCommand->data()->skill = 'test';
            $this->addSkillToUserCommand->data()->userID = $this->user->getId();
            $this->addSkillToUserCommand->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('skill'));
    }
}