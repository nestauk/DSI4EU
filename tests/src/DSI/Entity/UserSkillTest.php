<?php

use DSI\Entity\UserSkill;
use \DSI\Entity\User;
use \DSI\Entity\Skill;

require_once __DIR__ . '/../../../config.php';

class UserSkillTest extends \PHPUnit_Framework_TestCase
{
    /** @var UserSkill */
    private $userSkill;

    public function setUp()
    {
        $this->userSkill = new UserSkill();
    }

    /** @test setId, getId */
    public function settingDetails_returnsTheDetails()
    {
        $user = new \DSI\Entity\User();
        $user->setId(1);
        $skill = new \DSI\Entity\Skill();
        $skill->setId(1);

        $this->userSkill = new UserSkill();
        $this->userSkill->setUser($user);
        $this->userSkill->setSkill($skill);

        $this->assertEquals($user->getId(), $this->userSkill->getUserID());
        $this->assertEquals($user->getId(), $this->userSkill->getUser()->getId());
        $this->assertEquals($skill->getId(), $this->userSkill->getSkillID());
        $this->assertEquals($skill->getId(), $this->userSkill->getSkill()->getId());
    }
}