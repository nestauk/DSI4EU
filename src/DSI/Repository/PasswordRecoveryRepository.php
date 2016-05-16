<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\PasswordRecovery;
use DSI\Service\SQL;

class PasswordRecoveryRepository
{
    public function insert(PasswordRecovery $passwordRecovery)
    {
        $insert = array();
        $insert[] = "`userID` = '" . addslashes($passwordRecovery->getUser()->getId()) . "'";
        $insert[] = "`code` = '" . addslashes($passwordRecovery->getCode()) . "'";
        $insert[] = "`expires` = DATE_ADD(NOW(), INTERVAL 1 DAY)";
        $insert[] = "`isUsed` = '" . (int)($passwordRecovery->isUsed()) . "'";

        $query = new SQL("INSERT INTO `password-recovery` SET " . implode(', ', $insert) . "");
        $query->query();

        $passwordRecovery->setId($query->insert_id());
    }

    public function save(PasswordRecovery $passwordRecovery)
    {
        $query = new SQL("SELECT id FROM `password-recovery` WHERE id = '{$passwordRecovery->getId()}' LIMIT 1");
        $existingProject = $query->fetch();
        if (!$existingProject)
            throw new DSI\NotFound('projectID: ' . $passwordRecovery->getId());

        $insert = array();
        $insert[] = "`userID` = '" . addslashes($passwordRecovery->getUser()->getId()) . "'";
        $insert[] = "`code` = '" . addslashes($passwordRecovery->getCode()) . "'";
        $insert[] = "`isUsed` = '" . (int)($passwordRecovery->isUsed()) . "'";

        $query = new SQL("UPDATE `password-recovery` SET " . implode(', ', $insert) . " WHERE `id` = '{$passwordRecovery->getId()}'");
        $query->query();
    }

    public function getById(int $id): PasswordRecovery
    {
        return $this->getPasswordRecoveryWhere([
            "`id` = {$id}"
        ]);
    }

    private function buildPasswordRecoveryFromData($dbPasswordRecovery)
    {
        $passwordRecovery = new PasswordRecovery();
        $passwordRecovery->setId($dbPasswordRecovery['id']);
        $passwordRecovery->setUser(
            (new UserRepository())->getById($dbPasswordRecovery['userID'])
        );
        $passwordRecovery->setCode($dbPasswordRecovery['code']);
        $passwordRecovery->setExpires($dbPasswordRecovery['expires']);
        $passwordRecovery->setIsUsed($dbPasswordRecovery['isUsed']);

        return $passwordRecovery;
    }

    public function getAll()
    {
        $where = ["1"];
        $projects = [];
        $query = new SQL("SELECT 
            id, userID, code, expires, isUsed
          FROM `password-recovery` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbProject) {
            $projects[] = $this->buildPasswordRecoveryFromData($dbProject);
        }
        return $projects;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `password-recovery`");
        $query->query();
    }

    private function getPasswordRecoveryWhere($where)
    {
        $query = new SQL("SELECT 
              id, userID, code, expires, isUsed
            FROM `password-recovery` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        $dbPasswordRecovery = $query->fetch();
        if (!$dbPasswordRecovery) {
            throw new DSI\NotFound();
        }

        return $this->buildPasswordRecoveryFromData($dbPasswordRecovery);
    }
}