<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\Project;
use DSI\Entity\ProjectNetworkTag;
use DSI\Entity\ProjectTag;
use DSI\NotFound;
use DSI\Service\SQL;

class ProjectNetworkTagRepository
{
    private $table = 'project-network-tags';
    /** @var ProjectRepository */
    private $projectRepo;

    /** @var NetworkTagRepository */
    private $tagsRepo;

    public function __construct()
    {
        $this->projectRepo = new ProjectRepository();
        $this->tagsRepo = new NetworkTagRepository();
    }

    public function add(ProjectNetworkTag $projectNetworkTag)
    {
        $query = new SQL("SELECT projectID 
            FROM `{$this->table}`
            WHERE `projectID` = '{$projectNetworkTag->getProjectID()}'
            AND `tagID` = '{$projectNetworkTag->getTagID()}'
            LIMIT 1
        ");
        if ($query->fetch('projectID') > 0)
            throw new DuplicateEntry("projectID: {$projectNetworkTag->getProjectID()} / tagID: {$projectNetworkTag->getTagID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectNetworkTag->getProjectID()) . "'";
        $insert[] = "`tagID` = '" . (int)($projectNetworkTag->getTagID()) . "'";

        $query = new SQL("INSERT INTO `{$this->table}` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(ProjectNetworkTag $projectNetworkTag)
    {
        $query = new SQL("SELECT projectID 
            FROM `{$this->table}`
            WHERE `projectID` = '{$projectNetworkTag->getProjectID()}'
            AND `tagID` = '{$projectNetworkTag->getTagID()}'
            LIMIT 1
        ");
        if (!$query->fetch('projectID'))
            throw new NotFound("projectID: {$projectNetworkTag->getProjectID()} / tagID: {$projectNetworkTag->getTagID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectNetworkTag->getProjectID()) . "'";
        $insert[] = "`tagID` = '" . (int)($projectNetworkTag->getTagID()) . "'";

        $query = new SQL("DELETE FROM `{$this->table}` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $projectID
     * @return ProjectNetworkTag[]
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
            FROM `{$this->table}`
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
     * @return ProjectNetworkTag[]
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
            FROM `{$this->table}`
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
        $query = new SQL("TRUNCATE TABLE `{$this->table}`");
        $query->query();
    }

    /**
     * @param $where
     * @return ProjectNetworkTag[]
     */
    private function getProjectTagsWhere($where)
    {
        /** @var ProjectNetworkTag[] $projectNetworkTags */
        $projectNetworkTags = [];
        $query = new SQL("SELECT projectID, tagID 
            FROM `{$this->table}`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbProjectTag) {
            $projectTag = new ProjectNetworkTag();
            $projectTag->setProject($this->projectRepo->getById($dbProjectTag['projectID']));
            $projectTag->setTag($this->tagsRepo->getById($dbProjectTag['tagID']));
            $projectNetworkTags[] = $projectTag;
        }

        return $projectNetworkTags;
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
            FROM `network-tags` 
            LEFT JOIN `{$this->table}` ON `network-tags`.`id` = `{$this->table}`.`tagID`
            WHERE `{$this->table}`.`projectID` = '{$project->getId()}'
            ORDER BY `network-tags`.`tag`
        ");
        return $query->fetch_all('tag');
    }
}