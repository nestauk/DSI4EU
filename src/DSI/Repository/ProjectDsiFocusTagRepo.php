<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\Project;
use DSI\Entity\ProjectDsiFocusTag;
use DSI\NotFound;
use DSI\Service\SQL;

class ProjectDsiFocusTagRepo
{
    /** @var ProjectRepo */
    private $projectRepo;

    /** @var DsiFocusTagRepo */
    private $tagsRepo;

    private $table = 'project-impact-tags-b';

    public function __construct()
    {
        $this->projectRepo = new ProjectRepoInAPC();
        $this->tagsRepo = new DsiFocusTagRepo();
    }

    public function add(ProjectDsiFocusTag $projectTag)
    {
        $query = new SQL("SELECT projectID 
            FROM `{$this->table}`
            WHERE `projectID` = '{$projectTag->getProjectID()}'
            AND `tagID` = '{$projectTag->getTagID()}'
            LIMIT 1
        ");
        if ($query->fetch('projectID') > 0)
            throw new DuplicateEntry("projectID: {$projectTag->getProjectID()} / tagID: {$projectTag->getTagID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectTag->getProjectID()) . "'";
        $insert[] = "`tagID` = '" . (int)($projectTag->getTagID()) . "'";

        $query = new SQL("INSERT INTO `{$this->table}` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(ProjectDsiFocusTag $projectTag)
    {
        $query = new SQL("SELECT projectID 
            FROM `{$this->table}`
            WHERE `projectID` = '{$projectTag->getProjectID()}'
            AND `tagID` = '{$projectTag->getTagID()}'
            LIMIT 1
        ");
        if (!$query->fetch('projectID'))
            throw new NotFound("projectID: {$projectTag->getProjectID()} / tagID: {$projectTag->getTagID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectTag->getProjectID()) . "'";
        $insert[] = "`tagID` = '" . (int)($projectTag->getTagID()) . "'";

        $query = new SQL("DELETE FROM `{$this->table}` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $projectID
     * @return \DSI\Entity\ProjectDsiFocusTag[]
     */
    public function getByProjectID(int $projectID)
    {
        return $this->getProjectDsiFocusTagsWhere([
            "`projectID` = '{$projectID}'"
        ]);
    }

    /**
     * @param int $tagID
     * @return \DSI\Entity\ProjectDsiFocusTag[]
     */
    public function getByTagID(int $tagID)
    {
        return $this->getProjectDsiFocusTagsWhere([
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
            FROM `{$this->table}`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY projectID
        ");
        foreach ($query->fetch_all() AS $dbProjectDsiFocusTag) {
            $projectIDs[] = $dbProjectDsiFocusTag['projectID'];
        }

        return $projectIDs;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `{$this->table}`");
        $query->query();
    }

    /**
     * @param $where
     * @return \DSI\Entity\ProjectDsiFocusTag[]
     */
    private function getProjectDsiFocusTagsWhere($where)
    {
        /** @var ProjectDsiFocusTag[] $projectTags */
        $projectTags = [];
        $query = new SQL("SELECT projectID, tagID 
            FROM `{$this->table}`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbProjectDsiFocusTag) {
            $projectTag = new ProjectDsiFocusTag();
            $projectTag->setProject($this->projectRepo->getById($dbProjectDsiFocusTag['projectID']));
            $projectTag->setTag($this->tagsRepo->getById($dbProjectDsiFocusTag['tagID']));
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

    public function getTagNamesByProject(Project $project)
    {
        $query = new SQL("SELECT tag 
            FROM `dsi-focus-tags` 
            LEFT JOIN `{$this->table}` ON `dsi-focus-tags`.`id` = `{$this->table}`.`tagID`
            WHERE `{$this->table}`.`projectID` = '{$project->getId()}'
            ORDER BY `dsi-focus-tags`.`tag`
        ");
        return $query->fetch_all('tag');
    }

    public function getTagDataByProject(Project $project)
    {
        $query = new SQL("SELECT id, tag AS name 
            FROM `dsi-focus-tags` 
            LEFT JOIN `{$this->table}` ON `dsi-focus-tags`.`id` = `{$this->table}`.`tagID`
            WHERE `{$this->table}`.`projectID` = '{$project->getId()}'
            ORDER BY `dsi-focus-tags`.`tag`
        ");
        return $query->fetch_all();
    }

    public function getTagIDsByProject(Project $project)
    {
        $query = new SQL("SELECT tagID 
            FROM `{$this->table}`
            WHERE `{$this->table}`.`projectID` = '{$project->getId()}'
        ");
        return $query->fetch_all('tagID');
    }
}