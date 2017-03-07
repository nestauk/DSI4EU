<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\Project;
use DSI\Entity\ProjectTag;
use DSI\NotFound;
use DSI\Service\SQL;

class ProjectTagRepository
{
    /** @var ProjectRepository */
    private $projectRepo;

    /** @var TagForProjectsRepository */
    private $tagsRepo;

    private $table = 'project-tags';

    public function __construct()
    {
        $this->projectRepo = new ProjectRepositoryInAPC();
        $this->tagsRepo = new TagForProjectsRepository();
    }

    public function add(ProjectTag $projectTag)
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

    public function remove(ProjectTag $projectTag)
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
     * @return \DSI\Entity\ProjectTag[]
     */
    public function getByProjectID(int $projectID)
    {
        return $this->getProjectTagsWhere([
            "`projectID` = '{$projectID}'"
        ]);
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
     * @return \DSI\Entity\ProjectTag[]
     */
    private function getProjectTagsWhere($where)
    {
        /** @var ProjectTag[] $projectTags */
        $projectTags = [];
        $query = new SQL("SELECT projectID, tagID 
            FROM `{$this->table}`
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

    public function getTagNamesByProject(Project $project)
    {
        $query = new SQL("SELECT tag 
            FROM `tags-for-projects` 
            LEFT JOIN `{$this->table}` ON `tags-for-projects`.`id` = `{$this->table}`.`tagID`
            WHERE `{$this->table}`.`projectID` = '{$project->getId()}'
            ORDER BY `tags-for-projects`.`tag`
        ");
        return $query->fetch_all('tag');
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