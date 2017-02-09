<?php

namespace DSI\Repository;

use DSI;
use DSI\Service\SQL;
use DSI\Entity\CountryRegion;

class CountryRegionRepository
{
    /** @var self */
    private static $objects = [];

    public function insert(CountryRegion $countryRegion)
    {
        if (!$countryRegion->getName())
            throw new DSI\NotEnoughData('name');
        elseif (!$countryRegion->hasCountry())
            throw new DSI\NotEnoughData('country');
        elseif ($this->nameExists($countryRegion->getCountry()->getId(), $countryRegion->getName()))
            throw new DSI\DuplicateEntry('name');

        $insert = array();
        $insert[] = "`countryID` = '" . addslashes($countryRegion->getCountry()->getId()) . "'";
        $insert[] = "`name` = '" . addslashes($countryRegion->getName()) . "'";
        $insert[] = "`lat` = '" . (float)($countryRegion->getLatitude()) . "'";
        $insert[] = "`lng` = '" . (float)($countryRegion->getLongitude()) . "'";

        $query = new SQL("INSERT INTO `country-regions` SET " . implode(', ', $insert) . "");
        $query->query();

        $countryRegion->setId($query->insert_id());

        self::$objects[$countryRegion->getId()] = $countryRegion;

        OrganisationRepositoryInAPC::resetCache();
        ProjectRepositoryInAPC::resetCache();
    }

    public function save(CountryRegion $countryRegion)
    {
        if (!$countryRegion->getName())
            throw new DSI\NotEnoughData('name');
        elseif ($this->nameExists($countryRegion->getCountry()->getId(), $countryRegion->getName()))
            if ($this->getByName($countryRegion->getCountry()->getId(), $countryRegion->getName())->getId() != $countryRegion->getId())
                throw new DSI\DuplicateEntry('name');

        $query = new SQL("SELECT id FROM `country-regions` WHERE id = '{$countryRegion->getId()}' LIMIT 1");
        $existingCountryRegion = $query->fetch();
        if (!$existingCountryRegion)
            throw new DSI\NotFound('countryRegionID: ' . $countryRegion->getId());

        $insert = array();
        $insert[] = "`countryID` = '" . addslashes($countryRegion->getCountry()->getId()) . "'";
        $insert[] = "`name` = '" . addslashes($countryRegion->getName()) . "'";
        $insert[] = "`lat` = '" . (float)($countryRegion->getLatitude()) . "'";
        $insert[] = "`lng` = '" . (float)($countryRegion->getLongitude()) . "'";

        $query = new SQL("UPDATE `country-regions` SET " . implode(', ', $insert) . " WHERE `id` = '{$countryRegion->getId()}'");
        $query->query();

        self::$objects[$countryRegion->getId()] = $countryRegion;

        OrganisationRepositoryInAPC::resetCache();
        ProjectRepositoryInAPC::resetCache();
    }

    public function getById(int $id): CountryRegion
    {
        if (isset(self::$objects[$id]))
            return self::$objects[$id];

        return $this->getCountryRegionWhere([
            "`id` = {$id}"
        ]);
    }

    public function nameExists(int $countryID, $name): bool
    {
        return $this->checkExistingCountryRegionWhere([
            "`countryID` = '" . addslashes($countryID) . "'",
            "`name` = '" . addslashes($name) . "'",
        ]);
    }

    public function getByName(int $countryID, string $name): CountryRegion
    {
        return $this->getCountryRegionWhere([
            "`countryID` = '" . addslashes($countryID) . "'",
            "`name` = '" . addslashes($name) . "'"
        ]);
    }

    /** @return CountryRegion[] */
    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    /**
     * @param int $countryID
     * @return DSI\Entity\CountryRegion[]
     */
    public function getAllByCountryId(int $countryID)
    {
        return $this->getObjectsWhere([
            "`countryID` = {$countryID}"
        ]);
    }

    /**
     * @param array $where
     * @return DSI\Entity\CountryRegion[]
     */
    private function getObjectsWhere($where)
    {
        $query = new SQL("SELECT 
            `id`, `countryID`, `name`, `lat`, `lng`
          FROM `country-regions` WHERE " . implode(' AND ', $where) . "
          ORDER BY `name`");

        return array_map(function ($dbCountryRegion) {
            return $this->buildCountryRegionFromData($dbCountryRegion);
        }, $query->fetch_all());
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `country-regions`");
        $query->query();
        self::$objects = [];
    }

    /**
     * @param $countryRegion
     * @return CountryRegion
     */
    private function buildCountryRegionFromData($countryRegion)
    {
        $countryObj = new CountryRegion();
        $countryObj->setId($countryRegion['id']);
        $countryObj->setCountry(
            (new CountryRepository())->getById($countryRegion['countryID'])
        );
        $countryObj->setName($countryRegion['name']);
        $countryObj->setLatitude($countryRegion['lat']);
        $countryObj->setLongitude($countryRegion['lng']);

        self::$objects[$countryObj->getId()] = $countryObj;

        return $countryObj;
    }

    /**
     * @param $where
     * @return CountryRegion
     * @throws DSI\NotFound
     */
    private function getCountryRegionWhere($where)
    {
        $query = new SQL("SELECT 
              `id`, `countryID`, `name`, `lat`, `lng`
            FROM `country-regions` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        $dbCountryRegion = $query->fetch();
        if (!$dbCountryRegion) {
            throw new DSI\NotFound();
        }

        return $this->buildCountryRegionFromData($dbCountryRegion);
    }

    /**
     * @param $where
     * @return bool
     */
    private function checkExistingCountryRegionWhere($where)
    {
        $query = new SQL("SELECT id FROM `country-regions` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        return ($query->fetch() ? true : false);
    }
}