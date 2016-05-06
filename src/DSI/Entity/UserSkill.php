<?php

namespace DSI\Entity;

class UserSkill
{
    /** @var User */
    private $user;

    /** @var Skill */
    private $skill;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getUserID(): int
    {
        return $this->user->getId();
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return Skill
     */
    public function getSkill(): Skill
    {
        return $this->skill;
    }

    /**
     * @return int
     */
    public function getSkillID(): int
    {
        return $this->skill->getId();
    }

    /**
     * @param Skill $skill
     */
    public function setSkill(Skill $skill)
    {
        $this->skill = $skill;
    }
}