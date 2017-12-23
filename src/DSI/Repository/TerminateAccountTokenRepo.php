<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\TerminateAccountToken;
use DSI\NotEnoughData;
use DSI\NotFound;
use DSI\Service\SQL;

class TerminateAccountTokenRepo
{
    private $dbTable = 'terminate-account-tokens';

    public function insert(TerminateAccountToken $token)
    {
        if ($this->tokenExists($token->getToken()))
            throw new DuplicateEntry('token: ' . $token->getToken());

        $insert = array();
        $insert[] = "`token` = '" . addslashes($token->getToken()) . "'";
        $insert[] = "`userID` = '" . addslashes($token->getUser()->getId()) . "'";
        $insert[] = "`expires` = '" . addslashes($token->getExpire()) . "'";

        $query = new SQL("INSERT INTO `{$this->dbTable}` SET " . implode(', ', $insert));
        $query->query();
    }

    public function save(TerminateAccountToken $token)
    {
        if (!$token->getToken())
            throw new NotEnoughData('token');

        if (!$this->tokenExists($token->getToken()))
            throw new NotFound('token: ' . $token->getToken());

        $insert = array();
        $insert[] = "`token` = '" . addslashes($token->getToken()) . "'";
        $insert[] = "`userID` = '" . addslashes($token->getUser()->getId()) . "'";
        $insert[] = "`expires` = '" . addslashes($token->getExpire()) . "'";

        $query = new SQL("UPDATE `{$this->dbTable}` 
          SET " . implode(', ', $insert) . " 
          WHERE `token` = '{$token->getToken()}'");
        $query->query();
    }

    public function remove(TerminateAccountToken $token)
    {
        if (!$token->getToken())
            throw new NotEnoughData('token');

        if (!$this->tokenExists($token->getToken()))
            throw new NotFound('token: ' . $token->getToken());

        $query = new SQL("DELETE FROM `{$this->dbTable}` WHERE token = '{$token->getToken()}' LIMIT 1");
        $query->query();
    }

    public function getByToken($token)
    {
        return $this->getObjectWhere([
            "`token` = '" . addslashes($token) . "'"
        ]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `{$this->dbTable}`");
        $query->query();
    }

    /**
     * @param $where
     * @return TerminateAccountToken
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
     * @return TerminateAccountToken[]
     */
    private function getObjectsWhere($where)
    {
        $query = new SQL("SELECT 
            token, userID, expires
          FROM `{$this->dbTable}` WHERE " . implode(' AND ', $where) . "");

        return array_map(function ($data) {
            $token = new TerminateAccountToken();
            $token->setToken($data['token']);
            $token->setUser((new UserRepo())->getById($data['userID']));
            $token->setExpire($data['expires']);
            return $token;
        }, $query->fetch_all());
    }

    public function tokenExists(string $token): bool
    {
        return $this->checkExistingObjectWhere([
            "`token` = '" . addslashes($token) . "'"
        ]);
    }

    /**
     * @param $where
     * @return bool
     */
    private function checkExistingObjectWhere($where)
    {
        $query = new SQL("SELECT token FROM `{$this->dbTable}` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        return ($query->fetch() ? true : false);
    }
}