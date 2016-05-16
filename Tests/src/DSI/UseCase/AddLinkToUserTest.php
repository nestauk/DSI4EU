<?php

require_once __DIR__ . '/../../../config.php';

class AddLinkToUserTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddLinkToUser */
    private $addLinkToUserCommand;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->addLinkToUserCommand = new \DSI\UseCase\AddLinkToUser();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);
    }

    public function tearDown()
    {
        $this->userRepo->clearAll();
        (new \DSI\Repository\UserLinkRepository())->clearAll();
    }

    /** @test */
    public function successfulAddLinkToUser()
    {
        $this->addLinkToUserCommand->data()->link = 'http://example.org';
        $this->addLinkToUserCommand->data()->userID = $this->user->getId();
        $this->addLinkToUserCommand->exec();

        $this->assertTrue(
            (new \DSI\Repository\UserLinkRepository())->userHasLink(
                $this->addLinkToUserCommand->data()->userID,
                $this->addLinkToUserCommand->data()->link
            )
        );
    }

    /** @test */
    public function cannotAddSameLinkTwice()
    {
        $e = null;
        $this->addLinkToUserCommand->data()->link = 'http://example.org';
        $this->addLinkToUserCommand->data()->userID = $this->user->getId();
        $this->addLinkToUserCommand->exec();

        try {
            $this->addLinkToUserCommand->data()->link = 'http://example.org';
            $this->addLinkToUserCommand->data()->userID = $this->user->getId();
            $this->addLinkToUserCommand->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('skill'));
    }
}