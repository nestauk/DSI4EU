<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\AuthToken;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Service\SQL;

class AuthTokenRepo
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
        $insert[] = "`ip` = '" . addslashes(getIP()) . "'";
        $insert[] = "`created` = NOW()";
        $insert[] = "`lastUse` = NOW()";

        $query = new SQL("INSERT INTO `{$this->dbTable}` SET " . implode(', ', $insert));
        $query->query();
    }

    public function save(AuthToken $authToken)
    {
        if (!$authToken->getSelector())
            throw new \DSI\NotEnoughData('selector');

        $query = new SQL("SELECT selector FROM `{$this->dbTable}` WHERE selector = '{$authToken->getSelector()}' LIMIT 1");
        $existingToken = $query->fetch();
        if (!$existingToken)
            throw new \DSI\NotFound('token selector: ' . $authToken->getSelector());

        $insert = array();
        $insert[] = "`selector` = '" . addslashes($authToken->getSelector()) . "'";
        $insert[] = "`token` = '" . addslashes($authToken->getToken()) . "'";
        $insert[] = "`userID` = '" . addslashes($authToken->getUser()->getId()) . "'";
        $insert[] = "`ip` = '" . addslashes($authToken->getIp()) . "'";
        $insert[] = "`lastUse` = '" . addslashes($authToken->getLastUse()) . "'";

        $query = new SQL("UPDATE `{$this->dbTable}` 
          SET " . implode(', ', $insert) . " 
          WHERE `selector` = '{$authToken->getSelector()}'");
        $query->query();
    }

    public function remove(AuthToken $authToken)
    {
        $query = new SQL("SELECT selector FROM `{$this->dbTable}` WHERE selector = '{$authToken->getSelector()}' LIMIT 1");
        $existingToken = $query->fetch();
        if (!$existingToken)
            throw new NotFound('token selector: ' . $authToken->getSelector());

        $query = new SQL("DELETE FROM `{$this->dbTable}` WHERE selector = '{$authToken->getSelector()}' LIMIT 1");
        $query->query();
    }

    public function getBySelector($selector)
    {
        return $this->getObjectWhere([
            "`selector` = '" . addslashes($selector) . "'"
        ]);
    }

    /**
     * @param User $user
     * @return AuthToken[]
     */
    public function getAllByUser(User $user)
    {
        return $this->getObjectsWhere([
            "`userID` = '" . (int)($user->getId()) . "'"
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
            selector, token, userID, ip, created, lastUse
          FROM `{$this->dbTable}` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbAuthToken) {
            $authToken = new AuthToken();
            $authToken->setSelector($dbAuthToken['selector']);
            $authToken->setToken($dbAuthToken['token']);
            $authToken->setUser(
                (new UserRepo())->getById($dbAuthToken['userID'])
            );
            $authToken->setIp($dbAuthToken['ip']);
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
        $query = new SQL("SELECT selector FROM `{$this->dbTable}` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        return ($query->fetch() ? true : false);
    }
}