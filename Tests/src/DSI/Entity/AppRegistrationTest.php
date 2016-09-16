<?php

require_once __DIR__ . '/../../../config.php';

class AppRegistrationTest extends \PHPUnit_Framework_TestCase
{
    /** @var \DSI\Entity\AppRegistration */
    private $appRegistration;

    public function setUp()
    {
        $this->appRegistration = new \DSI\Entity\AppRegistration();
    }

    public function tearDown()
    {

    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->assertEquals(0, $this->appRegistration->getId());

        $this->appRegistration->setId(1);
        $this->assertEquals(1, $this->appRegistration->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->appRegistration->setId(0);
    }

    /** @test */
    public function settingLoggedInUser_returnsLoggedInUser()
    {
        $user = new \DSI\Entity\User();
        $user->setId(1);

        $this->assertEquals(0, $this->appRegistration->getLoggedInUserID());
        $this->assertNull($this->appRegistration->getLoggedInUser());

        $this->appRegistration->setLoggedInUser($user);
        $this->assertEquals($user->getId(), $this->appRegistration->getLoggedInUser()->getId());
        $this->assertEquals($user->getId(), $this->appRegistration->getLoggedInUserID());
    }

    /** @test */
    public function settingRegisteredUser_returnsRegisteredUser()
    {
        $user = new \DSI\Entity\User();
        $user->setId(1);

        $this->assertEquals(0, $this->appRegistration->getRegisteredUserID());
        $this->assertNull($this->appRegistration->getRegisteredUser());

        $this->appRegistration->setRegisteredUser($user);
        $this->assertEquals($user->getId(), $this->appRegistration->getRegisteredUser()->getId());
        $this->assertEquals($user->getId(), $this->appRegistration->getRegisteredUserID());
    }
}