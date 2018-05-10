<?php

require_once __DIR__ . '/../../../config.php';

use DSI\UseCase\Register;
use DSI\Service\ErrorHandler;
use DSI\Repository\UserRepo;

class RegisterTest extends PHPUnit_Framework_TestCase
{
    /** @var Register */
    private $registerCommand;

    /** @var UserRepo */
    private $userRepo;

    public function setUp()
    {
        $this->registerCommand = new Register();

        $this->userRepo = new UserRepo();
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
            $this->registerCommand->data()->recaptchaResponse = true;
            $this->registerCommand->data()->acceptTerms = true;
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
        $this->registerCommand->data()->recaptchaResponse = true;
        $this->registerCommand->data()->acceptTerms = true;
        $this->registerCommand->exec();

        $e = null;
        try {
            $this->registerCommand->data()->email = 'test@example.org';
            $this->registerCommand->data()->password = 'testPassword';
            $this->registerCommand->data()->recaptchaResponse = true;
            $this->registerCommand->data()->acceptTerms = true;
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
            $this->registerCommand->data()->recaptchaResponse = true;
            $this->registerCommand->data()->acceptTerms = true;
            $this->registerCommand->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('email'));
        $this->assertNotEmpty($e->getTaggedError('password'));
    }

    /** @test */
    public function cannotRegisterWithoutAcceptingTheTerms()
    {
        $e = null;
        try {
            $this->registerCommand->data()->email = 'test@example.org';
            $this->registerCommand->data()->password = 'testPassword';
            $this->registerCommand->data()->recaptchaResponse = true;
            $this->registerCommand->data()->acceptTerms = false;
            $this->registerCommand->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('accept-terms'));
    }
}