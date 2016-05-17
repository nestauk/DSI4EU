<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\UserRepository;
use \DSI\Repository\PasswordRecoveryRepository;
use \DSI\Entity\User;

class PasswordRecoveryRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var PasswordRecoveryRepository */
    private $passwordRecoveryRepository;

    /** @var UserRepository */
    private $userRepo;

    /** @var User */
    private $user1, $user2;

    public function setUp()
    {
        $this->passwordRecoveryRepository = new PasswordRecoveryRepository();
        $this->userRepo = new UserRepository();

        $this->user1 = new User();
        $this->user2 = new User();
        $this->userRepo->insert($this->user1);
        $this->userRepo->insert($this->user2);
    }

    public function tearDown()
    {
        $this->passwordRecoveryRepository->clearAll();
        $this->userRepo->clearAll();
    }

    /** @test saveAsNew */
    public function passwordRecoveryCanBeSaved()
    {
        $passwordRecovery = new \DSI\Entity\PasswordRecovery();
        $passwordRecovery->setUser($this->user1);
        $passwordRecovery->setCode('XYZ');
        $this->passwordRecoveryRepository->insert($passwordRecovery);

        $this->assertEquals(1, $passwordRecovery->getId());
    }

    /** @test save, getByID */
    public function passwordRecoveryCanBeUpdated()
    {
        $passwordRecovery = new \DSI\Entity\PasswordRecovery();
        $passwordRecovery->setUser($this->user1);
        $passwordRecovery->setCode('XYZ');
        $this->passwordRecoveryRepository->insert($passwordRecovery);

        $passwordRecovery->setCode($code = 'ABC');
        $this->passwordRecoveryRepository->save($passwordRecovery);

        $sameProject = $this->passwordRecoveryRepository->getById($passwordRecovery->getId());
        $this->assertEquals($code, $sameProject->getCode());
    }

    /** @test getByID */
    public function gettingAnNonExistentPasswordRecoveryById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->passwordRecoveryRepository->getById(1);
    }

    /** @test save */
    public function NonexistentPasswordRecoveryCannotBeSaved()
    {
        $passwordRecovery = new \DSI\Entity\PasswordRecovery();
        $passwordRecovery->setId(1);
        $passwordRecovery->setUser($this->user1);
        $passwordRecovery->setCode('ABC');

        $this->setExpectedException(\DSI\NotFound::class);
        $this->passwordRecoveryRepository->save($passwordRecovery);
    }

    /** @test getAll */
    public function getAllPasswordRecoveries()
    {
        $passwordRecovery = new \DSI\Entity\PasswordRecovery();
        $passwordRecovery->setId(1);
        $passwordRecovery->setUser($this->user1);
        $passwordRecovery->setCode('ABC');
        $this->passwordRecoveryRepository->insert($passwordRecovery);

        $this->assertCount(1, $this->passwordRecoveryRepository->getAll());

        $passwordRecovery = new \DSI\Entity\PasswordRecovery();
        $passwordRecovery->setId(1);
        $passwordRecovery->setUser($this->user1);
        $passwordRecovery->setCode('ABC');
        $this->passwordRecoveryRepository->insert($passwordRecovery);

        $this->assertCount(2, $this->passwordRecoveryRepository->getAll());
    }

    /** @test saveAsNew getById */
    public function setAllProjectsDetails()
    {
        $passwordRecovery = new \DSI\Entity\PasswordRecovery();
        $passwordRecovery->setUser($user = $this->user1);
        $passwordRecovery->setCode($code = 'ABC');
        $passwordRecovery->setIsUsed($isUsed = true);
        $this->passwordRecoveryRepository->insert($passwordRecovery);

        $passwordRecovery = $this->passwordRecoveryRepository->getById($passwordRecovery->getId());
        $this->assertEquals($this->user1->getId(), $passwordRecovery->getUser()->getId());
        $this->assertEquals($code, $passwordRecovery->getCode());
        $this->assertRegExp('[^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}$]', $passwordRecovery->getExpires());
        $this->assertEquals($isUsed, $passwordRecovery->isUsed());
    }
}