<?php

require_once __DIR__ . '/../../../config.php';

class RemoveLinkFromUserTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\AddLinkToUser */
    private $addLinkToUserCommand;

    /** @var \DSI\UseCase\RemoveLinkFromUser */
    private $removeLinkFromUserCommand;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->addLinkToUserCommand = new \DSI\UseCase\AddLinkToUser();
        $this->removeLinkFromUserCommand = new \DSI\UseCase\RemoveLinkFromUser();
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
    public function successfulRemoveLinkFromUser()
    {
        $this->addLinkToUserCommand->data()->link = 'http://example.org';
        $this->addLinkToUserCommand->data()->userID = $this->user->getId();
        $this->addLinkToUserCommand->exec();

        $this->removeLinkFromUserCommand->data()->link = 'http://example.org';
        $this->removeLinkFromUserCommand->data()->userID = $this->user->getId();
        $this->removeLinkFromUserCommand->exec();

        $this->assertFalse(
            (new \DSI\Repository\UserLinkRepository())->userHasLink(
                $this->removeLinkFromUserCommand->data()->userID,
                $this->removeLinkFromUserCommand->data()->link
            )
        );
    }

    /** @test */
    public function cannotRemoveLinkIfUserDoesNotHaveIt()
    {
        $e = null;

        try {
            $this->removeLinkFromUserCommand->data()->link = 'http://example.org';
            $this->removeLinkFromUserCommand->data()->userID = $this->user->getId();
            $this->removeLinkFromUserCommand->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('link'));
    }
}