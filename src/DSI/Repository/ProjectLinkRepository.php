<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\ProjectLink;
use DSI\NotFound;
use DSI\Service\SQL;

class ProjectLinkRepository
{
    /** @var ProjectRepository */
    private $projectRepository;

    public function __construct()
    {
        $this->projectRepository = new ProjectRepository();
    }

    public function add(ProjectLink $projectLink)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-links`
            WHERE `projectID` = '{$projectLink->getProjectID()}'
            AND `link` = '{$projectLink->getLink()}'
            LIMIT 1
        ");
        if ($query->fetch('projectID') > 0)
            throw new DuplicateEntry("projectID: {$projectLink->getProjectID()} / link: {$projectLink->getLink()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectLink->getProjectID()) . "'";
        $insert[] = "`link` = '" . ($projectLink->getLink()) . "'";

        $query = new SQL("INSERT INTO `project-links` SET " . implode(', ', $insert) . "");
        $query->query();
    }

    public function remove(ProjectLink $projectLink)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-links`
            WHERE `projectID` = '{$projectLink->getProjectID()}'
            AND `link` = '{$projectLink->getLink()}'
            LIMIT 1
        ");
        if (!$query->fetch('projectID'))
            throw new NotFound("projectID: {$projectLink->getProjectID()} / link: {$projectLink->getLink()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectLink->getProjectID()) . "'";
        $insert[] = "`link` = '" . ($projectLink->getLink()) . "'";

        $query = new SQL("DELETE FROM `project-links` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $projectID
     * @return \DSI\Entity\ProjectLink[]
     */
    public function getByProjectID(int $projectID)
    {
        return $this->getProjectLinksWhere([
            "`projectID` = '{$projectID}'"
        ]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `project-links`");
        $query->query();
    }

    /**
     * @param $where
     * @return \DSI\Entity\ProjectLink[]
     */
    private function getProjectLinksWhere($where)
    {
        /** @var ProjectLink[] $projectLinks */
        $projectLinks = [];
        $query = new SQL("SELECT projectID, link 
            FROM `project-links`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbProjectLink) {
            $projectLink = new ProjectLink();
            $projectLink->setProject($this->projectRepository->getById($dbProjectLink['projectID']));
            $projectLink->setLink($dbProjectLink['link']);
            $projectLinks[] = $projectLink;
        }

        return $projectLinks;
    }

    /**
     * @param int $projectID
     * @return string[]
     */
    public function getLinksByProjectID(int $projectID)
    {
        $query = new SQL("SELECT link 
            FROM `project-links` 
            WHERE `projectID` = '{$projectID}'
            ORDER BY `link`
        ");
        return $query->fetch_all('link');
    }

    /**
     * @param int $projectID
     * @param string $link
     * @return bool
     */
    public function projectHasLink($projectID, $link){
        return in_array($link, $this->getLinksByProjectID($projectID));
    }
}