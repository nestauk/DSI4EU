<?php

namespace DSI\Repository;

use DSI\Entity\Event;
use DSI\NotFound;
use DSI\Service\SQL;

class EventRepo
{
    private $dbTable = 'events';

    public function insert(Event $event)
    {
        $insert = array();
        $insert[] = "`title` = '" . addslashes($event->getTitle()) . "'";
        $insert[] = "`url` = '" . addslashes($event->getUrl()) . "'";
        $insert[] = "`shortDesc` = '" . addslashes($event->getShortDescription()) . "'";
        $insert[] = "`description` = '" . addslashes($event->getDescription()) . "'";
        $insert[] = "`startDate` = '" . addslashes($event->getStartDate()) . "'";
        $insert[] = "`endDate` = '" . addslashes($event->getEndDate()) . "'";
        $insert[] = "`address` = '" . addslashes($event->getAddress()) . "'";
        $insert[] = "`phoneNumber` = '" . addslashes($event->getPhoneNumber()) . "'";
        $insert[] = "`emailAddress` = '" . addslashes($event->getEmailAddress()) . "'";
        $insert[] = "`price` = '" . addslashes($event->getPrice()) . "'";
        $insert[] = "`regionID` = '" . (int)($event->getRegionID()) . "'";

        $query = new SQL("INSERT INTO `{$this->dbTable}` SET " . implode(', ', $insert));
        $query->query();

        $event->setId($query->insert_id());
    }

    public function save(Event $event)
    {
        $query = new SQL("SELECT id FROM `{$this->dbTable}` WHERE id = '{$event->getId()}' LIMIT 1");
        $existingUser = $query->fetch();
        if (!$existingUser)
            throw new NotFound('eventID: ' . $event->getId());

        $insert = array();
        $insert[] = "`title` = '" . addslashes($event->getTitle()) . "'";
        $insert[] = "`url` = '" . addslashes($event->getUrl()) . "'";
        $insert[] = "`shortDesc` = '" . addslashes($event->getShortDescription()) . "'";
        $insert[] = "`description` = '" . addslashes($event->getDescription()) . "'";
        $insert[] = "`startDate` = '" . addslashes($event->getStartDate()) . "'";
        $insert[] = "`endDate` = '" . addslashes($event->getEndDate()) . "'";
        $insert[] = "`address` = '" . addslashes($event->getAddress()) . "'";
        $insert[] = "`phoneNumber` = '" . addslashes($event->getPhoneNumber()) . "'";
        $insert[] = "`emailAddress` = '" . addslashes($event->getEmailAddress()) . "'";
        $insert[] = "`price` = '" . addslashes($event->getPrice()) . "'";
        $insert[] = "`regionID` = '" . (int)($event->getRegionID()) . "'";

        $query = new SQL("UPDATE `{$this->dbTable}` SET " . implode(', ', $insert) . " WHERE `id` = '{$event->getId()}'");
        $query->query();
    }

    public function getById(int $id)
    {
        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    /** @return Event[] */
    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    /** @return Event[] */
    public function getFutureOnes()
    {
        return $this->getObjectsWhere([
            "`endDate` > NOW() OR `endDate` = '0000-00-00'"
        ]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `{$this->dbTable}`");
        $query->query();
    }

    /**
     * @param $where
     * @return Event
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
     * @return Event[]
     */
    private function getObjectsWhere($where)
    {
        $events = [];
        $query = new SQL("SELECT 
              id, title, url, shortDesc, description
            , startDate, endDate, regionID, timeCreated
            , address, phoneNumber, emailAddress, price
          FROM `{$this->dbTable}` WHERE " . implode(' AND ', $where) . "
          ORDER BY startDate");
        foreach ($query->fetch_all() AS $dbEvent) {
            $event = new Event();
            $event->setId($dbEvent['id']);
            $event->setTitle($dbEvent['title']);
            $event->setUrl($dbEvent['url']);
            $event->setShortDescription($dbEvent['shortDesc']);
            $event->setDescription($dbEvent['description']);
            $event->setStartDate($dbEvent['startDate']);
            $event->setEndDate($dbEvent['endDate']);
            $event->setAddress($dbEvent['address']);
            $event->setPhoneNumber($dbEvent['phoneNumber']);
            $event->setEmailAddress($dbEvent['emailAddress']);
            $event->setPrice($dbEvent['price']);
            $event->setTimeCreated($dbEvent['timeCreated']);
            if ($dbEvent['regionID'])
                $event->setRegion(
                    (new CountryRegionRepo())->getById($dbEvent['regionID'])
                );
            $events[] = $event;
        }
        return $events;
    }
}