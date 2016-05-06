<?php

require_once __DIR__ . '/../../../config.php';

class RemoveSkillFromUserTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddSkillToUser */
    private $addSkillToUserCommand;

    /** @var \DSI\UseCase\RemoveSkillFromUser */
    private $removeSkillFromUserCommand;

    /** @var \DSI\Repository\SkillRepository */
    private $skillRepo;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->addSkillToUserCommand = new \DSI\UseCase\AddSkillToUser();
        $this->removeSkillFromUserCommand = new \DSI\UseCase\RemoveSkillFromUser();
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
    public function successfulRemoveSkillFromUser()
    {
        $this->addSkillToUserCommand->data()->skill = 'test';
        $this->addSkillToUserCommand->data()->userID = $this->user->getId();
        $this->addSkillToUserCommand->exec();

        $this->removeSkillFromUserCommand->data()->skill = 'test';
        $this->removeSkillFromUserCommand->data()->userID = $this->user->getId();
        $this->removeSkillFromUserCommand->exec();

        $this->assertFalse(
            (new \DSI\Repository\UserSkillRepository())->userHasSkillName(
                $this->removeSkillFromUserCommand->data()->userID,
                $this->removeSkillFromUserCommand->data()->skill
            )
        );
    }

    /** @test */
    public function cannotRemoveIfUserDoesNotHaveIt()
    {
        $e = null;

        try {
            $this->removeSkillFromUserCommand->data()->skill = 'test';
            $this->removeSkillFromUserCommand->data()->userID = $this->user->getId();
            $this->removeSkillFromUserCommand->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('skill'));
    }
}