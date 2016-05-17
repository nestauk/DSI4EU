<?php

require_once __DIR__ . '/../../../config.php';

class VerifyPasswordRecoveryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreatePasswordRecovery */
    private $createPasswordRecoveryCmd;

    /** @var \DSI\UseCase\VerifyPasswordRecovery */
    private $verifyPasswordRecoveryCmd;

    /** @var \DSI\Repository\PasswordRecoveryRepository */
    private $passwordRecoveryRepo;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->createPasswordRecoveryCmd = new \DSI\UseCase\CreatePasswordRecovery();
        $this->verifyPasswordRecoveryCmd = new \DSI\UseCase\VerifyPasswordRecovery();
        $this->passwordRecoveryRepo = new \DSI\Repository\PasswordRecoveryRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);
    }

    public function tearDown()
    {
        $this->passwordRecoveryRepo->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test */
    public function canVerifyGenuineRequest()
    {
        $this->createPasswordRecoveryCmd->data()->user = $this->user;
        $this->createPasswordRecoveryCmd->exec();
        $passwordRecovery = $this->createPasswordRecoveryCmd->getPasswordRecovery();

        $this->verifyPasswordRecoveryCmd->data()->user = $this->user;
        $this->verifyPasswordRecoveryCmd->data()->code = $passwordRecovery->getCode();
        $this->verifyPasswordRecoveryCmd->exec();

        $samePasswordRecovery = $this->passwordRecoveryRepo->getById($passwordRecovery->getId());
        $this->assertTrue($samePasswordRecovery->isUsed());
    }

    /** @test */
    public function doesNotAcceptInvalidCode()
    {
        $e = null;
        $this->createPasswordRecoveryCmd->data()->user = $this->user;
        $this->createPasswordRecoveryCmd->exec();
        $passwordRecovery = $this->createPasswordRecoveryCmd->getPasswordRecovery();

        $this->verifyPasswordRecoveryCmd->data()->user = $this->user;
        $this->verifyPasswordRecoveryCmd->data()->code = $passwordRecovery->getCode() . '1';
        try {
            $this->verifyPasswordRecoveryCmd->exec();
        } catch (\DSI\Service\ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('code'));
        $samePasswordRecovery = $this->passwordRecoveryRepo->getById($passwordRecovery->getId());
        $this->assertFalse($samePasswordRecovery->isUsed());
    }
}