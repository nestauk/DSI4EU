<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\OrganisationType;
use DSI\Service\SQL;

class OrganisationTypeRepository
{
    public function saveAsNew(OrganisationType $organisationType)
    {
        $insert = array();
        $insert[] = "`name` = '" . addslashes($organisationType->getName()) . "'";
        $query = new SQL("INSERT INTO `organisation-types` SET " . implode(', ', $insert) . "");
        $query->query();

        $organisationType->setId($query->insert_id());
    }

    public function save(OrganisationType $organisationType)
    {
        $query = new SQL("SELECT id FROM `organisation-types` WHERE id = '{$organisationType->getId()}' LIMIT 1");
        $existingOrg = $query->fetch();
        if (!$existingOrg)
            throw new DSI\NotFound('organisationID: ' . $organisationType->getId());

        $insert = array();
        $insert[] = "`name` = '" . addslashes($organisationType->getName()) . "'";

        $query = new SQL("UPDATE `organisation-types` SET " . implode(', ', $insert) . " WHERE `id` = '{$organisationType->getId()}'");
        $query->query();
    }

    public function getById(int $id): OrganisationType
    {
        return $this->getElementWhere([
            "`id` = {$id}"
        ]);
    }

    private function buildObjectFromData($organisationType)
    {
        $organisationTypeObj = new OrganisationType();
        $organisationTypeObj->setId($organisationType['id']);
        $organisationTypeObj->setName($organisationType['name']);
        return $organisationTypeObj;
    }

    public function getAll()
    {
        $where = ["1"];
        $organisationTypes = [];
        $query = new SQL("SELECT 
            id, name
          FROM `organisation-types` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbOrganisationType) {
            $organisationTypes[] = $this->buildObjectFromData($dbOrganisationType);
        }
        return $organisationTypes;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `organisation-types`");
        $query->query();
    }

    private function getElementWhere($where)
    {
        $query = new SQL("SELECT 
              id, name
            FROM `organisation-types` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        $dbOrganisationType = $query->fetch();
        if (!$dbOrganisationType) {
            throw new DSI\NotFound();
        }

        return $this->buildObjectFromData($dbOrganisationType);
    }
}