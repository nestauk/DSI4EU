<?php

namespace DSI\Repository;

use DSI;
use DSI\Service\SQL;
use DSI\Entity\TagForOrganisations;

class TagForOrganisationsRepository
{
    public function saveAsNew(TagForOrganisations $tag)
    {
        if (!$tag->getName())
            throw new DSI\NotEnoughData('name');
        if ($this->nameExists($tag->getName()))
            throw new DSI\DuplicateEntry('name');

        $insert = array();
        $insert[] = "`tag` = '" . addslashes($tag->getName()) . "'";

        $query = new SQL("INSERT INTO `tags-for-organisations` SET " . implode(', ', $insert) . "");
        $query->query();

        $tag->setId($query->insert_id());
    }

    public function save(TagForOrganisations $tag)
    {
        if (!$tag->getName())
            throw new DSI\NotEnoughData('name');
        if ($this->nameExists($tag->getName()))
            if ($this->getByName($tag->getName())->getId() != $tag->getId())
                throw new DSI\DuplicateEntry('name');

        $query = new SQL("SELECT id FROM `tags-for-organisations` WHERE id = '{$tag->getId()}' LIMIT 1");
        $existingTag = $query->fetch();
        if (!$existingTag)
            throw new DSI\NotFound('tagID: ' . $tag->getId());

        $insert = array();
        $insert[] = "`tag` = '" . addslashes($tag->getName()) . "'";

        $query = new SQL("UPDATE `tags-for-organisations` SET " . implode(', ', $insert) . " WHERE `id` = '{$tag->getId()}'");
        $query->query();
    }

    public function remove(TagForOrganisations $tag)
    {
        $query = new SQL("SELECT id FROM `tags-for-organisations` WHERE id = '{$tag->getId()}' LIMIT 1");
        $existingTag = $query->fetch();
        if (!$existingTag)
            throw new DSI\NotFound('tagID: ' . $tag->getId());

        $query = new SQL("DELETE FROM `tags-for-organisations` WHERE `id` = '{$tag->getId()}'");
        $query->query();
    }

    public function getById(int $id): TagForOrganisations
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

    public function getByName(string $name): TagForOrganisations
    {
        return $this->getTagWhere([
            "`tag` = '" . addslashes($name) . "'"
        ]);
    }

    /** @return TagForOrganisations[] */
    public function getAll()
    {
        $where = ["1"];
        $tags = [];
        $query = new SQL("SELECT 
            id, tag
          FROM `tags-for-organisations` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbTag) {
            $tags[] = $this->buildTagFromData($dbTag);
        }
        return $tags;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `tags-for-organisations`");
        $query->query();
    }


    /**
     * @param $tag
     * @return TagForOrganisations
     */
    private function buildTagFromData($tag)
    {
        $tagObj = new TagForOrganisations();
        $tagObj->setId($tag['id']);
        $tagObj->setName($tag['tag']);
        return $tagObj;
    }

    /**
     * @param $where
     * @return TagForOrganisations
     * @throws DSI\NotFound
     */
    private function getTagWhere($where)
    {
        $query = new SQL("SELECT 
              id, tag
            FROM `tags-for-organisations` WHERE " . implode(' AND ', $where) . " LIMIT 1");
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
        $query = new SQL("SELECT id FROM `tags-for-organisations` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        return ($query->fetch() ? true : false);
    }
}