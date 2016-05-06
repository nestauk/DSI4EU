<?php

namespace DSI\Repository;

use DSI;
use DSI\Service\SQL;
use DSI\Entity\Skill;

class SkillRepository
{
    public function saveAsNew(Skill $skill)
    {
        if (!$skill->getName())
            throw new DSI\NotEnoughData('name');
        if ($this->nameExists($skill->getName()))
            throw new DSI\DuplicateEntry('name');

        $insert = array();
        $insert[] = "`skill` = '" . addslashes($skill->getName()) . "'";

        $query = new SQL("INSERT INTO `skills` SET " . implode(', ', $insert) . "");
        $query->query();

        $skill->setId($query->insert_id());
    }

    public function save(Skill $skill)
    {
        if (!$skill->getName())
            throw new DSI\NotEnoughData('name');
        if ($this->nameExists($skill->getName()))
            if ($this->getByName($skill->getName())->getId() != $skill->getId())
                throw new DSI\DuplicateEntry('name');

        $query = new SQL("SELECT id FROM `skills` WHERE id = '{$skill->getId()}' LIMIT 1");
        $existingSkill = $query->fetch();
        if (!$existingSkill)
            throw new DSI\NotFound('skillID: ' . $skill->getId());

        $insert = array();
        $insert[] = "`skill` = '" . addslashes($skill->getName()) . "'";

        $query = new SQL("UPDATE `skills` SET " . implode(', ', $insert) . " WHERE `id` = '{$skill->getId()}'");
        $query->query();
    }

    public function getById(int $id): Skill
    {
        return $this->getSkillWhere([
            "`id` = {$id}"
        ]);
    }

    public function nameExists(string $name): bool
    {
        return $this->checkExistingSkillWhere([
            "`skill` = '" . addslashes($name) . "'"
        ]);
    }

    public function getByName(string $name): Skill
    {
        return $this->getSkillWhere([
            "`skill` = '" . addslashes($name) . "'"
        ]);
    }

    /** @return Skill[] */
    public function getAll()
    {
        $where = ["1"];
        $skills = [];
        $query = new SQL("SELECT 
            id, skill
          FROM `skills` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbSkill) {
            $skills[] = $this->buildSkillFromData($dbSkill);
        }
        return $skills;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `skills`");
        $query->query();
    }


    /**
     * @param $skill
     * @return Skill
     */
    private function buildSkillFromData($skill)
    {
        $skillObj = new Skill();
        $skillObj->setId($skill['id']);
        $skillObj->setName($skill['skill']);
        return $skillObj;
    }

    /**
     * @param $where
     * @return Skill
     * @throws DSI\NotFound
     */
    private function getSkillWhere($where)
    {
        $query = new SQL("SELECT 
              id, skill 
            FROM `skills` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        $dbSkill = $query->fetch();
        if (!$dbSkill) {
            throw new DSI\NotFound();
        }

        return $this->buildSkillFromData($dbSkill);
    }

    /**
     * @param $where
     * @return bool
     */
    private function checkExistingSkillWhere($where)
    {
        $query = new SQL("SELECT id FROM `skills` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        return ($query->fetch() ? true : false);
    }
}