<?php

require_once __DIR__ . '/../../../config.php';

use DSI\Service\ErrorHandler;
use DSI\Repository\UserRepo;

class UpdateUserBasicDetailsTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\UpdateUserBasicDetails */
    private $updateUserBasicDetails;

    /** @var UserRepo */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->updateUserBasicDetails = new \DSI\UseCase\UpdateUserBasicDetails();

        $this->userRepo = new UserRepo();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);
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
        $bio = 'About me';

        try {
            $this->updateUserBasicDetails->data()->userID = $this->user->getId();
            $this->updateUserBasicDetails->data()->firstName = $firstName;
            $this->updateUserBasicDetails->data()->lastName = $lastName;
            $this->updateUserBasicDetails->data()->cityName = $location;
            $this->updateUserBasicDetails->data()->bio = $bio;
            $this->updateUserBasicDetails->exec();
        } catch (ErrorHandler $e) {
            $this->assertNull($e);
        }

        $user = $this->userRepo->getById($this->user->getId());
        $this->assertEquals($firstName, $user->getFirstName());
        $this->assertEquals($lastName, $user->getLastName());
        $this->assertEquals($location, $user->getCityName());
        $this->assertEquals($bio, $user->getBio());
    }

    /** @test */
    public function canSendShortBio()
    {
        $firstName = 'Alecs';
        $lastName = 'Pandele';
        $bio = $this->randomString(140);

        $this->updateUserBasicDetails->data()->userID = $this->user->getId();
        $this->updateUserBasicDetails->data()->firstName = $firstName;
        $this->updateUserBasicDetails->data()->lastName = $lastName;
        $this->updateUserBasicDetails->data()->bio = $bio;
        $this->updateUserBasicDetails->exec();

        $user = $this->userRepo->getById($this->user->getId());
        $this->assertEquals($firstName, $user->getFirstName());
        $this->assertEquals($lastName, $user->getLastName());
        $this->assertEquals($bio, $user->getBio());
    }

    /** @test */
    public function cannotSendLongBio()
    {
        $firstName = 'Alecs';
        $lastName = 'Pandele';
        $bio = $this->randomString(141);

        $this->updateUserBasicDetails->data()->userID = $this->user->getId();
        $this->updateUserBasicDetails->data()->firstName = $firstName;
        $this->updateUserBasicDetails->data()->lastName = $lastName;
        $this->updateUserBasicDetails->data()->bio = $bio;

        $this->setExpectedException(ErrorHandler::class);
        $this->updateUserBasicDetails->exec();
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

    private function randomString($length = 5)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $result = '';
        for ($i = 0; $i < $length; $i++)
            $result .= $characters[mt_rand(0, 61)];

        return $result;
    }
}