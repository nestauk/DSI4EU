<?php

namespace DSI\UseCase;

use DSI\Entity\Skill;
use DSI\Entity\UserSkill;
use DSI\Repository\SkillRepo;
use DSI\Repository\UserRepo;
use DSI\Repository\UserSkillRepo;
use DSI\Service\ErrorHandler;

class RemoveSkillFromUser
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var UserSkillRepo */
    private $userSkillRepo;

    /** @var RemoveSkillFromUser_Data */
    private $data;

    public function __construct()
    {
        $this->data = new RemoveSkillFromUser_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->userSkillRepo = new UserSkillRepo();

        $skillRepo = new SkillRepo();
        $userRepo = new UserRepo();

        if ($skillRepo->nameExists($this->data()->skill)) {
            $skill = $skillRepo->getByName($this->data()->skill);
        } else {
            $createSkill = new CreateSkill();
            $createSkill->data()->name = $this->data()->skill;
            $createSkill->exec();
            $skill = $createSkill->getSkill();
        }

        if (!$this->userSkillRepo->userHasSkillName($this->data()->userID, $this->data()->skill)) {
            $this->errorHandler->addTaggedError('skill', 'The user does not have this skill');
            $this->errorHandler->throwIfNotEmpty();
        }

        $userSkill = new UserSkill();
        $userSkill->setSkill($skill);
        $userSkill->setUser($userRepo->getById($this->data()->userID));
        $this->userSkillRepo->remove($userSkill);
    }

    /**
     * @return RemoveSkillFromUser_Data
     */
    public function data()
    {
        return $this->data;
    }
}

class RemoveSkillFromUser_Data
{
    /** @var string */
    public $skill;

    /** @var int */
    public $userID;
}