<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\AuthToken;
use DSI\NotFound;
use DSI\Service\SQL;

class AuthTokenRepository
{
    private $dbTable = 'auth-tokens';

    public function insert(AuthToken $authToken)
    {
        if ($this->selectorExists($authToken->getSelector()))
            throw new DuplicateEntry('selector: ' . $authToken->getSelector());

        $insert = array();
        $insert[] = "`selector` = '" . addslashes($authToken->getSelector()) . "'";
        $insert[] = "`token` = '" . addslashes($authToken->getToken()) . "'";
        $insert[] = "`userID` = '" . addslashes($authToken->getUser()->getId()) . "'";
        $insert[] = "`created` = NOW()";
        $insert[] = "`lastUse` = NOW()";

        $query = new SQL("INSERT INTO `{$this->dbTable}` SET " . implode(', ', $insert));
        $query->query();

        $authToken->setId($query->insert_id());
    }

    public function save(AuthToken $authToken)
    {
        if (!$authToken->getSelector())
            throw new \DSI\NotEnoughData('selector');

        $query = new SQL("SELECT id FROM `{$this->table}` WHERE id = '{$authToken->getId()}' LIMIT 1");
        $existingToken = $query->fetch();
        if (!$existingToken)
            throw new \DSI\NotFound('token ID: ' . $authToken->getId());

        $insert = array();
        $insert[] = "`selector` = '" . addslashes($authToken->getSelector()) . "'";
        $insert[] = "`token` = '" . addslashes($authToken->getToken()) . "'";
        $insert[] = "`userID` = '" . addslashes($authToken->getUser()->getId()) . "'";
        $insert[] = "`lastUse` = '" . addslashes($authToken->getLastUse()) . "'";

        $query = new SQL("UPDATE `{$this->table}` SET " . implode(', ', $insert) . " WHERE `id` = '{$authToken->getId()}'");
        $query->query();
    }

    public function remove(AuthToken $authToken)
    {
        $query = new SQL("SELECT id FROM `{$this->dbTable}` WHERE id = '{$authToken->getId()}' LIMIT 1");
        $existingToken = $query->fetch();
        if (!$existingToken)
            throw new NotFound('token ID: ' . $authToken->getId());

        $query = new SQL("DELETE FROM `{$this->dbTable}` WHERE id = '{$authToken->getId()}' LIMIT 1");
        $query->query();
    }

    public function getById(int $id)
    {
        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    /** @return AuthToken[] */
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
     * @return AuthToken
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
     * @return AuthToken[]
     */
    private function getObjectsWhere($where)
    {
        $authTokens = [];
        $query = new SQL("SELECT 
            id, selector, token, userID, created, lastUse
          FROM `{$this->dbTable}` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbAuthToken) {
            $authToken = new AuthToken();
            $authToken->setId($dbAuthToken['id']);
            $authToken->setSelector($dbAuthToken['selector']);
            $authToken->setToken($dbAuthToken['token']);
            $authToken->setUser(
                (new UserRepository())->getById($dbAuthToken['userID'])
            );
            $authToken->setCreated($dbAuthToken['created']);
            $authToken->setLastUse($dbAuthToken['lastUse']);
            $authTokens[] = $authToken;
        }
        return $authTokens;
    }

    public function selectorExists(string $selector): bool
    {
        return $this->checkExistingObjectWhere([
            "`selector` = '" . addslashes($selector) . "'"
        ]);
    }

    /**
     * @param $where
     * @return bool
     */
    private function checkExistingObjectWhere($where)
    {
        $query = new SQL("SELECT id FROM `{$this->table}` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        return ($query->fetch() ? true : false);
    }
}