<?php

use DSI\Entity\Skill;

require_once __DIR__ . '/../../../config.php';

class SkillTest extends \PHPUnit_Framework_TestCase
{
    /** @var Skill */
    private $skill;

    public function setUp()
    {
        $this->skill = new Skill();
    }

    /** @test setId, getId */
    public function settingAnId_returnsTheId()
    {
        $this->skill->setId(1);
        $this->assertEquals(1, $this->skill->getId());
    }

    /** @test setId */
    public function settingAnInvalidId_throwsException()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $this->skill->setId(0);
    }

    /** @test setSkill, getSkill*/
    public function settingAName_returnsTheName()
    {
        $name = 'php';
        $this->skill->setName($name);
        $this->assertEquals($name, $this->skill->getName());
    }
}