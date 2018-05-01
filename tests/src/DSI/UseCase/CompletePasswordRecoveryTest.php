<?php

require_once __DIR__ . '/../../../config.php';

class CompletePasswordRecoveryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreatePasswordRecovery */
    private $createPasswordRecoveryCmd;

    /** @var \DSI\UseCase\CompletePasswordRecovery */
    private $completePasswordRecoveryCmd;

    /** @var \DSI\Repository\PasswordRecoveryRepo */
    private $passwordRecoveryRepo;

    /** @var \DSI\Repository\UserRepo */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->createPasswordRecoveryCmd = new \DSI\UseCase\CreatePasswordRecovery();
        $this->completePasswordRecoveryCmd = new \DSI\UseCase\CompletePasswordRecovery();
        $this->passwordRecoveryRepo = new \DSI\Repository\PasswordRecoveryRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();

        $this->user = new \DSI\Entity\User();
        $this->user->setEmail('test@example.org');
        $this->userRepo->insert($this->user);
    }

    public function tearDown()
    {
        $this->passwordRecoveryRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function onSuccessfulRequest_passwordRecoveryRequestIsMarkedAsUsed()
    {
        $this->createPasswordRecoveryCmd->data()->email = $this->user->getEmail();
        $this->createPasswordRecoveryCmd->exec();
        $passwordRecovery = $this->createPasswordRecoveryCmd->getPasswordRecovery();

        $this->completePasswordRecoveryCmd->data()->email = $this->user->getEmail();
        $this->completePasswordRecoveryCmd->data()->code = $passwordRecovery->getCode();
        $this->completePasswordRecoveryCmd->data()->password = 'password';
        $this->completePasswordRecoveryCmd->data()->retypePassword = 'password';
        $this->completePasswordRecoveryCmd->exec();

        $samePasswordRecovery = $this->passwordRecoveryRepo->getById($passwordRecovery->getId());
        $this->assertTrue($samePasswordRecovery->isUsed());
    }

    /** @test */
    public function onSuccessfulRequest_userPasswordIsChanged()
    {
        $this->createPasswordRecoveryCmd->data()->email = $this->user->getEmail();
        $this->createPasswordRecoveryCmd->exec();
        $passwordRecovery = $this->createPasswordRecoveryCmd->getPasswordRecovery();

        $this->completePasswordRecoveryCmd->data()->email = $this->user->getEmail();
        $this->completePasswordRecoveryCmd->data()->code = $passwordRecovery->getCode();
        $this->completePasswordRecoveryCmd->data()->password = 'password';
        $this->completePasswordRecoveryCmd->data()->retypePassword = 'password';
        $this->completePasswordRecoveryCmd->exec();

        $user = $this->userRepo->getById($passwordRecovery->getUser()->getId());
        $this->assertTrue($user->checkPassword('password'));
    }

    /** @test */
    public function doesNotAcceptInvalidCode()
    {
        $e = null;
        $this->createPasswordRecoveryCmd->data()->email = $this->user->getEmail();
        $this->createPasswordRecoveryCmd->exec();
        $passwordRecovery = $this->createPasswordRecoveryCmd->getPasswordRecovery();

        $this->completePasswordRecoveryCmd->data()->email = $this->user->getEmail();
        $this->completePasswordRecoveryCmd->data()->code = $passwordRecovery->getCode() . '1';
        $this->completePasswordRecoveryCmd->data()->password = 'password';
        $this->completePasswordRecoveryCmd->data()->retypePassword = 'password';
        try {
            $this->completePasswordRecoveryCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('code'));
        $samePasswordRecovery = $this->passwordRecoveryRepo->getById($passwordRecovery->getId());
        $this->assertFalse($samePasswordRecovery->isUsed());
    }

    /** @test */
    public function passwordTooShort()
    {
        $e = null;
        $this->createPasswordRecoveryCmd->data()->email = $this->user->getEmail();
        $this->createPasswordRecoveryCmd->exec();
        $passwordRecovery = $this->createPasswordRecoveryCmd->getPasswordRecovery();

        $this->completePasswordRecoveryCmd->data()->email = $this->user->getEmail();
        $this->completePasswordRecoveryCmd->data()->code = $passwordRecovery->getCode() . '1';
        $this->completePasswordRecoveryCmd->data()->password = 'pass';
        $this->completePasswordRecoveryCmd->data()->retypePassword = 'pass';
        try {
            $this->completePasswordRecoveryCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('password'));
    }

    /** @test */
    public function passwordMismatch()
    {
        $e = null;
        $this->createPasswordRecoveryCmd->data()->email = $this->user->getEmail();
        $this->createPasswordRecoveryCmd->exec();
        $passwordRecovery = $this->createPasswordRecoveryCmd->getPasswordRecovery();

        $this->completePasswordRecoveryCmd->data()->email = $this->user->getEmail();
        $this->completePasswordRecoveryCmd->data()->code = $passwordRecovery->getCode() . '1';
        $this->completePasswordRecoveryCmd->data()->password = 'password';
        $this->completePasswordRecoveryCmd->data()->retypePassword = 'different_password';
        try {
            $this->completePasswordRecoveryCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('retypePassword'));
    }
}