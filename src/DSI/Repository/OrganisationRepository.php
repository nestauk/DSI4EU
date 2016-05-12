<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\Organisation;
use DSI\Service\SQL;

class OrganisationRepository
{
    public function saveAsNew(Organisation $organisation)
    {
        $insert = array();
        $insert[] = "`ownerID` = '" . addslashes($organisation->getOwner()->getId()) . "'";
        $insert[] = "`name` = '" . addslashes($organisation->getName()) . "'";
        $insert[] = "`description` = '" . addslashes($organisation->getDescription()) . "'";
        if ($organisation->getCountry())
            $insert[] = "`countryID` = '" . addslashes($organisation->getCountry()->getId()) . "'";
        if ($organisation->getCountryRegion())
            $insert[] = "`countryRegionID` = '" . addslashes($organisation->getCountryRegion()->getId()) . "'";
        if ($organisation->getOrganisationType())
            $insert[] = "`organisationTypeID` = '" . addslashes($organisation->getOrganisationTypeId()) . "'";
        if ($organisation->getOrganisationSize())
            $insert[] = "`organisationSizeID` = '" . addslashes($organisation->getOrganisationSizeId()) . "'";

        $query = new SQL("INSERT INTO `organisations` SET " . implode(', ', $insert) . "");
        $query->query();

        $organisation->setId($query->insert_id());
    }

    public function save(Organisation $organisation)
    {
        $query = new SQL("SELECT id FROM `organisations` WHERE id = '{$organisation->getId()}' LIMIT 1");
        $existingOrg = $query->fetch();
        if (!$existingOrg)
            throw new DSI\NotFound('organisationID: ' . $organisation->getId());

        $insert = array();
        $insert[] = "`ownerID` = '" . addslashes($organisation->getOwner()->getId()) . "'";
        $insert[] = "`name` = '" . addslashes($organisation->getName()) . "'";
        $insert[] = "`description` = '" . addslashes($organisation->getDescription()) . "'";
        if ($organisation->getCountry())
            $insert[] = "`countryID` = '" . addslashes($organisation->getCountry()->getId()) . "'";
        if ($organisation->getCountryRegion())
            $insert[] = "`countryRegionID` = '" . addslashes($organisation->getCountryRegion()->getId()) . "'";
        if ($organisation->getOrganisationType())
            $insert[] = "`organisationTypeID` = '" . addslashes($organisation->getOrganisationTypeId()) . "'";
        if ($organisation->getOrganisationSize())
            $insert[] = "`organisationSizeID` = '" . addslashes($organisation->getOrganisationSizeId()) . "'";

        $query = new SQL("UPDATE `organisations` SET " . implode(', ', $insert) . " WHERE `id` = '{$organisation->getId()}'");
        $query->query();
    }

    public function getById(int $id): Organisation
    {
        return $this->getElementWhere([
            "`id` = {$id}"
        ]);
    }

    private function buildObjectFromData($organisation)
    {
        $organisationObj = new Organisation();
        $organisationObj->setId($organisation['id']);

        $organisationObj->setOwner(
            (new UserRepository())->getById($organisation['ownerID'])
        );
        $organisationObj->setName($organisation['name']);
        $organisationObj->setDescription($organisation['description']);
        if ($organisation['countryRegionID']) {
            $organisationObj->setCountryRegion(
                (new CountryRegionRepository())->getById($organisation['countryRegionID'])
            );
        }
        if ($organisation['organisationTypeID']) {
            $organisationObj->setOrganisationType(
                (new OrganisationTypeRepository())->getById($organisation['organisationTypeID'])
            );
        }
        if ($organisation['organisationSizeID']) {
            $organisationObj->setOrganisationSize(
                (new OrganisationSizeRepository())->getById($organisation['organisationSizeID'])
            );
        }

        return $organisationObj;
    }

    public function getAll()
    {
        $where = ["1"];
        $organisations = [];
        $query = new SQL("SELECT 
            id, ownerID, name, description
          , countryRegionID, organisationTypeID, organisationSizeID
          FROM `organisations` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbOrganisation) {
            $organisations[] = $this->buildObjectFromData($dbOrganisation);
        }
        return $organisations;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `organisations`");
        $query->query();
    }

    private function getElementWhere($where)
    {
        $query = new SQL("SELECT 
              id, ownerID, name, description
            , countryRegionID, organisationTypeID, organisationSizeID
            FROM `organisations` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        $dbOrganisation = $query->fetch();
        if (!$dbOrganisation) {
            throw new DSI\NotFound();
        }

        return $this->buildObjectFromData($dbOrganisation);
    }
}