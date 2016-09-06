<?php

namespace DSI\Repository;

use DSI\Entity\CacheMail;
use DSI\NotFound;
use DSI\Service\SQL;

class CacheMailRepository
{
    private $dbTable = 'mails';

    public function insert(CacheMail $mail)
    {
        $insert = array();
        $insert[] = "`content` = '" . addslashes(serialize($mail->getContent())) . "'";

        $query = new SQL("INSERT INTO `{$this->dbTable}` SET " . implode(', ', $insert));
        $query->query();

        $mail->setId($query->insert_id());
    }

    public function getById(int $id)
    {
        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    /** @return CacheMail[] */
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
     * @return CacheMail
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
     * @return CacheMail[]
     */
    private function getObjectsWhere($where)
    {
        $mails = [];
        $query = new SQL("SELECT 
            id, content
          FROM `{$this->dbTable}` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbMail) {
            $mail = new CacheMail();
            $mail->setId($dbMail['id']);
            $mail->setContent(unserialize($dbMail['content']));
            $mails[] = $mail;
        }
        return $mails;
    }
}