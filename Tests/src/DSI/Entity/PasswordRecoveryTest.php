<?php

use DSI\Entity\User;
use DSI\Entity\PasswordRecovery;

require_once __DIR__ . '/../../../config.php';

class PasswordRecoveryTest extends \PHPUnit_Framework_TestCase
{
    /** @var PasswordRecovery */
    private $passwordRecovery;

    /** @var User */
    private $user;

    public function setUp()
    {
        $this->user = new User();
        $this->user->setId(1);

        $this->passwordRecovery = new PasswordRecovery();
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->passwordRecovery->setId(0);
    }

    /** @test setAllInfo, getAllInfo */
    public function settingAllInfo_returnsAllInfo()
    {
        $this->passwordRecovery->setId($id = 1);
        $this->passwordRecovery->setUser($this->user);
        $this->passwordRecovery->setCode($code = 'OEFN2VO2GV');
        $this->passwordRecovery->setExpires($expires = '2016-05-16 10:10:10');
        $this->passwordRecovery->setIsUsed($isUsed = true);

        $this->assertEquals($id, $this->passwordRecovery->getId());
        $this->assertEquals($this->user->getId(), $this->passwordRecovery->getUser()->getId());
        $this->assertEquals($code, $this->passwordRecovery->getCode());
        $this->assertEquals($expires, $this->passwordRecovery->getExpires());
        $this->assertEquals($isUsed, $this->passwordRecovery->isUsed());
    }
}