<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\ProjectTag;
use DSI\NotFound;
use DSI\Service\SQL;

class ProjectTagRepository
{
    /** @var ProjectRepository */
    private $projectRepo;

    /** @var TagForProjectsRepository */
    private $tagsRepo;

    public function __construct()
    {
        $this->projectRepo = new ProjectRepository();
        $this->tagsRepo = new TagForProjectsRepository();
    }

    public function add(ProjectTag $projectTag)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-tags`
            WHERE `projectID` = '{$projectTag->getProjectID()}'
            AND `tagID` = '{$projectTag->getTagID()}'
            LIMIT 1
        ");
        if ($query->fetch('projectID') > 0)
            throw new DuplicateEntry("projectID: {$projectTag->getProjectID()} / tagID: {$projectTag->getTagID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectTag->getProjectID()) . "'";
        $insert[] = "`tagID` = '" . (int)($projectTag->getTagID()) . "'";

        $query = new SQL("INSERT INTO `project-tags` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(ProjectTag $projectTag)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-tags`
            WHERE `projectID` = '{$projectTag->getProjectID()}'
            AND `tagID` = '{$projectTag->getTagID()}'
            LIMIT 1
        ");
        if (!$query->fetch('projectID'))
            throw new NotFound("projectID: {$projectTag->getProjectID()} / tagID: {$projectTag->getTagID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectTag->getProjectID()) . "'";
        $insert[] = "`tagID` = '" . (int)($projectTag->getTagID()) . "'";

        $query = new SQL("DELETE FROM `project-tags` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $projectID
     * @return \DSI\Entity\ProjectTag[]
     */
    public function getByProjectID(int $projectID)
    {
        return $this->getProjectTagsWhere([
            "`projectID` = '{$projectID}'"
        ]);
    }

    /**
     * @param int $projectID
     * @return \int[]
     */
    public function getTagIDsForProject(int $projectID)
    {
        $where = [
            "`projectID` = '{$projectID}'"
        ];

        /** @var int[] $tagIDs */
        $tagIDs = [];
        $query = new SQL("SELECT tagID 
            FROM `project-tags`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY tagID
        ");
        foreach ($query->fetch_all() AS $dbProjectTags) {
            $tagIDs[] = $dbProjectTags['tagID'];
        }

        return $tagIDs;
    }

    /**
     * @param int $tagID
     * @return \DSI\Entity\ProjectTag[]
     */
    public function getByTagID(int $tagID)
    {
        return $this->getProjectTagsWhere([
            "`tagID` = '{$tagID}'"
        ]);
    }

    /**
     * @param int $tagID
     * @return \int[]
     */
    public function getProjectIDsForTag(int $tagID)
    {
        $where = [
            "`tagID` = '{$tagID}'"
        ];

        /** @var int[] $projectIDs */
        $projectIDs = [];
        $query = new SQL("SELECT projectID 
            FROM `project-tags`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY projectID
        ");
        foreach ($query->fetch_all() AS $dbProjectTag) {
            $projectIDs[] = $dbProjectTag['projectID'];
        }

        return $projectIDs;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `project-tags`");
        $query->query();
    }

    /**
     * @param $where
     * @return \DSI\Entity\ProjectTag[]
     */
    private function getProjectTagsWhere($where)
    {
        /** @var ProjectTag[] $projectTags */
        $projectTags = [];
        $query = new SQL("SELECT projectID, tagID 
            FROM `project-tags`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbProjectTag) {
            $projectTag = new ProjectTag();
            $projectTag->setProject($this->projectRepo->getById($dbProjectTag['projectID']));
            $projectTag->setTag($this->tagsRepo->getById($dbProjectTag['tagID']));
            $projectTags[] = $projectTag;
        }

        return $projectTags;
    }

    public function projectHasTagName(int $projectID, string $tagName)
    {
        $projectTags = $this->getByProjectID($projectID);
        foreach ($projectTags AS $projectTag) {
            if ($tagName == $projectTag->getTag()->getName()) {
                return true;
            }
        }

        return false;
    }

    public function getTagsNameByProjectID(int $projectID)
    {
        $query = new SQL("SELECT tag 
            FROM `tags-for-projects` 
            LEFT JOIN `project-tags` ON `tags-for-projects`.`id` = `project-tags`.`tagID`
            WHERE `project-tags`.`projectID` = '{$projectID}'
            ORDER BY `tags-for-projects`.`tag`
        ");
        return $query->fetch_all('tag');
    }
}