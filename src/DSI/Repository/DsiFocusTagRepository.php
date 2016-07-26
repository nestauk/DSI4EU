<?php

namespace DSI\Repository;

use DSI;
use DSI\Service\SQL;
use DSI\Entity\DsiFocusTag;

class DsiFocusTagRepository
{
    public function insert(DsiFocusTag $tag)
    {
        if (!$tag->getName())
            throw new DSI\NotEnoughData('name');
        if ($this->nameExists($tag->getName()))
            throw new DSI\DuplicateEntry('name');

        $insert = array();
        $insert[] = "`tag` = '" . addslashes($tag->getName()) . "'";

        $query = new SQL("INSERT INTO `dsi-focus-tags` SET " . implode(', ', $insert) . "");
        $query->query();

        $tag->setId($query->insert_id());
    }

    public function save(DsiFocusTag $tag)
    {
        if (!$tag->getName())
            throw new DSI\NotEnoughData('name');
        if ($this->nameExists($tag->getName()))
            if ($this->getByName($tag->getName())->getId() != $tag->getId())
                throw new DSI\DuplicateEntry('name');

        $query = new SQL("SELECT id FROM `dsi-focus-tags` WHERE id = '{$tag->getId()}' LIMIT 1");
        $existingTag = $query->fetch();
        if (!$existingTag)
            throw new DSI\NotFound('tagID: ' . $tag->getId());

        $insert = array();
        $insert[] = "`tag` = '" . addslashes($tag->getName()) . "'";

        $query = new SQL("UPDATE `dsi-focus-tags` SET " . implode(', ', $insert) . " WHERE `id` = '{$tag->getId()}'");
        $query->query();
    }

    public function getById(int $id): DsiFocusTag
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

    public function getByName(string $name): DsiFocusTag
    {
        return $this->getTagWhere([
            "`tag` = '" . addslashes($name) . "'"
        ]);
    }

    /** @return DsiFocusTag[] */
    public function getAll()
    {
        $where = ["1"];
        $tags = [];
        $query = new SQL("SELECT 
            id, tag
          FROM `dsi-focus-tags` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbTag) {
            $tags[] = $this->buildTagFromData($dbTag);
        }
        return $tags;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `dsi-focus-tags`");
        $query->query();
    }


    /**
     * @param $tag
     * @return DsiFocusTag
     */
    private function buildTagFromData($tag)
    {
        $tagObj = new DsiFocusTag();
        $tagObj->setId($tag['id']);
        $tagObj->setName($tag['tag']);
        return $tagObj;
    }

    /**
     * @param $where
     * @return DsiFocusTag
     * @throws DSI\NotFound
     */
    private function getTagWhere($where)
    {
        $query = new SQL("SELECT 
              id, tag
            FROM `dsi-focus-tags` WHERE " . implode(' AND ', $where) . " LIMIT 1");
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
        $query = new SQL("SELECT id FROM `dsi-focus-tags` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        return ($query->fetch() ? true : false);
    }
}