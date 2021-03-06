<?php

namespace DSI\Repository;

use DSI;
use DSI\Service\SQL;
use DSI\Entity\Country;

class CountryRepo
{
    /** @var self */
    private static $objects = [];

    public function insert(Country $country)
    {
        if (!$country->getName())
            throw new DSI\NotEnoughData('name');
        if ($this->nameExists($country->getName()))
            throw new DSI\DuplicateEntry('name');

        $insert = array();
        $insert[] = "`name` = '" . addslashes($country->getName()) . "'";

        $query = new SQL("INSERT INTO `countries` SET " . implode(', ', $insert) . "");
        $query->query();

        $country->setId($query->insert_id());

        self::$objects[$country->getId()] = $country;

        OrganisationRepoInAPC::resetCache();
        ProjectRepoInAPC::resetCache();
    }

    public function save(Country $country)
    {
        if (!$country->getName())
            throw new DSI\NotEnoughData('name');
        if ($this->nameExists($country->getName()))
            if ($this->getByName($country->getName())->getId() != $country->getId())
                throw new DSI\DuplicateEntry('name');

        $query = new SQL("SELECT id FROM `countries` WHERE id = '{$country->getId()}' LIMIT 1");
        $existingCountry = $query->fetch();
        if (!$existingCountry)
            throw new DSI\NotFound('countryID: ' . $country->getId());

        $insert = array();
        $insert[] = "`name` = '" . addslashes($country->getName()) . "'";

        $query = new SQL("UPDATE `countries` SET " . implode(', ', $insert) . " WHERE `id` = '{$country->getId()}'");
        $query->query();

        self::$objects[$country->getId()] = $country;

        OrganisationRepoInAPC::resetCache();
        ProjectRepoInAPC::resetCache();
    }

    public function getById(int $id): Country
    {
        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    public function getByIds($ids)
    {
        if (count($ids) == 0)
            return [];

        return $this->getObjectsWhere([
            "`id` IN (" . implode(',', $ids) . ")"
        ]);
    }

    public function nameExists(string $name): bool
    {
        return $this->checkExistingCountryWhere([
            "`name` = '" . addslashes($name) . "'"
        ]);
    }

    public function getByName(string $name): Country
    {
        return $this->getCountryWhere([
            "`name` = '" . addslashes($name) . "'"
        ]);
    }

    /** @return Country[] */
    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    /**
     * @param $where
     * @return Country[]
     */
    private function getObjectsWhere($where)
    {
        $countries = [];
        $query = new SQL("SELECT 
            id, name
          FROM `countries` WHERE " . implode(' AND ', $where) . "
          ORDER BY name");
        foreach ($query->fetch_all() AS $dbCountry) {
            $countries[] = $this->buildCountryFromData($dbCountry);
        }
        return $countries;
    }

    /**
     * @param $where
     * @return Country
     * @throws DSI\NotFound
     */
    private function getObjectWhere($where)
    {
        $objects = $this->getObjectsWhere($where);
        if (count($objects) < 1)
            throw new DSI\NotFound();

        return $objects[0];
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `countries`");
        $query->query();
        self::$objects = [];
    }


    /**
     * @param $country
     * @return Country
     */
    private function buildCountryFromData($country)
    {
        $countryObj = new Country();
        $countryObj->setId($country['id']);
        $countryObj->setName($country['name']);

        self::$objects[$countryObj->getId()] = $countryObj;

        return $countryObj;
    }

    /**
     * @param $where
     * @return Country
     * @throws DSI\NotFound
     */
    private function getCountryWhere($where)
    {
        $query = new SQL("SELECT 
              id, name
            FROM `countries` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        $dbCountry = $query->fetch();
        if (!$dbCountry) {
            throw new DSI\NotFound();
        }

        return $this->buildCountryFromData($dbCountry);
    }

    /**
     * @param $where
     * @return bool
     */
    private function checkExistingCountryWhere($where)
    {
        $query = new SQL("SELECT id FROM `countries` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        return ($query->fetch() ? true : false);
    }
}