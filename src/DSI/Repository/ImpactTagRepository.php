<?php

namespace DSI\Repository;

use DSI;
use DSI\Service\SQL;
use DSI\Entity\ImpactTag;

class ImpactTagRepository
{
    public function insert(ImpactTag $tag)
    {
        if (!$tag->getName())
            throw new DSI\NotEnoughData('name');
        if ($this->nameExists($tag->getName()))
            throw new DSI\DuplicateEntry('name');

        $insert = array();
        $insert[] = "`tag` = '" . addslashes($tag->getName()) . "'";

        $query = new SQL("INSERT INTO `impact-tags` SET " . implode(', ', $insert) . "");
        $query->query();

        $tag->setId($query->insert_id());
    }

    public function save(ImpactTag $tag)
    {
        if (!$tag->getName())
            throw new DSI\NotEnoughData('name');
        if ($this->nameExists($tag->getName()))
            if ($this->getByName($tag->getName())->getId() != $tag->getId())
                throw new DSI\DuplicateEntry('name');

        $query = new SQL("SELECT id FROM `impact-tags` WHERE id = '{$tag->getId()}' LIMIT 1");
        $existingTag = $query->fetch();
        if (!$existingTag)
            throw new DSI\NotFound('tagID: ' . $tag->getId());

        $insert = array();
        $insert[] = "`tag` = '" . addslashes($tag->getName()) . "'";

        $query = new SQL("UPDATE `impact-tags` SET " . implode(', ', $insert) . " WHERE `id` = '{$tag->getId()}'");
        $query->query();
    }

    public function getById(int $id): ImpactTag
    {
        return $this->getTagWhere([
            "`id` = {$id}"
        ]);
    }

    public function nameExists(string $name): bool
    {
        return $this->checkExistingTagWhere([
            "`tag` = '" . addslashes($name) . "'"
        ]);
    }

    public function getByName(string $name): ImpactTag
    {
        return $this->getTagWhere([
            "`tag` = '" . addslashes($name) . "'"
        ]);
    }

    /** @return ImpactTag[] */
    public function getAll()
    {
        $where = ["1"];
        $tags = [];
        $query = new SQL("SELECT 
            id, tag
          FROM `impact-tags` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbTag) {
            $tags[] = $this->buildTagFromData($dbTag);
        }
        return $tags;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `impact-tags`");
        $query->query();
    }


    /**
     * @param $tag
     * @return ImpactTag
     */
    private function buildTagFromData($tag)
    {
        $tagObj = new ImpactTag();
        $tagObj->setId($tag['id']);
        $tagObj->setName($tag['tag']);
        return $tagObj;
    }

    /**
     * @param $where
     * @return ImpactTag
     * @throws DSI\NotFound
     */
    private function getTagWhere($where)
    {
        $query = new SQL("SELECT 
              id, tag
            FROM `impact-tags` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        $dbTag = $query->fetch();
        if (!$dbTag) {
            throw new DSI\NotFound();
        }

        return $this->buildTagFromData($dbTag);
    }

    /**
     * @param $where
     * @return bool
     */
    private function checkExistingTagWhere($where)
    {
        $query = new SQL("SELECT id FROM `impact-tags` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        return ($query->fetch() ? true : false);
    }
}