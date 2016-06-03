<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\OrganisationSize;
use DSI\Service\SQL;

class OrganisationSizeRepository
{
    /** @var self */
    private static $objects = [];

    public function insert(OrganisationSize $organisationSize)
    {
        $insert = array();
        $insert[] = "`name` = '" . addslashes($organisationSize->getName()) . "'";
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

        self::$objects[$organisationSizeObj->getId()] = $organisationSizeObj;

        return $organisationSizeObj;
    }

    public function getAll()
    {
        $where = ["1"];
        $organisationSizes = [];
        $query = new SQL("SELECT 
            id, name
          FROM `organisation-sizes` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbOrganisationSize) {
            $organisationSizes[] = $this->buildObjectFromData($dbOrganisationSize);
        }
        return $organisationSizes;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `organisation-sizes`");
        $query->query();
        self::$objects = [];
    }

    private function getElementWhere($where)
    {
        $query = new SQL("SELECT 
              id, name
            FROM `organisation-sizes` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        $dbOrganisationSize = $query->fetch();
        if (!$dbOrganisationSize) {
            throw new DSI\NotFound();
        }

        return $this->buildObjectFromData($dbOrganisationSize);
    }
}