<?php

namespace DSI\Repository;

use DSI\Entity\Funding;
use DSI\NotFound;
use DSI\Service\SQL;

class FundingRepository
{
    private $dbTable = 'fundings';

    public function insert(Funding $funding)
    {
        $insert = array();
        $insert[] = "`title` = '" . addslashes($funding->getTitle()) . "'";
        $insert[] = "`url` = '" . addslashes($funding->getUrl()) . "'";
        $insert[] = "`description` = '" . addslashes($funding->getDescription()) . "'";
        $insert[] = "`closingDate` = '" . addslashes($funding->getClosingDate()) . "'";
        $insert[] = "`fundingSourceID` = '" . (int)($funding->getSourceID()) . "'";
        $insert[] = "`countryID` = '" . (int)($funding->getCountryID()) . "'";
        $insert[] = "`typeID` = '" . (int)($funding->getTypeID()) . "'";
        $insert[] = "`targets` = '" . addslashes(implode(',', $funding->getTargetIDs())) . "'";

        $query = new SQL("INSERT INTO `{$this->dbTable}` SET " . implode(', ', $insert));
        $query->query();

        $funding->setId($query->insert_id());
    }

    public function save(Funding $funding)
    {
        $query = new SQL("SELECT id FROM `{$this->dbTable}` WHERE id = '{$funding->getId()}' LIMIT 1");
        $existingUser = $query->fetch();
        if (!$existingUser)
            throw new NotFound('fundingID: ' . $funding->getId());

        $insert = array();
        $insert[] = "`title` = '" . addslashes($funding->getTitle()) . "'";
        $insert[] = "`url` = '" . addslashes($funding->getUrl()) . "'";
        $insert[] = "`description` = '" . addslashes($funding->getDescription()) . "'";
        $insert[] = "`closingDate` = '" . addslashes($funding->getClosingDate()) . "'";
        $insert[] = "`fundingSourceID` = '" . (int)($funding->getSourceID()) . "'";
        $insert[] = "`countryID` = '" . (int)($funding->getCountryID()) . "'";
        $insert[] = "`typeID` = '" . (int)($funding->getTypeID()) . "'";
        $insert[] = "`targets` = '" . addslashes(implode(',', $funding->getTargetIDs())) . "'";

        $query = new SQL("UPDATE `{$this->dbTable}` SET " . implode(', ', $insert) . " WHERE `id` = '{$funding->getId()}'");
        $query->query();
    }

    public function getById(int $id)
    {
        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    /** @return Funding[] */
    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    /** @return Funding[] */
    public function getFutureOnes()
    {
        return $this->getObjectsWhere([
            "`closingDate` > NOW() OR `closingDate` = '0000-00-00'"
        ]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `{$this->dbTable}`");
        $query->query();
    }

    /**
     * @param $where
     * @return Funding
     * @throws NotFound
     */
    private function getObjectWhere($where)
    {
        $objects = $this->getObjectsWhere($where);
        if (count($objects) < 1)
            throw new NotFound();

        return $objects[0];
    }

    /**
     * @param $where
     * @return Funding[]
     */
    private function getObjectsWhere($where)
    {
        $fundings = [];
        $query = new SQL("SELECT 
            id, title, url, description, closingDate, fundingSourceID, countryID, timeCreated, typeID, targets
          FROM `{$this->dbTable}` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbFunding) {
            $funding = new Funding();
            $funding->setId($dbFunding['id']);
            $funding->setTitle($dbFunding['title']);
            $funding->setUrl($dbFunding['url']);
            $funding->setDescription($dbFunding['description']);
            $funding->setClosingDate($dbFunding['closingDate']);
            if ($dbFunding['fundingSourceID'])
                $funding->setSource(
                    (new FundingSourceRepository())->getById($dbFunding['fundingSourceID'])
                );
            if ($dbFunding['countryID'])
                $funding->setCountry(
                    (new CountryRepository())->getById($dbFunding['countryID'])
                );
            if ($dbFunding['typeID'])
                $funding->setType(
                    (new FundingTypeRepository())->getById($dbFunding['typeID'])
                );
            if ($dbFunding['targets']) {
                $fundingTargetRepository = new FundingTargetRepository();
                foreach (explode(',', $dbFunding['targets']) AS $target) {
                    $funding->addTarget($fundingTargetRepository->getById($target));
                }
            }
            $funding->setTimeCreated($dbFunding['timeCreated']);
            $fundings[] = $funding;
        }
        return $fundings;
    }
}