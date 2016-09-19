<?php

require_once __DIR__ . '/../../../../config.php';

use DSI\Service\ErrorHandler;
use DSI\Repository\UserRepository;

class EnableUserTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\Users\EnableUser */
    private $enableUserCmd;

    /** @var UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $superAdmin,
        $normalUser,
        $user;

    public function setUp()
    {
        $this->enableUserCmd = new \DSI\UseCase\Users\EnableUser();

        $this->userRepo = new UserRepository();

        $this->superAdmin = new \DSI\Entity\User();
        $this->superAdmin->setRole('sys-admin');
        $this->userRepo->insert($this->superAdmin);

        $this->normalUser = new \DSI\Entity\User();
        $this->userRepo->insert($this->normalUser);

        $this->user = new \DSI\Entity\User();
        $this->userRepo->insert($this->user);
    }

    public function tearDown()
    {
        $this->userRepo->clearAll();
    }

    /** @test */
    public function successfullyEnableUser()
    {
        try {
            $this->enableUserCmd->data()->userID = $this->user->getId();
            $this->enableUserCmd->data()->executor = $this->superAdmin;
            $this->enableUserCmd->exec();
        } catch (ErrorHandler $e) {
            $this->assertNull($e);
        }

        $user = $this->userRepo->getById($this->user->getId());
        $this->assertFalse($user->isDisabled());
    }

    /** @test */
    public function onlySuperAdminCanMakeChanges()
    {
        $e = null;
        try {
            $this->enableUserCmd->data()->userID = $this->user->getId();
            $this->enableUserCmd->data()->executor = $this->normalUser;
            $this->enableUserCmd->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('executor'));
    }
}