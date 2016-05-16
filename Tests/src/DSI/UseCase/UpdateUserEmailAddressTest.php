<?php

require_once __DIR__ . '/../../../config.php';

use DSI\Service\ErrorHandler;
use DSI\Repository\UserRepository;

class UpdateUserEmailAddressTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\UpdateUserEmailAddress */
    private $updateUsersEmailAddress;

    /** @var UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user, $user2;

    public function setUp()
    {
        $this->updateUsersEmailAddress = new \DSI\UseCase\UpdateUserEmailAddress();

        $this->userRepo = new UserRepository();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);
        $this->user2 = new \DSI\Entity\User();
        $this->userRepo->insert($this->user2);
    }

    public function tearDown()
    {
        $this->userRepo->clearAll();
    }

    /** @test */
    public function successfulUpdate()
    {
        try {
            $this->updateUsersEmailAddress->data()->userID = $this->user->getId();
            $this->updateUsersEmailAddress->data()->email = 'test@example.org';
            $this->updateUsersEmailAddress->exec();
        } catch (ErrorHandler $e) {
            $this->assertNull($e);
        }

        $user = $this->userRepo->getById($this->user->getId());
        $this->assertEquals('test@example.org', $user->getEmail());
    }

    /** @test */
    public function cannotSendAnEmptyEmailAddress()
    {
        $e = null;
        try {
            $this->updateUsersEmailAddress->data()->userID = $this->user->getId();
            $this->updateUsersEmailAddress->data()->email = '';
            $this->updateUsersEmailAddress->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('email'));
    }

    /** @test */
    public function cannotHave2UsersWithSameEmailAddress()
    {
        $e = null;

        $this->updateUsersEmailAddress->data()->userID = $this->user->getId();
        $this->updateUsersEmailAddress->data()->email = 'test@example.org';
        $this->updateUsersEmailAddress->exec();

        $this->updateUsersEmailAddress->data()->userID = $this->user2->getId();
        $this->updateUsersEmailAddress->data()->email = 'test@example.org';

        try {
            $this->updateUsersEmailAddress->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('email'));
    }
}