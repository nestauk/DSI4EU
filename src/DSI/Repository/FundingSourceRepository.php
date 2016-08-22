<?php

namespace DSI\Repository;

use DSI\Entity\FundingSource;
use DSI\NotFound;
use DSI\Service\SQL;

class FundingSourceRepository
{
    private $dbTable = 'funding-sources';

    public function insert(FundingSource $fundingSource)
    {
        $insert = array();
        $insert[] = "`title` = '" . addslashes($fundingSource->getTitle()) . "'";
        $insert[] = "`url` = '" . addslashes($fundingSource->getUrl()) . "'";

        $query = new SQL("INSERT INTO `{$this->dbTable}` SET " . implode(', ', $insert));
        $query->query();

        $fundingSource->setId($query->insert_id());
    }

    public function save(FundingSource $fundingSource)
    {
        $query = new SQL("SELECT id FROM `{$this->dbTable}` WHERE id = '{$fundingSource->getId()}' LIMIT 1");
        $existingUser = $query->fetch();
        if (!$existingUser)
            throw new NotFound('userID: ' . $fundingSource->getId());

        $insert = array();
        $insert[] = "`title` = '" . addslashes($fundingSource->getTitle()) . "'";
        $insert[] = "`url` = '" . addslashes($fundingSource->getUrl()) . "'";

        $query = new SQL("UPDATE `{$this->dbTable}` SET " . implode(', ', $insert) . " WHERE `id` = '{$fundingSource->getId()}'");
        $query->query();
    }

    public function getById(int $id)
    {
        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    /** @return FundingSource[] */
    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `{$this->dbTable}`");
        $query->query();
    }

    /**
     * @param $where
     * @return FundingSource
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
     * @return FundingSource[]
     */
    private function getObjectsWhere($where)
    {
        $fundingSources = [];
        $query = new SQL("SELECT 
            id, title, url
          FROM `{$this->dbTable}` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbFundingSource) {
            $fundingSource = new FundingSource();
            $fundingSource->setId($dbFundingSource['id']);
            $fundingSource->setTitle($dbFundingSource['title']);
            $fundingSource->setUrl($dbFundingSource['url']);
            $fundingSources[] = $fundingSource;
        }
        return $fundingSources;
    }
}