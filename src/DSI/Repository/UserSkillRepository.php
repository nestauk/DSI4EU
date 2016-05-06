<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\UserSkill;
use DSI\NotFound;
use DSI\Service\SQL;

class UserSkillRepository
{
    /** @var UserRepository */
    private $userRepo;

    /** @var SkillRepository */
    private $skillRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->skillRepo = new SkillRepository();
    }

    public function add(UserSkill $userSkill)
    {
        $query = new SQL("SELECT userID 
            FROM `user-skills`
            WHERE `userID` = '{$userSkill->getUserID()}'
            AND `skillID` = '{$userSkill->getSkillID()}'
            LIMIT 1
        ");
        if ($query->fetch('userID') > 0)
            throw new DuplicateEntry("userID: {$userSkill->getUserID()} / skillID: {$userSkill->getSkillID()}");

        $insert = array();
        $insert[] = "`userID` = '" . (int)($userSkill->getUserID()) . "'";
        $insert[] = "`skillID` = '" . (int)($userSkill->getSkillID()) . "'";

        $query = new SQL("INSERT INTO `user-skills` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(UserSkill $userSkill)
    {
        $query = new SQL("SELECT userID 
            FROM `user-skills`
            WHERE `userID` = '{$userSkill->getUserID()}'
            AND `skillID` = '{$userSkill->getSkillID()}'
            LIMIT 1
        ");
        if (!$query->fetch('userID'))
            throw new NotFound("userID: {$userSkill->getUserID()} / skillID: {$userSkill->getSkillID()}");

        $insert = array();
        $insert[] = "`userID` = '" . (int)($userSkill->getUserID()) . "'";
        $insert[] = "`skillID` = '" . (int)($userSkill->getSkillID()) . "'";

        $query = new SQL("DELETE FROM `user-skills` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $userID
     * @return \DSI\Entity\UserSkill[]
     */
    public function getByUserID(int $userID)
    {
        return $this->getUserSkillsWhere([
            "`userID` = '{$userID}'"
        ]);
    }

    /**
     * @param int $userID
     * @return \int[]
     */
    public function getSkillIDsForUser(int $userID)
    {
        $where = [
            "`userID` = '{$userID}'"
        ];

        /** @var int[] $skillIDs */
        $skillIDs = [];
        $query = new SQL("SELECT skillID 
            FROM `user-skills`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY skillID
        ");
        foreach ($query->fetch_all() AS $dbUserSkill) {
            $skillIDs[] = $dbUserSkill['skillID'];
        }

        return $skillIDs;
    }

    /**
     * @param int $skillID
     * @return \DSI\Entity\UserSkill[]
     */
    public function getBySkillID(int $skillID)
    {
        return $this->getUserSkillsWhere([
            "`skillID` = '{$skillID}'"
        ]);
    }

    /**
     * @param int $skillID
     * @return \int[]
     */
    public function getUserIDsForSkill(int $skillID)
    {
        $where = [
            "`skillID` = '{$skillID}'"
        ];

        /** @var int[] $userIDs */
        $userIDs = [];
        $query = new SQL("SELECT userID 
            FROM `user-skills`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY userID
        ");
        foreach ($query->fetch_all() AS $dbUserSkill) {
            $userIDs[] = $dbUserSkill['userID'];
        }

        return $userIDs;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `user-skills`");
        $query->query();
    }

    /**
     * @param $where
     * @return \DSI\Entity\UserSkill[]
     */
    private function getUserSkillsWhere($where)
    {
        /** @var UserSkill[] $userSkills */
        $userSkills = [];
        $query = new SQL("SELECT userID, skillID 
            FROM `user-skills`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbUserSkill) {
            $userSkill = new UserSkill();
            $userSkill->setUser($this->userRepo->getById($dbUserSkill['userID']));
            $userSkill->setSkill($this->skillRepo->getById($dbUserSkill['skillID']));
            $userSkills[] = $userSkill;
        }

        return $userSkills;
    }

    public function userHasSkillName(int $userID, string $skillName)
    {
        $userSkills = $this->getByUserID($userID);
        foreach ($userSkills AS $userSkill) {
            if ($skillName == $userSkill->getSkill()->getName()) {
                return true;
            }
        }

        return false;
    }

    public function getSkillsNameByUserID(int $userID)
    {
        $query = new SQL("SELECT skill 
            FROM `skills` 
            LEFT JOIN `user-skills` ON `skills`.`id` = `user-skills`.`skillID`
            WHERE `user-skills`.`userID` = '{$userID}'
            ORDER BY `skills`.`skill`
        ");
        return $query->fetch_all('skill');
    }
}