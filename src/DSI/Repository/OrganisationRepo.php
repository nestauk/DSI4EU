<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\Organisation;
use DSI\Service\SQL;

class OrganisationRepo
{
    public function __construct()
    {
    }

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
        $insert[] = "`isWaitingApproval` = '" . (bool)($organisation->isWaitingApproval()) . "'";
        $insert[] = "`isPublished` = '" . (bool)($organisation->isPublished()) . "'";

        $query = new SQL("INSERT INTO `organisations` SET " . implode(', ', $insert) . "");
        $query->query();

        $organisation->setId($query->insert_id());
        OrganisationRepoInAPC::resetCache();
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
        $insert[] = "`isWaitingApproval` = '" . (bool)($organisation->isWaitingApproval()) . "'";
        $insert[] = "`isPublished` = '" . (bool)($organisation->isPublished()) . "'";

        $query = new SQL("UPDATE `organisations` SET " . implode(', ', $insert) . " WHERE `id` = '{$organisation->getId()}'");
        $query->query();
        OrganisationRepoInAPC::resetCache();
    }

    public function remove(Organisation $organisation)
    {
        $query = new SQL("SELECT id FROM `organisations` WHERE id = '{$organisation->getId()}' LIMIT 1");
        $existingOrg = $query->fetch();
        if (!$existingOrg)
            throw new DSI\NotFound('organisationID: ' . $organisation->getId());

        $query = new SQL("DELETE FROM `organisations` WHERE `id` = '{$organisation->getId()}'");
        $query->query();
        OrganisationRepoInAPC::resetCache();
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
            (new UserRepo())->getById($organisation['ownerID'])
        );
        $organisationObj->setName($organisation['name'] != '' ? $organisation['name'] : '-');
        $organisationObj->setUrl($organisation['url']);
        $organisationObj->setShortDescription($organisation['shortDescription']);
        $organisationObj->setDescription($organisation['description']);
        if ($organisation['countryRegionID']) {
            $organisationObj->setCountryRegion(
                (new CountryRegionRepo())->getById($organisation['countryRegionID'])
            );
        }
        $organisationObj->setAddress($organisation['address']);
        if ($organisation['organisationTypeID']) {
            $organisationObj->setType(
                (new OrganisationTypeRepo())->getById($organisation['organisationTypeID'])
            );
        }
        if ($organisation['organisationSizeID']) {
            $organisationObj->setSize(
                (new OrganisationSizeRepo())->getById($organisation['organisationSizeID'])
            );
        }
        $organisationObj->setStartDate($organisation['startDate'] != '0000-00-00' ? $organisation['startDate'] : NULL);
        $organisationObj->setCreationTime($organisation['created'] != '0000-00-00 00:00:00' ? $organisation['created'] : NULL);
        $organisationObj->setLogo($organisation['logo']);
        $organisationObj->setHeaderImage($organisation['headerImage']);
        $organisationObj->setProjectsCount($organisation['projectsCount']);
        $organisationObj->setPartnersCount($organisation['partnersCount']);
        $organisationObj->setImportID($organisation['importID']);
        $organisationObj->setIsWaitingApproval($organisation['isWaitingApproval']);
        $organisationObj->setIsPublished($organisation['isPublished']);

        return $organisationObj;
    }

    /**
     * @return Organisation[]
     */
    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    /**
     * @param DSI\Entity\User $user
     * @return Organisation[]
     */
    public function getByUser(DSI\Entity\User $user)
    {
        return $this->getObjectsWhere([
            "`ownerID` = '" . $user->getId() . "'"
        ]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `organisations`");
        $query->query();
        OrganisationRepoInAPC::resetCache();
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
          , isWaitingApproval
          , isPublished
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