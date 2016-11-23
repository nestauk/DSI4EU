<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\Organisation;
use DSI\Service\SQL;

class OrganisationRepository
{
    public function insert(Organisation $organisation)
    {
        $insert = array();
        $insert[] = "`ownerID` = '" . addslashes($organisation->getOwnerID()) . "'";
        $insert[] = "`name` = '" . addslashes($organisation->getName()) . "'";
        $insert[] = "`url` = '" . addslashes($organisation->getUrl()) . "'";
        $insert[] = "`shortDescription` = '" . addslashes($organisation->getShortDescription()) . "'";
        $insert[] = "`description` = '" . addslashes($organisation->getDescription()) . "'";
        if ($organisation->getCountry())
            $insert[] = "`countryID` = '" . addslashes($organisation->getCountry()->getId()) . "'";
        if ($organisation->getRegion())
            $insert[] = "`countryRegionID` = '" . addslashes($organisation->getRegion()->getId()) . "'";
        $insert[] = "`address` = '" . addslashes($organisation->getAddress()) . "'";
        if ($organisation->getType())
            $insert[] = "`organisationTypeID` = '" . addslashes($organisation->getTypeId()) . "'";
        if ($organisation->getSize())
            $insert[] = "`organisationSizeID` = '" . addslashes($organisation->getSizeId()) . "'";
        $insert[] = "`startDate` = '" . addslashes($organisation->getStartDate()) . "'";
        $insert[] = "`logo` = '" . addslashes($organisation->getLogo()) . "'";
        $insert[] = "`headerImage` = '" . addslashes($organisation->getHeaderImage()) . "'";
        $insert[] = "`projectsCount` = '" . (int)($organisation->getProjectsCount()) . "'";
        $insert[] = "`partnersCount` = '" . (int)($organisation->getPartnersCount()) . "'";
        $insert[] = "`importID` = '" . addslashes($organisation->getImportID()) . "'";

        $query = new SQL("INSERT INTO `organisations` SET " . implode(', ', $insert) . "");
        $query->query();

        $organisation->setId($query->insert_id());
        OrganisationRepositoryInAPC::resetCache();
    }

    public function save(Organisation $organisation)
    {
        $query = new SQL("SELECT id FROM `organisations` WHERE id = '{$organisation->getId()}' LIMIT 1");
        $existingOrg = $query->fetch();
        if (!$existingOrg)
            throw new DSI\NotFound('organisationID: ' . $organisation->getId());

        $insert = array();
        $insert[] = "`ownerID` = '" . addslashes($organisation->getOwnerID()) . "'";
        $insert[] = "`name` = '" . addslashes($organisation->getName()) . "'";
        $insert[] = "`url` = '" . addslashes($organisation->getUrl()) . "'";
        $insert[] = "`shortDescription` = '" . addslashes($organisation->getShortDescription()) . "'";
        $insert[] = "`description` = '" . addslashes($organisation->getDescription()) . "'";
        if ($organisation->getCountry())
            $insert[] = "`countryID` = '" . addslashes($organisation->getCountry()->getId()) . "'";
        if ($organisation->getRegion())
            $insert[] = "`countryRegionID` = '" . addslashes($organisation->getRegion()->getId()) . "'";
        $insert[] = "`address` = '" . addslashes($organisation->getAddress()) . "'";
        if ($organisation->getType())
            $insert[] = "`organisationTypeID` = '" . addslashes($organisation->getTypeId()) . "'";
        if ($organisation->getSize())
            $insert[] = "`organisationSizeID` = '" . addslashes($organisation->getSizeId()) . "'";
        $insert[] = "`startDate` = '" . addslashes($organisation->getStartDate()) . "'";
        $insert[] = "`created` = '" . addslashes($organisation->getCreationTime()) . "'";
        $insert[] = "`logo` = '" . addslashes($organisation->getLogo()) . "'";
        $insert[] = "`headerImage` = '" . addslashes($organisation->getHeaderImage()) . "'";
        $insert[] = "`projectsCount` = '" . (int)($organisation->getProjectsCount()) . "'";
        $insert[] = "`partnersCount` = '" . (int)($organisation->getPartnersCount()) . "'";
        $insert[] = "`importID` = '" . addslashes($organisation->getImportID()) . "'";

        $query = new SQL("UPDATE `organisations` SET " . implode(', ', $insert) . " WHERE `id` = '{$organisation->getId()}'");
        $query->query();
        OrganisationRepositoryInAPC::resetCache();
    }

