<?php

require_once __DIR__ . '/../../../config.php';

use DSI\UseCase\Register;
use DSI\Service\ErrorHandler;
use DSI\Repository\UserRepository;

class RegisterTest extends PHPUnit_Framework_TestCase
{
    /** @var Register */
    private $registerCommand;

    /** @var UserRepository */
    private $userRepo;

    public function setUp()
    {
        $this->registerCommand = new Register();

        $this->userRepo = new UserRepository();
        $this->registerCommand->setUserRepo($this->userRepo);
    }

    public function tearDown()
    {
        $this->userRepo->clearAll();
    }

    /** @test */
    public function successfulRegistration()
    {
        try {
            $this->registerCommand->data()->email = 'test@example.org';
            $this->registerCommand->data()->password = 'testPassword';
            $this->registerCommand->exec();
        } catch (ErrorHandler $e) {
            $this->assertNull($e);
        }

        $user = $this->userRepo->getById(1);
        $this->assertEquals(
            $this->registerCommand->getUser()->getId(),
            $user->getId()
        );
    }

    /** @test */
    public function cannotRegisterSameEmailAddressTwice()
    {
        $this->registerCommand->data()->email = 'test@example.org';
        $this->registerCommand->data()->password = 'testPassword';
        $this->registerCommand->exec();

        $e = null;
        try {
            $this->registerCommand->data()->email = 'test@example.org';
            $this->registerCommand->data()->password = 'testPassword';
            $this->registerCommand->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('email'));
    }

    /** @test */
    public function cannotRegisterWithoutEmailAddressOrPassword()
    {
        $e = null;
        try {
            $this->registerCommand->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('email'));
        $this->assertNotEmpty($e->getTaggedError('password'));
    }
}