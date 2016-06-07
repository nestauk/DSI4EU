<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\StoryCategory;
use DSI\Service\SQL;

class StoryCategoryRepository
{
    public function insert(StoryCategory $storyCategory)
    {
        $insert = array();
        $insert[] = "`name` = '" . addslashes($storyCategory->getName()) . "'";

        $query = new SQL("INSERT INTO `story-categories` SET " . implode(', ', $insert) . "");
        $query->query();

        $storyCategory->setId($query->insert_id());
    }

    public function save(StoryCategory $storyCategory)
    {
        $query = new SQL("SELECT id FROM `story-categories` WHERE id = '{$storyCategory->getId()}' LIMIT 1");
        $existingStory = $query->fetch();
        if (!$existingStory)
            throw new DSI\NotFound('story category ID: ' . $storyCategory->getId());

        $insert = array();
        $insert[] = "`name` = '" . addslashes($storyCategory->getName()) . "'";

        $query = new SQL("UPDATE `story-categories` SET " . implode(', ', $insert) . " WHERE `id` = '{$storyCategory->getId()}'");
        $query->query();
    }

    public function getById(int $id): StoryCategory
    {
        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    private function buildProjectFromData($story)
    {
        $storyCategoryObj = new StoryCategory();
        $storyCategoryObj->setId($story['id']);
        $storyCategoryObj->setName($story['name']);

        return $storyCategoryObj;
    }

    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `story-categories`");
        $query->query();
    }

    private function getObjectWhere($where)
    {
        $objects = $this->getObjectsWhere($where);
        if (count($objects) < 1)
            throw new DSI\NotFound();

        return $objects[0];
    }

    /**
     * @param $where
     * @return array
     */
    private function getObjectsWhere($where)
    {
        $storyCategories = [];
        $query = new SQL("SELECT 
            id, name
          FROM `story-categories`
          WHERE " . implode(' AND ', $where) . "
          ORDER BY `name`
        ");
        foreach ($query->fetch_all() AS $dbStoryCategory) {
            $storyCategories[] = $this->buildProjectFromData($dbStoryCategory);
        }
        return $storyCategories;
    }
}