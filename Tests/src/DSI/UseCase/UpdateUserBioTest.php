<?php

require_once __DIR__ . '/../../../config.php';

use DSI\Service\ErrorHandler;
use DSI\Repository\UserRepository;

class UpdateUserBioTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\UpdateUserBio */
    private $updateUserBioCommand;

    /** @var UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->updateUserBioCommand = new \DSI\UseCase\UpdateUserBio();

        $this->userRepo = new UserRepository();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->saveAsNew($this->user);
    }

    public function tearDown()
    {
        $this->userRepo->clearAll();
    }

    /** @test */
    public function successfulUpdate()
    {
        try {
            $this->updateUserBioCommand->data()->userID = $this->user->getId();
            $this->updateUserBioCommand->data()->bio = 'Wonderful Bio';
            $this->updateUserBioCommand->exec();
        } catch (ErrorHandler $e) {
            $this->assertNull($e);
        }

        $user = $this->userRepo->getById($this->user->getId());
        $this->assertEquals('Wonderful Bio', $user->getBio());
    }
}