<?php

namespace DSI\Repository;

use DSI;
use DSI\Service\SQL;
use DSI\Entity\Country;

class CountryRepository
{
    public function saveAsNew(Country $country)
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
    }

    public function getById(int $id): Country
    {
        return $this->getCountryWhere([
            "`id` = {$id}"
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
        $where = ["1"];
        $countries = [];
        $query = new SQL("SELECT 
            id, name
          FROM `countries` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbCountry) {
            $countries[] = $this->buildCountryFromData($dbCountry);
        }
        return $countries;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `countries`");
        $query->query();
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