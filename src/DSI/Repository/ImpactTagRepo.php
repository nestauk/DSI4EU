<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\ImpactTag;
use DSI\Service\SQL;

class ImpactTagRepo
{
    private $table = 'impact-tags';

    public function insert(ImpactTag $tag)
    {
        if (!$tag->getName())
            throw new DSI\NotEnoughData('name');
        if ($this->nameExists($tag->getName()))
            throw new DSI\DuplicateEntry('name');

        $insert = array();
        $insert[] = "`tag` = '" . addslashes($tag->getName()) . "'";
        $insert[] = "`isMain` = '" . (bool)($tag->isMain()) . "'";
        $insert[] = "`order` = '" . (int)($tag->getOrder()) . "'";

        $query = new SQL("INSERT INTO `{$this->table}` SET " . implode(', ', $insert) . "");
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

        $query = new SQL("SELECT id FROM `{$this->table}` WHERE id = '{$tag->getId()}' LIMIT 1");
        $existingTag = $query->fetch();
        if (!$existingTag)
            throw new DSI\NotFound('tagID: ' . $tag->getId());

        $insert = array();
        $insert[] = "`tag` = '" . addslashes($tag->getName()) . "'";
        $insert[] = "`isMain` = '" . (bool)($tag->isMain()) . "'";
        $insert[] = "`order` = '" . (int)($tag->getOrder()) . "'";

        $query = new SQL("UPDATE `{$this->table}` SET " . implode(', ', $insert) . " WHERE `id` = '{$tag->getId()}'");
        $query->query();
    }

    public function remove(ImpactTag $tag)
    {
        $query = new SQL("SELECT id FROM `{$this->table}` WHERE id = '{$tag->getId()}' LIMIT 1");
        $existingTag = $query->fetch();
        if (!$existingTag)
            throw new DSI\NotFound('tagID: ' . $tag->getId());

        $query = new SQL("DELETE FROM `{$this->table}` WHERE `id` = '{$tag->getId()}'");
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
        return $this->getTagsWhere(["1"]);
    }

    /** @return ImpactTag[] */
    public function getMainTags()
    {
        return $this->getTagsWhere([
            "`isMain` = 1"
        ]);
    }

    /**
     * @param $where
     * @return ImpactTag[]
     */
    private function getTagsWhere($where)
    {
        $query = new SQL("SELECT 
            `id`, `tag`, `isMain`, `order`
          FROM `{$this->table}` WHERE " . implode(' AND ', $where) . "
          ORDER BY `order` DESC, tag");

        return array_map(function ($dbTag) {
            return $this->buildTagFromData($dbTag);
        }, $query->fetch_all());
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `{$this->table}`");
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
        $tagObj->setIsMain($tag['isMain']);
        $tagObj->setOrder($tag['order']);
        return $tagObj;
    }

    /**
     * @param $where
     * @return ImpactTag
     * @throws DSI\NotFound
     */
    private function getTagWhere($where)
    {
        $objects = $this->getTagsWhere($where);
        if (count($objects) < 1)
            throw new DSI\NotFound();

        return $objects[0];
    }

    /**
     * @param $where
     * @return bool
     */
    private function checkExistingTagWhere($where)
    {
        $query = new SQL("SELECT id FROM `{$this->table}` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        return ($query->fetch() ? true : false);
    }
}