    public function remove(Organisation $organisation)
    {
        $query = new SQL("SELECT id FROM `organisations` WHERE id = '{$organisation->getId()}' LIMIT 1");
        $existingOrg = $query->fetch();
        if (!$existingOrg)
            throw new DSI\NotFound('organisationID: ' . $organisation->getId());

        $query = new SQL("DELETE FROM `organisations` WHERE `id` = '{$organisation->getId()}'");
        $query->query();
        OrganisationRepositoryInAPC::resetCache();
    }

    /**
     * @param int $id
     * @return Organisation
     */
    public function getById(int $id): Organisation
    {
        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    public function getByImportID(string $importID): Organisation
    {
        return $this->getObjectWhere([
            "`importID` = '" . addslashes($importID) . "'"
        ]);
    }

    private function buildObjectFromData($organisation)
    {
        $organisationObj = new Organisation();
        $organisationObj->setId($organisation['id']);

        $organisationObj->setOwner(
            (new UserRepository())->getById($organisation['ownerID'])
        );
        $organisationObj->setName($organisation['name'] != '' ? $organisation['name'] : '-');
        $organisationObj->setUrl($organisation['url']);
        $organisationObj->setShortDescription($organisation['shortDescription']);
        $organisationObj->setDescription($organisation['description']);
        if ($organisation['countryRegionID']) {
            $organisationObj->setCountryRegion(
                (new CountryRegionRepository())->getById($organisation['countryRegionID'])
            );
        }
        $organisationObj->setAddress($organisation['address']);
        if ($organisation['organisationTypeID']) {
            $organisationObj->setType(
                (new OrganisationTypeRepository())->getById($organisation['organisationTypeID'])
            );
        }
        if ($organisation['organisationSizeID']) {
            $organisationObj->setSize(
                (new OrganisationSizeRepository())->getById($organisation['organisationSizeID'])
            );
        }
        $organisationObj->setStartDate($organisation['startDate'] != '0000-00-00' ? $organisation['startDate'] : NULL);
        $organisationObj->setCreationTime($organisation['created'] != '0000-00-00 00:00:00' ? $organisation['created'] : NULL);
        $organisationObj->setLogo($organisation['logo']);
        $organisationObj->setHeaderImage($organisation['headerImage']);
        $organisationObj->setProjectsCount($organisation['projectsCount']);
        $organisationObj->setPartnersCount($organisation['partnersCount']);
        $organisationObj->setImportID($organisation['importID']);

        return $organisationObj;
    }

    /**
     * @return Organisation[]
     */
    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `organisations`");
        $query->query();
        OrganisationRepositoryInAPC::resetCache();
    }

    /**
     * @param $where
     * @return Organisation
     * @throws DSI\NotFound
     */
    private function getObjectWhere($where)
    {
        $objects = $this->getObjectsWhere($where);

        if (count($objects) < 1)
            throw new DSI\NotFound();

        return $objects[0];
    }

    /**
     * @param $where
     * @param array $options
     * @return Organisation[]
     */
    private function getObjectsWhere($where, $options = [])
    {
        $organisations = [];
        $query = new SQL("SELECT 
            id, ownerID, name, url
          , shortDescription, description
          , countryRegionID, address
          , organisationTypeID, organisationSizeID
          , startDate, created, logo, headerImage
          , projectsCount, partnersCount
          , importID
          FROM `organisations` 
          WHERE " . implode(' AND ', $where) . "
          ORDER BY `name`
          " . ((isset($options['limit']) AND $options['limit'] > 0) ? "LIMIT {$options['limit']}" : '') . "
        ");
        foreach ($query->fetch_all() AS $dbOrganisation) {
            $organisations[] = $this->buildObjectFromData($dbOrganisation);
        }
        return $organisations;
    }

    /**
     * @return int
     */
    public function countAll()
    {
        return $this->countObjectsWhere(['1']);
    }

    /**
     * @param $where
     * @return int
     */
    private function countObjectsWhere($where)
    {
        $query = new SQL("SELECT count(id) AS `total`
          FROM `organisations`
          WHERE " . implode(' AND ', $where) . "
        ");
        return $query->fetch('total');
    }

    public function searchByTitle(string $name, int $limit = 0)
    {
        return $this->getObjectsWhere([
            "`name` LIKE '%" . addslashes($name) . "%'"
        ], [
            "limit" => $limit
        ]);
    }
}