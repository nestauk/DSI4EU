<?php

namespace DSI\Repository;

use DSI\Entity\AppRegistration;
use DSI\NotFound;
use DSI\Service\SQL;

class AppRegistrationRepo
{
    private $dbTable = 'app-registrations';

    public function insert(AppRegistration $appRegistration)
    {
        $insert = array();
        $insert[] = "`loggedInUserID` = '" . ((int)$appRegistration->getLoggedInUserID()) . "'";
        $insert[] = "`registeredUserID` = '" . ((int)$appRegistration->getRegisteredUserID()) . "'";

        $query = new SQL("INSERT INTO `{$this->dbTable}` SET " . implode(', ', $insert));
        $query->query();

        $appRegistration->setId($query->insert_id());
    }

    public function getById(int $id)
    {
        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    /** @return AppRegistration[] */
    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `{$this->dbTable}`");
        $query->query();
    }

    /**
     * @param $where
     * @return AppRegistration
     * @throws NotFound
     */
    private function getObjectWhere($where)
    {
        $objects = $this->getObjectsWhere($where);
        if (count($objects) < 1)
            throw new NotFound();

        return $objects[0];
    }

    /**
     * @param $where
     * @return AppRegistration[]
     */
    private function getObjectsWhere($where)
    {
        $registrations = [];
        $userRepository = new UserRepo();
        $query = new SQL("SELECT 
            id, loggedInUserID, registeredUserID
          FROM `{$this->dbTable}` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbRegistrations) {
            $appRegistration = new AppRegistration();
            $appRegistration->setId($dbRegistrations['id']);
            if ($dbRegistrations['loggedInUserID'])
                $appRegistration->setLoggedInUser($userRepository->getById($dbRegistrations['loggedInUserID']));
            if ($dbRegistrations['registeredUserID'])
                $appRegistration->setRegisteredUser($userRepository->getById($dbRegistrations['registeredUserID']));
            $registrations[] = $appRegistration;
        }
        return $registrations;
    }

    /**
     * @return string
     */
    public function getDbTable(): string
    {
        return (string)$this->dbTable;
    }
}