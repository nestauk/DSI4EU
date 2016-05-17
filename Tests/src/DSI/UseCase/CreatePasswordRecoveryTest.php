<?php

require_once __DIR__ . '/../../../config.php';

class CreatePasswordRecoveryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\CreatePasswordRecovery */
    private $createPasswordRecoveryCmd;

    /** @var \DSI\Repository\PasswordRecoveryRepository*/
    private $passwordRecoveryRepo;

    /** @var \DSI\Repository\UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $user;

    public function setUp()
    {
        $this->createPasswordRecoveryCmd = new \DSI\UseCase\CreatePasswordRecovery();
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
    public function successfulCreation()
    {
        $this->createPasswordRecoveryCmd->data()->user = $this->user;
        $this->createPasswordRecoveryCmd->exec();

        $this->assertCount(1, $this->passwordRecoveryRepo->getAll());

        $this->createPasswordRecoveryCmd->data()->user = $this->user;
        $this->createPasswordRecoveryCmd->exec();

        $this->assertCount(2, $this->passwordRecoveryRepo->getAll());
    }

    /** @test */
    public function successfulCreation_createdData()
    {
        $this->createPasswordRecoveryCmd->data()->user = $this->user;
        $this->createPasswordRecoveryCmd->exec();
        $passwordRecovery = $this->createPasswordRecoveryCmd->getPasswordRecovery();

        $this->assertRegExp('[^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}$]', $passwordRecovery->getExpires());
        $this->assertNotEquals('', $passwordRecovery->getCode());
    }
}