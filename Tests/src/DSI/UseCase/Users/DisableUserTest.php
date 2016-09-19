<?php

require_once __DIR__ . '/../../../../config.php';

use DSI\Service\ErrorHandler;
use DSI\Repository\UserRepository;

class DisableUserTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\UseCase\Users\DisableUser */
    private $disableUserCmd;

    /** @var UserRepository */
    private $userRepo;

    /** @var \DSI\Entity\User */
    private $superAdmin,
        $normalUser,
        $user;

    public function setUp()
    {
        $this->disableUserCmd = new \DSI\UseCase\Users\DisableUser();

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
    public function successfullyDisableUser()
    {
        try {
            $this->disableUserCmd->data()->userID = $this->user->getId();
            $this->disableUserCmd->data()->executor = $this->superAdmin;
            $this->disableUserCmd->exec();
        } catch (ErrorHandler $e) {
            $this->assertNull($e);
        }

        $user = $this->userRepo->getById($this->user->getId());
        $this->assertTrue($user->isDisabled());
    }

    /** @test */
    public function onlySuperAdminCanMakeChanges()
    {
        $e = null;
        try {
            $this->disableUserCmd->data()->userID = $this->user->getId();
            $this->disableUserCmd->data()->executor = $this->normalUser;
            $this->disableUserCmd->exec();
        } catch (ErrorHandler $e) {
        }

        $this->assertNotNull($e);
        $this->assertNotEmpty($e->getTaggedError('executor'));
    }
}