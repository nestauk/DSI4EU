<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\Project;
use DSI\Entity\ProjectImpactTagC;
use DSI\NotFound;
use DSI\Service\SQL;

class ProjectImpactTagCRepository
{
    /** @var ProjectRepository */
    private $projectRepo;

    /** @var ImpactTagRepository */
    private $tagsRepo;

    public function __construct()
    {
        $this->projectRepo = new ProjectRepository();
        $this->tagsRepo = new ImpactTagRepository();
    }

    public function add(ProjectImpactTagC $projectTag)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-impact-tags-c`
            WHERE `projectID` = '{$projectTag->getProjectID()}'
            AND `tagID` = '{$projectTag->getTagID()}'
            LIMIT 1
        ");
        if ($query->fetch('projectID') > 0)
            throw new DuplicateEntry("projectID: {$projectTag->getProjectID()} / tagID: {$projectTag->getTagID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectTag->getProjectID()) . "'";
        $insert[] = "`tagID` = '" . (int)($projectTag->getTagID()) . "'";

        $query = new SQL("INSERT INTO `project-impact-tags-c` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(ProjectImpactTagC $projectTag)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-impact-tags-c`
            WHERE `projectID` = '{$projectTag->getProjectID()}'
            AND `tagID` = '{$projectTag->getTagID()}'
            LIMIT 1
        ");
        if (!$query->fetch('projectID'))
            throw new NotFound("projectID: {$projectTag->getProjectID()} / tagID: {$projectTag->getTagID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectTag->getProjectID()) . "'";
        $insert[] = "`tagID` = '" . (int)($projectTag->getTagID()) . "'";

        $query = new SQL("DELETE FROM `project-impact-tags-c` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $projectID
     * @return \DSI\Entity\ProjectImpactTagC[]
     */
    public function getByProjectID(int $projectID)
    {
        return $this->getProjectImpactTagCsWhere([
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
            FROM `project-impact-tags-c`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY tagID
        ");
        foreach ($query->fetch_all() AS $dbProjectImpactTagCs) {
            $tagIDs[] = $dbProjectImpactTagCs['tagID'];
        }

        return $tagIDs;
    }

    /**
     * @param int $tagID
     * @return \DSI\Entity\ProjectImpactTagC[]
     */
    public function getByTagID(int $tagID)
    {
        return $this->getProjectImpactTagCsWhere([
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
            FROM `project-impact-tags-c`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY projectID
        ");
        foreach ($query->fetch_all() AS $dbProjectImpactTagC) {
            $projectIDs[] = $dbProjectImpactTagC['projectID'];
        }

        return $projectIDs;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `project-impact-tags-c`");
        $query->query();
    }

    /**
     * @param $where
     * @return \DSI\Entity\ProjectImpactTagC[]
     */
    private function getProjectImpactTagCsWhere($where)
    {
        /** @var ProjectImpactTagC[] $projectTags */
        $projectTags = [];
        $query = new SQL("SELECT projectID, tagID 
            FROM `project-impact-tags-c`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbProjectImpactTagC) {
            $projectTag = new ProjectImpactTagC();
            $projectTag->setProject($this->projectRepo->getById($dbProjectImpactTagC['projectID']));
            $projectTag->setTag($this->tagsRepo->getById($dbProjectImpactTagC['tagID']));
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
            LEFT JOIN `project-impact-tags-c` ON `impact-tags`.`id` = `project-impact-tags-c`.`tagID`
            WHERE `project-impact-tags-c`.`projectID` = '{$project->getId()}'
            ORDER BY `impact-tags`.`tag`
        ");
        return $query->fetch_all('tag');
    }
}