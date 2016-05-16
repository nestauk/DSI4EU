<?php

require_once __DIR__ . '/../../../config.php';

class UserSkillRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\UserSkillRepository */
    protected $userSkillRepo;

    /** @var \DSI\Repository\UserRepository */
    protected $userRepo;

    /** @var \DSI\Entity\User */
    protected $user_1, $user_2, $user_3;

    /** @var \DSI\Repository\SkillRepository */
    protected $skillRepo;

    /** @var \DSI\Entity\Skill */
    protected $skill_1, $skill_2, $skill_3;

    public function setUp()
    {
        $this->userSkillRepo = new \DSI\Repository\UserSkillRepository();
        $this->userRepo = new \DSI\Repository\UserRepository();
        $this->skillRepo = new \DSI\Repository\SkillRepository();

        $this->user_1 = $this->createUser(1);
        $this->user_2 = $this->createUser(2);
        $this->user_3 = $this->createUser(3);
        $this->skill_1 = $this->createSkill(1);
        $this->skill_2 = $this->createSkill(2);
        $this->skill_3 = $this->createSkill(3);
    }

    public function tearDown()
    {
        $this->userSkillRepo->clearAll();
        $this->userRepo->clearAll();
        $this->skillRepo->clearAll();
    }

    /** @test saveAsNew */
    public function userSkillCanBeSaved()
    {
        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_1);
        $userSkill->setSkill($this->skill_1);
        $this->userSkillRepo->add($userSkill);

        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_1);
        $userSkill->setSkill($this->skill_2);
        $this->userSkillRepo->add($userSkill);

        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_2);
        $userSkill->setSkill($this->skill_1);
        $this->userSkillRepo->add($userSkill);

        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_3);
        $userSkill->setSkill($this->skill_1);
        $this->userSkillRepo->add($userSkill);

        $this->assertCount(2, $this->userSkillRepo->getByUserID(1));
    }

    /** @test saveAsNew */
    public function cannotAddSameUserSkillTwice()
    {
        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_1);
        $userSkill->setSkill($this->skill_1);
        $this->userSkillRepo->add($userSkill);

        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_1);
        $userSkill->setSkill($this->skill_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->userSkillRepo->add($userSkill);
    }

    /** @test saveAsNew */
    public function getAllSkillIDsForUser()
    {
        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_1);
        $userSkill->setSkill($this->skill_1);
        $this->userSkillRepo->add($userSkill);

        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_1);
        $userSkill->setSkill($this->skill_2);
        $this->userSkillRepo->add($userSkill);

        $this->assertEquals([1, 2], $this->userSkillRepo->getSkillIDsForUser(1));
    }

    /** @test saveAsNew */
    public function getAllUsersForSkill()
    {
        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_1);
        $userSkill->setSkill($this->skill_1);
        $this->userSkillRepo->add($userSkill);

        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_2);
        $userSkill->setSkill($this->skill_1);
        $this->userSkillRepo->add($userSkill);

        $this->assertCount(2, $this->userSkillRepo->getBySkillID(1));
    }

    /** @test saveAsNew */
    public function getAllUserIDsForSkill()
    {
        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_1);
        $userSkill->setSkill($this->skill_1);
        $this->userSkillRepo->add($userSkill);

        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_2);
        $userSkill->setSkill($this->skill_1);
        $this->userSkillRepo->add($userSkill);

        $this->assertEquals([1, 2], $this->userSkillRepo->getUserIDsForSkill(1));
    }

    /** @test saveAsNew */
    public function canCheckIfUserHasSkillName()
    {
        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_1);
        $userSkill->setSkill($this->skill_1);
        $this->userSkillRepo->add($userSkill);

        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_2);
        $userSkill->setSkill($this->skill_2);
        $this->userSkillRepo->add($userSkill);

        $this->assertTrue($this->userSkillRepo->userHasSkillName(
            $this->user_1->getId(), $this->skill_1->getName())
        );
        $this->assertFalse($this->userSkillRepo->userHasSkillName(
            $this->user_1->getId(), $this->skill_2->getName())
        );
        $this->assertTrue($this->userSkillRepo->userHasSkillName(
            $this->user_2->getId(), $this->skill_2->getName())
        );
        $this->assertFalse($this->userSkillRepo->userHasSkillName(
            $this->user_2->getId(), $this->skill_1->getName())
        );
    }

    /** @test saveAsNew */
    public function canGetSkillNamesByUserID()
    {
        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_1);
        $userSkill->setSkill($this->skill_1);
        $this->userSkillRepo->add($userSkill);

        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_1);
        $userSkill->setSkill($this->skill_2);
        $this->userSkillRepo->add($userSkill);

        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setUser($this->user_2);
        $userSkill->setSkill($this->skill_3);
        $this->userSkillRepo->add($userSkill);

        $this->assertEquals(
            [$this->skill_1->getName(), $this->skill_2->getName()],
            $this->userSkillRepo->getSkillsNameByUserID($this->user_1->getId())
        );
        $this->assertEquals(
            [$this->skill_3->getName()],
            $this->userSkillRepo->getSkillsNameByUserID($this->user_2->getId())
        );
    }


    private function createUser(int $userID)
    {
        $user = new \DSI\Entity\User();
        $user->setId($userID);
        $this->userRepo->insert($user);
        return $user;
    }

    private function createSkill(int $skillID)
    {
        $skill = new \DSI\Entity\Skill();
        $skill->setId($skillID);
        $skill->setName('skill-' . $skillID);
        $this->skillRepo->saveAsNew($skill);
        return $skill;
    }
}