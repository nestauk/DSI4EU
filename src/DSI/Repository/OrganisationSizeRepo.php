<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\OrganisationSize;
use DSI\Service\SQL;

class OrganisationSizeRepo
{
    /** @var self */
    private static $objects = [];

    public function insert(OrganisationSize $organisationSize)
    {
        $insert = array();
        $insert[] = "`name` = '" . addslashes($organisationSize->getName()) . "'";
        $insert[] = "`order` = '" . addslashes($organisationSize->getOrder()) . "'";
        $query = new SQL("INSERT INTO `organisation-sizes` SET " . implode(', ', $insert) . "");
        $query->query();

        $organisationSize->setId($query->insert_id());

        self::$objects[$organisationSize->getId()] = $organisationSize;
    }

    public function save(OrganisationSize $organisationSize)
    {
        $query = new SQL("SELECT id FROM `organisation-sizes` WHERE id = '{$organisationSize->getId()}' LIMIT 1");
        $existingOrg = $query->fetch();
        if (!$existingOrg)
            throw new DSI\NotFound('organisationID: ' . $organisationSize->getId());

        $insert = array();
        $insert[] = "`name` = '" . addslashes($organisationSize->getName()) . "'";
        $insert[] = "`order` = '" . addslashes($organisationSize->getOrder()) . "'";

        $query = new SQL("UPDATE `organisation-sizes` SET " . implode(', ', $insert) . " WHERE `id` = '{$organisationSize->getId()}'");
        $query->query();

        self::$objects[$organisationSize->getId()] = $organisationSize;
    }

    public function getById(int $id): OrganisationSize
    {
        if (isset(self::$objects[$id]))
            return self::$objects[$id];

        return $this->getElementWhere([
            "`id` = {$id}"
        ]);
    }

    public function getByName(string $name): OrganisationSize
    {
        return $this->getElementWhere([
            "`name` = '" . addslashes($name) . "'"
        ]);
    }

    private function buildObjectFromData($organisationSize)
    {
        $organisationSizeObj = new OrganisationSize();
        $organisationSizeObj->setId($organisationSize['id']);
        $organisationSizeObj->setName($organisationSize['name']);
        $organisationSizeObj->setOrder($organisationSize['order']);

        self::$objects[$organisationSizeObj->getId()] = $organisationSizeObj;

        return $organisationSizeObj;
    }

    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `organisation-sizes`");
        $query->query();
        self::$objects = [];
    }

    private function getElementWhere($where)
    {
        $objects = $this->getObjectsWhere($where);
        if(count($objects) < 1)
            throw new DSI\NotFound();

        return $objects[0];
    }

    /**
     * @param $where
     * @return array
     */
    private function getObjectsWhere($where)
    {
        $organisationSizes = [];
        $query = new SQL("SELECT 
            id, name, `order`
          FROM `organisation-sizes`
          WHERE " . implode(' AND ', $where) . "
          ORDER BY `order`");
        foreach ($query->fetch_all() AS $dbOrganisationSize) {
            $organisationSizes[] = $this->buildObjectFromData($dbOrganisationSize);
        }
        return $organisationSizes;
    }
}