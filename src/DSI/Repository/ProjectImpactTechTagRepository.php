<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\Project;
use DSI\Entity\ProjectImpactTechTag;
use DSI\NotFound;
use DSI\Service\SQL;

class ProjectImpactTechTagRepository
{
    /** @var ProjectRepository */
    private $projectRepo;

    /** @var ImpactTagRepository */
    private $tagsRepo;

    private $table = 'project-impact-tags-c';

    public function __construct()
    {
        $this->projectRepo = new ProjectRepositoryInAPC();
        $this->tagsRepo = new ImpactTagRepository();
    }

    public function add(ProjectImpactTechTag $projectTag)
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

    public function remove(ProjectImpactTechTag $projectTag)
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
     * @return \DSI\Entity\ProjectImpactTechTag[]
     */
    public function getByProjectID(int $projectID)
    {
        return $this->getObjectsWhere([
            "`projectID` = '{$projectID}'"
        ]);
    }

    /**
     * @param int $tagID
     * @return \DSI\Entity\ProjectImpactTechTag[]
     */
    public function getByTagID(int $tagID)
    {
        return $this->getObjectsWhere([
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
        foreach ($query->fetch_all() AS $dbProjectImpactTechTag) {
            $projectIDs[] = $dbProjectImpactTechTag['projectID'];
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
     * @return \DSI\Entity\ProjectImpactTechTag[]
     */
    private function getObjectsWhere($where)
    {
        /** @var ProjectImpactTechTag[] $projectTags */
        $projectTags = [];
        $query = new SQL("SELECT projectID, tagID 
            FROM `{$this->table}`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbProjectImpactTechTag) {
            $projectTag = new ProjectImpactTechTag();
            $projectTag->setProject($this->projectRepo->getById($dbProjectImpactTechTag['projectID']));
            $projectTag->setTag($this->tagsRepo->getById($dbProjectImpactTechTag['tagID']));
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
            FROM `impact-tags` 
            LEFT JOIN `{$this->table}` ON `impact-tags`.`id` = `{$this->table}`.`tagID`
            WHERE `{$this->table}`.`projectID` = '{$project->getId()}'
            ORDER BY `impact-tags`.`tag`
        ");
        return $query->fetch_all('tag');
    }

    public function getTagDataByProject(Project $project)
    {
        $query = new SQL("SELECT id, tag AS name
            FROM `impact-tags` 
            LEFT JOIN `{$this->table}` ON `impact-tags`.`id` = `{$this->table}`.`tagID`
            WHERE `{$this->table}`.`projectID` = '{$project->getId()}'
            ORDER BY `impact-tags`.`tag`
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