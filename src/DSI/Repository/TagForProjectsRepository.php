<?php

namespace DSI\Repository;

use DSI;
use DSI\Service\SQL;
use DSI\Entity\TagForProjects;

class TagForProjectsRepository
{
    public function saveAsNew(TagForProjects $tag)
    {
        if (!$tag->getName())
            throw new DSI\NotEnoughData('name');
        if ($this->nameExists($tag->getName()))
            throw new DSI\DuplicateEntry('name');

        $insert = array();
        $insert[] = "`tag` = '" . addslashes($tag->getName()) . "'";

        $query = new SQL("INSERT INTO `tags-for-projects` SET " . implode(', ', $insert) . "");
        $query->query();

        $tag->setId($query->insert_id());
    }

    public function save(TagForProjects $tag)
    {
        if (!$tag->getName())
            throw new DSI\NotEnoughData('name');
        if ($this->nameExists($tag->getName()))
            if ($this->getByName($tag->getName())->getId() != $tag->getId())
                throw new DSI\DuplicateEntry('name');

        $query = new SQL("SELECT id FROM `tags-for-projects` WHERE id = '{$tag->getId()}' LIMIT 1");
        $existingTag = $query->fetch();
        if (!$existingTag)
            throw new DSI\NotFound('tagID: ' . $tag->getId());

        $insert = array();
        $insert[] = "`tag` = '" . addslashes($tag->getName()) . "'";

        $query = new SQL("UPDATE `tags-for-projects` SET " . implode(', ', $insert) . " WHERE `id` = '{$tag->getId()}'");
        $query->query();
    }

    public function getById(int $id): TagForProjects
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

    public function getByName(string $name): TagForProjects
    {
        return $this->getTagWhere([
            "`tag` = '" . addslashes($name) . "'"
        ]);
    }

    /** @return TagForProjects[] */
    public function getAll()
    {
        $where = ["1"];
        $tags = [];
        $query = new SQL("SELECT 
            id, tag
          FROM `tags-for-projects` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbTag) {
            $tags[] = $this->buildTagFromData($dbTag);
        }
        return $tags;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `tags-for-projects`");
        $query->query();
    }


    /**
     * @param $tag
     * @return TagForProjects
     */
    private function buildTagFromData($tag)
    {
        $tagObj = new TagForProjects();
        $tagObj->setId($tag['id']);
        $tagObj->setName($tag['tag']);
        return $tagObj;
    }

    /**
     * @param $where
     * @return TagForProjects
     * @throws DSI\NotFound
     */
    private function getTagWhere($where)
    {
        $query = new SQL("SELECT 
              id, tag
            FROM `tags-for-projects` WHERE " . implode(' AND ', $where) . " LIMIT 1");
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
        $query = new SQL("SELECT id FROM `tags-for-projects` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        return ($query->fetch() ? true : false);
    }
}