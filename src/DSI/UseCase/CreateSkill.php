<?php

namespace DSI\UseCase;

use DSI\Entity\Skill;
use DSI\Repository\SkillRepo;
use DSI\Service\ErrorHandler;

class CreateSkill
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var SkillRepo */
    private $skillRepo;

    /** @var Skill */
    private $skill;

    /** @var CreateSkill_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CreateSkill_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->skillRepo = new SkillRepo();

        if($this->skillRepo->nameExists($this->data()->name)){
            $this->errorHandler->addTaggedError('skill', __('Skill name already exists'));
            $this->errorHandler->throwIfNotEmpty();
        }

        $skill = new Skill();
        $skill->setName((string)$this->data()->name);
        $this->skillRepo->saveAsNew($skill);

        $this->skill = $skill;
    }

    /**
     * @return CreateSkill_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return Skill
     */
    public function getSkill()
    {
        return $this->skill;
    }
}

class CreateSkill_Data
{
    /** @var string */
    public $name;
}