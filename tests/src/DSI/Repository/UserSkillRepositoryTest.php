<?php

require_once __DIR__ . '/../../../config.php';

class UserSkillRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var \DSI\Repository\UserSkillRepo */
    protected $userSkillRepo;

    /** @var \DSI\Repository\UserRepo */
    protected $userRepo;

    /** @var \DSI\Entity\User */
    protected $user_1, $user_2, $user_3;

    /** @var \DSI\Repository\SkillRepo */
    protected $skillRepo;

    /** @var \DSI\Entity\Skill */
    protected $skill_1, $skill_2, $skill_3;

    public function setUp()
    {
        $this->userSkillRepo = new \DSI\Repository\UserSkillRepo();
        $this->userRepo = new \DSI\Repository\UserRepo();
        $this->skillRepo = new \DSI\Repository\SkillRepo();

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
        $this->addSkillToUser($this->skill_1, $this->user_1);
        $this->addSkillToUser($this->skill_2, $this->user_1);
        $this->addSkillToUser($this->skill_1, $this->user_2);
        $this->addSkillToUser($this->skill_1, $this->user_3);

        $this->assertCount(2, $this->userSkillRepo->getByUserID($this->user_1->getId()));
        $this->assertCount(1, $this->userSkillRepo->getByUserID($this->user_2->getId()));
        $this->assertCount(1, $this->userSkillRepo->getByUserID($this->user_3->getId()));
    }

    /** @test saveAsNew */
    public function cannotAddSameUserSkillTwice()
    {
        $this->addSkillToUser($this->skill_1, $this->user_1);
        $this->setExpectedException(\DSI\DuplicateEntry::class);
        $this->addSkillToUser($this->skill_1, $this->user_1);
    }

    /** @test saveAsNew */
    public function getAllSkillIDsForUser()
    {
        $this->addSkillToUser($this->skill_1, $this->user_1);
        $this->addSkillToUser($this->skill_2, $this->user_1);
        $this->addSkillToUser($this->skill_1, $this->user_2);
        $this->addSkillToUser($this->skill_2, $this->user_3);

        $this->assertEquals([
            $this->skill_1->getId(), $this->skill_2->getId()
        ], $this->userSkillRepo->getSkillIDsForUser(
            $this->user_1->getId()
        ));
    }

    /** @test saveAsNew */
    public function getAllUsersForSkill()
    {
        $this->addSkillToUser($this->skill_1, $this->user_1);
        $this->addSkillToUser($this->skill_1, $this->user_2);

        $this->assertCount(2, $this->userSkillRepo->getBySkillID($this->skill_1->getId()));
    }

    /** @test saveAsNew */
    public function getAllUserIDsForSkill()
    {
        $this->addSkillToUser($this->skill_1, $this->user_1);
        $this->addSkillToUser($this->skill_1, $this->user_2);

        $this->assertEquals([1, 2], $this->userSkillRepo->getUserIDsForSkill(1));
    }

    /** @test saveAsNew */
    public function canCheckIfUserHasSkillName()
    {
        $this->addSkillToUser($this->skill_1, $this->user_1);
        $this->addSkillToUser($this->skill_2, $this->user_2);

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
        $this->addSkillToUser($this->skill_1, $this->user_1);
        $this->addSkillToUser($this->skill_2, $this->user_1);
        $this->addSkillToUser($this->skill_3, $this->user_2);

        $this->assertEquals(
            [$this->skill_1->getName(), $this->skill_2->getName()],
            $this->userSkillRepo->getSkillsNameByUserID($this->user_1->getId())
        );
        $this->assertEquals(
            [$this->skill_3->getName()],
            $this->userSkillRepo->getSkillsNameByUserID($this->user_2->getId())
        );
    }

    /** @test saveAsNew */
    public function canRemoveSkillFromUser()
    {
        $this->addSkillToUser($this->skill_1, $this->user_1);
        $this->addSkillToUser($this->skill_2, $this->user_1);
        $this->addSkillToUser($this->skill_3, $this->user_1);

        $this->assertCount(3, $this->userSkillRepo->getByUserID($this->user_1->getId()));

        $this->removeSkillFromUser($this->skill_3, $this->user_1);

        $this->assertCount(2, $this->userSkillRepo->getByUserID($this->user_1->getId()));
    }

    /** @test saveAsNew */
    public function cannotRemoveNonexistentSkillFromUser()
    {
        $this->setExpectedException(\DSI\NotFound::class);
        $this->removeSkillFromUser($this->skill_1, $this->user_1);
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

    /**
     * @param $skill
     * @param $user
     * @return \DSI\Entity\UserSkill
     */
    private function addSkillToUser($skill, $user)
    {
        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setSkill($skill);
        $userSkill->setUser($user);
        $this->userSkillRepo->add($userSkill);
    }

    /**
     * @param $skill
     * @param $user
     * @return \DSI\Entity\UserSkill
     */
    private function removeSkillFromUser($skill, $user)
    {
        $userSkill = new \DSI\Entity\UserSkill();
        $userSkill->setSkill($skill);
        $userSkill->setUser($user);
        $this->userSkillRepo->remove($userSkill);
    }
}