<?php

require_once __DIR__ . '/../../../config.php';

use \DSI\Repository\SkillRepository;
use \DSI\Entity\Skill;

class SkillRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var SkillRepository */
    protected $skillRepo;

    public function setUp()
    {
        $this->skillRepo = new SkillRepository;
    }

    public function tearDown()
    {
        $this->skillRepo->clearAll();
    }

    /** @test saveAsNew */
    public function skillCanBeSaved()
    {
        $skill = new Skill();
        $skill->setName('test');

        $this->skillRepo->saveAsNew($skill);

        $this->assertEquals(1, $skill->getId());
    }

    /** @test save, getByID */
    public function skillCanBeUpdated()
    {
        $skill = new Skill();
        $skill->setName('test');

        $this->skillRepo->saveAsNew($skill);

        $skill->setName('test2');
        $this->skillRepo->save($skill);

        $sameSkil = $this->skillRepo->getById($skill->getId());
        $this->assertEquals($skill->getName(), $sameSkil->getName());
    }

    /** @test getByID */
    public function gettingAnNonExistentSkillById_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->skillRepo->getById(1);
    }

    /** @test save */
    public function NonexistentSkillCannotBeSaved()
    {
        $name = 'test';
        $skill = new Skill();
        $skill->setId(1);
        $skill->setName($name);

        $this->setExpectedException(\DSI\NotFound::class);
        $this->skillRepo->save($skill);
    }

    /** @test getByName */
    public function userCanBeRetrievedByName()
    {
        $name = 'test';
        $skill = new Skill();
        $skill->setName($name);
        $this->skillRepo->saveAsNew($skill);

        $sameSkill = $this->skillRepo->getByName($name);
        $this->assertEquals($skill->getId(), $sameSkill->getId());
    }

    /** @test getByID */
    public function gettingAnNonExistentSkillByName_throwsException()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->skillRepo->getByName('test');
    }

    /** @test nameExists */
    public function NonexistentUserCannotBeFoundByName()
    {
        $this->assertFalse($this->skillRepo->nameExists('test'));
    }

    /** @test saveAsNew */
    public function cannotSaveAsNewWithoutAName()
    {
        $skill = new Skill();
        $this->setExpectedException(\DSI\NotEnoughData::class);
        $this->skillRepo->saveAsNew($skill);
    }

    /** @test save */
    public function cannotSaveWithAnEmptyName()
    {
        $skill = new Skill();
        $skill->setName('test');
        $this->skillRepo->saveAsNew($skill);

        $skill->setName('');
        $this->setExpectedException(\DSI\NotEnoughData::class);
        $this->skillRepo->save($skill);
    }

    /** @test save */
    public function cannotSaveAsNew2SkillsWithTheSameName()
    {
        $skill = new Skill();
        $skill->setName('test');
        $this->skillRepo->saveAsNew($skill);

        $skill = new Skill();
        $skill->setName('test');
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->skillRepo->saveAsNew($skill);
    }

    /** @test save */
    public function cannotSave2SkillsWithTheSameName()
    {
        $skill_1 = new Skill();
        $skill_1->setName('test');
        $this->skillRepo->saveAsNew($skill_1);

        $skill_2 = new Skill();
        $skill_2->setName('test2');
        $this->skillRepo->saveAsNew($skill_2);

        $skill_2->setName('test');
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->skillRepo->save($skill_2);
    }

    /** @test getAll */
    public function getAllSkills()
    {
        $skill = new Skill();
        $skill->setName('test');
        $this->skillRepo->saveAsNew($skill);

        $this->assertCount(1, $this->skillRepo->getAll());

        $skill = new Skill();
        $skill->setName('test2');
        $this->skillRepo->saveAsNew($skill);

        $this->assertCount(2, $this->skillRepo->getAll());
    }

    /** @test saveAsNew getById */
    public function setAllSkillsDetails()
    {
        $skill = new Skill();
        $skill->setName('test');
        $this->skillRepo->saveAsNew($skill);

        $sameSkill = $this->skillRepo->getById($skill->getId());
        $this->assertEquals($skill->getName(), $sameSkill->getName());
    }
}