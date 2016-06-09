<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\Story;
use DSI\Service\SQL;

class StoryRepository
{
    public function insert(Story $story)
    {
        $insert = array();
        $insert[] = "`categoryID` = '" . (int)($story->getStoryCategoryId()) . "'";
        $insert[] = "`writerID` = '" . (int)($story->getWriter()->getId()) . "'";
        $insert[] = "`title` = '" . addslashes($story->getTitle()) . "'";
        $insert[] = "`content` = '" . addslashes($story->getContent()) . "'";
        $insert[] = "`featuredImage` = '" . addslashes($story->getFeaturedImage()) . "'";
        $insert[] = "`bgImage` = '" . addslashes($story->getBgImage()) . "'";
        $insert[] = "`isPublished` = '" . (bool)($story->isPublished()) . "'";
        $insert[] = "`datePublished` = '" . addslashes($story->getDatePublished()) . "'";

        $query = new SQL("INSERT INTO `stories` SET " . implode(', ', $insert) . "");
        $query->query();

        $story->setId($query->insert_id());
    }

    public function save(Story $story)
    {
        $query = new SQL("SELECT id FROM `stories` WHERE id = '{$story->getId()}' LIMIT 1");
        $existingStory = $query->fetch();
        if (!$existingStory)
            throw new DSI\NotFound('storyID: ' . $story->getId());

        $insert = array();
        $insert[] = "`categoryID` = '" . (int)($story->getStoryCategoryId()) . "'";
        $insert[] = "`writerID` = '" . (int)($story->getWriter()->getId()) . "'";
        $insert[] = "`title` = '" . addslashes($story->getTitle()) . "'";
        $insert[] = "`content` = '" . addslashes($story->getContent()) . "'";
        $insert[] = "`featuredImage` = '" . addslashes($story->getFeaturedImage()) . "'";
        $insert[] = "`bgImage` = '" . addslashes($story->getBgImage()) . "'";
        $insert[] = "`isPublished` = '" . (bool)($story->isPublished()) . "'";
        $insert[] = "`datePublished` = '" . addslashes($story->getDatePublished()) . "'";

        $query = new SQL("UPDATE `stories` SET " . implode(', ', $insert) . " WHERE `id` = '{$story->getId()}'");
        $query->query();
    }

    public function getById(int $id): Story
    {
        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    private function buildProjectFromData($story)
    {
        $storyObj = new Story();
        $storyObj->setId($story['id']);

        if ($story['categoryID'])
            $storyObj->setStoryCategory(
                (new StoryCategoryRepository())->getById($story['categoryID'])
            );
        $storyObj->setWriter(
            (new UserRepository())->getById($story['writerID'])
        );
        $storyObj->setTitle($story['title']);
        $storyObj->setContent($story['content']);
        $storyObj->setTime($story['time']);
        $storyObj->setFeaturedImage($story['featuredImage']);
        $storyObj->setBgImage($story['bgImage']);
        $storyObj->setIsPublished($story['isPublished']);
        $storyObj->setDatePublished($story['datePublished']);

        return $storyObj;
    }

    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `stories`");
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
        $stories = [];
        $query = new SQL("SELECT 
            `id`, `categoryID`
          , `writerID`, `title`, `content`
          , `featuredImage`, `bgImage`
          , `time`, `isPublished`, `datePublished`
          FROM `stories`
          WHERE " . implode(' AND ', $where) . "
          ORDER BY `title`
        ");
        foreach ($query->fetch_all() AS $dbStory)
            $stories[] = $this->buildProjectFromData($dbStory);

        return $stories;
    }
}