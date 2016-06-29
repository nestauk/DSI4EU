<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\OrganisationType;
use DSI\Service\SQL;

class OrganisationTypeRepository
{
    /** @var self */
    private static $objects = [];

    public function insert(OrganisationType $organisationType)
    {
        $insert = array();
        $insert[] = "`name` = '" . addslashes($organisationType->getName()) . "'";
        $insert[] = "`order` = '" . addslashes($organisationType->getOrder()) . "'";
        $query = new SQL("INSERT INTO `organisation-types` SET " . implode(', ', $insert) . "");
        $query->query();

        $organisationType->setId($query->insert_id());

        self::$objects[$organisationType->getId()] = $organisationType;
    }

    public function save(OrganisationType $organisationType)
    {
        $query = new SQL("SELECT id FROM `organisation-types` WHERE id = '{$organisationType->getId()}' LIMIT 1");
        $existingOrg = $query->fetch();
        if (!$existingOrg)
            throw new DSI\NotFound('organisationID: ' . $organisationType->getId());

        $insert = array();
        $insert[] = "`name` = '" . addslashes($organisationType->getName()) . "'";
        $insert[] = "`order` = '" . addslashes($organisationType->getOrder()) . "'";

        $query = new SQL("UPDATE `organisation-types` SET " . implode(', ', $insert) . " WHERE `id` = '{$organisationType->getId()}'");
        $query->query();

        self::$objects[$organisationType->getId()] = $organisationType;
    }

    public function getById(int $id): OrganisationType
    {
        if (isset(self::$objects[$id]))
            return self::$objects[$id];

        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    public function getByName(string $name): OrganisationType
    {
        return $this->getObjectWhere([
            "`name` = '" . addslashes($name) . "'"
        ]);
    }

    private function buildObjectFromData($organisationType)
    {
        $organisationTypeObj = new OrganisationType();
        $organisationTypeObj->setId($organisationType['id']);
        $organisationTypeObj->setName($organisationType['name']);
        $organisationTypeObj->setOrder($organisationType['order']);

        self::$objects[$organisationTypeObj->getId()] = $organisationTypeObj;

        return $organisationTypeObj;
    }

    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `organisation-types`");
        $query->query();
        self::$objects = [];
    }

    private function getObjectWhere($where)
    {
        $objects = $this->getObjectsWhere($where);
        if (count($objects) < 1)
            throw new DSI\NotFound();

        return $objects[0];
    }

    /**
     * @param $where
     * @return array
     */
    private function getObjectsWhere($where)
    {
        $organisationTypes = [];
        $query = new SQL("SELECT 
            id, `name`, `order`
          FROM `organisation-types`
          WHERE " . implode(' AND ', $where) . "
          ORDER BY `order`");
        foreach ($query->fetch_all() AS $dbOrganisationType) {
            $organisationTypes[] = $this->buildObjectFromData($dbOrganisationType);
        }
        return $organisationTypes;
    }
}