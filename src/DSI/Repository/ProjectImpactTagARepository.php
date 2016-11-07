<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\Project;
use DSI\Entity\ProjectImpactTagA;
use DSI\NotFound;
use DSI\Service\SQL;

class ProjectImpactTagARepository
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

    public function add(ProjectImpactTagA $projectTag)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-impact-tags-a`
            WHERE `projectID` = '{$projectTag->getProjectID()}'
            AND `tagID` = '{$projectTag->getTagID()}'
            LIMIT 1
        ");
        if ($query->fetch('projectID') > 0)
            throw new DuplicateEntry("projectID: {$projectTag->getProjectID()} / tagID: {$projectTag->getTagID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectTag->getProjectID()) . "'";
        $insert[] = "`tagID` = '" . (int)($projectTag->getTagID()) . "'";

        $query = new SQL("INSERT INTO `project-impact-tags-a` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(ProjectImpactTagA $projectTag)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-impact-tags-a`
            WHERE `projectID` = '{$projectTag->getProjectID()}'
            AND `tagID` = '{$projectTag->getTagID()}'
            LIMIT 1
        ");
        if (!$query->fetch('projectID'))
            throw new NotFound("projectID: {$projectTag->getProjectID()} / tagID: {$projectTag->getTagID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectTag->getProjectID()) . "'";
        $insert[] = "`tagID` = '" . (int)($projectTag->getTagID()) . "'";

        $query = new SQL("DELETE FROM `project-impact-tags-a` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $projectID
     * @return \DSI\Entity\ProjectImpactTagA[]
     */
    public function getByProjectID(int $projectID)
    {
        return $this->getProjectImpactTagAsWhere([
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
            FROM `project-impact-tags-a`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY tagID
        ");
        foreach ($query->fetch_all() AS $dbProjectImpactTagAs) {
            $tagIDs[] = $dbProjectImpactTagAs['tagID'];
        }

        return $tagIDs;
    }

    /**
     * @param int $tagID
     * @return \DSI\Entity\ProjectImpactTagA[]
     */
    public function getByTagID(int $tagID)
    {
        return $this->getProjectImpactTagAsWhere([
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
            FROM `project-impact-tags-a`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY projectID
        ");
        foreach ($query->fetch_all() AS $dbProjectImpactTagA) {
            $projectIDs[] = $dbProjectImpactTagA['projectID'];
        }

        return $projectIDs;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `project-impact-tags-a`");
        $query->query();
    }

    /**
     * @param $where
     * @return \DSI\Entity\ProjectImpactTagA[]
     */
    private function getProjectImpactTagAsWhere($where)
    {
        /** @var ProjectImpactTagA[] $projectTags */
        $projectTags = [];
        $query = new SQL("SELECT projectID, tagID 
            FROM `project-impact-tags-a`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbProjectImpactTagA) {
            $projectTag = new ProjectImpactTagA();
            $projectTag->setProject($this->projectRepo->getById($dbProjectImpactTagA['projectID']));
            $projectTag->setTag($this->tagsRepo->getById($dbProjectImpactTagA['tagID']));
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
            LEFT JOIN `project-impact-tags-a` ON `impact-tags`.`id` = `project-impact-tags-a`.`tagID`
            WHERE `project-impact-tags-a`.`projectID` = '{$project->getId()}'
            ORDER BY `impact-tags`.`tag`
        ");
        return $query->fetch_all('tag');
    }
}