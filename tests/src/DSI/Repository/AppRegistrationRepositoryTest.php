<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\AppRegistrationRepo;
use \DSI\Entity\AppRegistration;
use \DSI\Repository\UserRepo;

class AppRegistrationRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var UserRepo */
    private $userRepository;

    /** @var AppRegistrationRepo */
    private $appRegistrationRepository;

    /** @var AppRegistration */
    private $appRegistration;

    public function setUp()
    {
        $this->appRegistrationRepository = new AppRegistrationRepo();
        $this->userRepository = new UserRepo();
        $this->appRegistration = new AppRegistration();
    }

    public function tearDown()
    {
        $this->appRegistrationRepository->clearAll();
        $this->userRepository->clearAll();
    }

    /** @test saveAsNew */
    public function AppRegistrationCanBeCreated()
    {
        $loggedInUser = $this->createUser();
        $registeredUser = $this->createUser();

        $this->appRegistration->setLoggedInUser($loggedInUser);
        $this->appRegistration->setRegisteredUser($registeredUser);
        $this->appRegistrationRepository->insert($this->appRegistration);

        $appRegistration = $this->appRegistrationRepository->getById(1);
        $this->assertEquals($loggedInUser->getId(), $appRegistration->getLoggedInUserID());
        $this->assertEquals($registeredUser->getId(), $appRegistration->getRegisteredUserID());
    }

    /** @test getByID */
    public function gettingAnNonExistentAppRegistrationById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->appRegistrationRepository->getById(1);
    }

    /** @test getAll */
    public function getAll()
    {
        $appRegistration = new AppRegistration();
        $this->appRegistrationRepository->insert($appRegistration);

        $this->assertCount(1, $this->appRegistrationRepository->getAll());

        $appRegistration = new AppRegistration();
        $this->appRegistrationRepository->insert($appRegistration);

        $this->assertCount(2, $this->appRegistrationRepository->getAll());
    }

    /**
     * @return \DSI\Entity\User
     */
    private function createUser():\DSI\Entity\User
    {
        $user = new \DSI\Entity\User();
        $this->userRepository->insert($user);
        return $user;
    }
}