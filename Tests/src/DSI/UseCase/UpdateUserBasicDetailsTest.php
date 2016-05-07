<?php

require_once __DIR__ . '/../../../config.php';

use DSI\Service\ErrorHandler;
use DSI\Repository\UserRepository;

class UpdateUserBasicDetailsTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\UpdateUserBasicDetails */
    private $updateUserBasicDetails;

    /** @var UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->updateUserBasicDetails = new \DSI\UseCase\UpdateUserBasicDetails();

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
        $firstName = 'Alecs';
        $lastName = 'Pandele';
        $location = 'London, UK';

        try {
            $this->updateUserBasicDetails->data()->userID = $this->user->getId();
            $this->updateUserBasicDetails->data()->firstName = $firstName;
            $this->updateUserBasicDetails->data()->lastName = $lastName;
            $this->updateUserBasicDetails->data()->location = $location;
            $this->updateUserBasicDetails->exec();
        } catch (ErrorHandler $e) {
            $this->assertNull($e);
        }

        $user = $this->userRepo->getById($this->user->getId());
        $this->assertEquals($firstName, $user->getFirstName());
        $this->assertEquals($lastName, $user->getLastName());
        $this->assertEquals($location, $user->getLocation());
    }

    /** @test */
    public function cannotSendAnEmptyFirstName()
    {
        $e = null;
        try {
            $this->updateUserBasicDetails->data()->userID = $this->user->getId();
            $this->updateUserBasicDetails->data()->firstName = '';
            $this->updateUserBasicDetails->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('firstName'));
    }

    /** @test */
    public function cannotSendAnEmptyLastName()
    {
        $e = null;
        try {
            $this->updateUserBasicDetails->data()->userID = $this->user->getId();
            $this->updateUserBasicDetails->data()->lastName = '';
            $this->updateUserBasicDetails->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('lastName'));
    }
}