<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\ProjectImpactTagB;
use DSI\NotFound;
use DSI\Service\SQL;

class ProjectImpactTagBRepository
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

    public function add(ProjectImpactTagB $projectTag)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-impact-tags-b`
            WHERE `projectID` = '{$projectTag->getProjectID()}'
            AND `tagID` = '{$projectTag->getTagID()}'
            LIMIT 1
        ");
        if ($query->fetch('projectID') > 0)
            throw new DuplicateEntry("projectID: {$projectTag->getProjectID()} / tagID: {$projectTag->getTagID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectTag->getProjectID()) . "'";
        $insert[] = "`tagID` = '" . (int)($projectTag->getTagID()) . "'";

        $query = new SQL("INSERT INTO `project-impact-tags-b` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(ProjectImpactTagB $projectTag)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-impact-tags-b`
            WHERE `projectID` = '{$projectTag->getProjectID()}'
            AND `tagID` = '{$projectTag->getTagID()}'
            LIMIT 1
        ");
        if (!$query->fetch('projectID'))
            throw new NotFound("projectID: {$projectTag->getProjectID()} / tagID: {$projectTag->getTagID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectTag->getProjectID()) . "'";
        $insert[] = "`tagID` = '" . (int)($projectTag->getTagID()) . "'";

        $query = new SQL("DELETE FROM `project-impact-tags-b` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $projectID
     * @return \DSI\Entity\ProjectImpactTagB[]
     */
    public function getByProjectID(int $projectID)
    {
        return $this->getProjectImpactTagBsWhere([
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
            FROM `project-impact-tags-b`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY tagID
        ");
        foreach ($query->fetch_all() AS $dbProjectImpactTagBs) {
            $tagIDs[] = $dbProjectImpactTagBs['tagID'];
        }

        return $tagIDs;
    }

    /**
     * @param int $tagID
     * @return \DSI\Entity\ProjectImpactTagB[]
     */
    public function getByTagID(int $tagID)
    {
        return $this->getProjectImpactTagBsWhere([
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
            FROM `project-impact-tags-b`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY projectID
        ");
        foreach ($query->fetch_all() AS $dbProjectImpactTagB) {
            $projectIDs[] = $dbProjectImpactTagB['projectID'];
        }

        return $projectIDs;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `project-impact-tags-b`");
        $query->query();
    }

    /**
     * @param $where
     * @return \DSI\Entity\ProjectImpactTagB[]
     */
    private function getProjectImpactTagBsWhere($where)
    {
        /** @var ProjectImpactTagB[] $projectTags */
        $projectTags = [];
        $query = new SQL("SELECT projectID, tagID 
            FROM `project-impact-tags-b`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbProjectImpactTagB) {
            $projectTag = new ProjectImpactTagB();
            $projectTag->setProject($this->projectRepo->getById($dbProjectImpactTagB['projectID']));
            $projectTag->setTag($this->tagsRepo->getById($dbProjectImpactTagB['tagID']));
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
            FROM `impact-tags` 
            LEFT JOIN `project-impact-tags-b` ON `impact-tags`.`id` = `project-impact-tags-b`.`tagID`
            WHERE `project-impact-tags-b`.`projectID` = '{$projectID}'
            ORDER BY `impact-tags`.`tag`
        ");
        return $query->fetch_all('tag');
    }
}