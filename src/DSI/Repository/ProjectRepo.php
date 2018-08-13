<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\Project;
use DSI\Service\SQL;

class ProjectRepo
{
    public function __construct()
    {
    }

    public function insert(Project $project)
    {
        $insert = array();
        $insert[] = "`ownerID` = '" . addslashes($project->getOwnerID()) . "'";
        $insert[] = "`name` = '" . addslashes($project->getName()) . "'";
        $insert[] = "`shortDescription` = '" . addslashes($project->getShortDescription()) . "'";
        $insert[] = "`description` = '" . addslashes($project->getDescription()) . "'";
        $insert[] = "`url` = '" . addslashes($project->getUrl()) . "'";
        $insert[] = "`status` = '" . addslashes($project->getStatus()) . "'";
        $insert[] = "`startDate` = '" . addslashes($project->getStartDate()) . "'";
        $insert[] = "`endDate` = '" . addslashes($project->getEndDate()) . "'";
        $insert[] = "`lastUpdate` = NOW()";
        if ($project->getCountry())
            $insert[] = "`countryID` = '" . addslashes($project->getCountry()->getId()) . "'";
        if ($project->getRegion())
            $insert[] = "`countryRegionID` = '" . addslashes($project->getRegion()->getId()) . "'";
        $insert[] = "`organisationsCount` = '" . (int)($project->getOrganisationsCount()) . "'";
        $insert[] = "`importID` = '" . addslashes($project->getImportID()) . "'";
        $insert[] = "`logo` = '" . addslashes($project->getLogo()) . "'";
        $insert[] = "`headerImage` = '" . addslashes($project->getHeaderImage()) . "'";
        $insert[] = "`socialImpact` = '" . addslashes($project->getSocialImpact()) . "'";
        $insert[] = "`isWaitingApproval` = '" . (bool)($project->isWaitingApproval()) . "'";
        $insert[] = "`isPublished` = '" . (bool)($project->isPublished()) . "'";

        $query = new SQL("INSERT INTO `projects` SET " . implode(', ', $insert) . "");
        $query->query();

        $project->setId($query->insert_id());
        ProjectRepoInAPC::resetCache();
    }

    public function save(Project $project)
    {
        $query = new SQL("SELECT id FROM `projects` WHERE id = '{$project->getId()}' LIMIT 1");
        $existingProject = $query->fetch();
        if (!$existingProject)
            throw new DSI\NotFound('projectID: ' . $project->getId());

        $insert = array();
        $insert[] = "`ownerID` = '" . addslashes($project->getOwnerID()) . "'";
        $insert[] = "`name` = '" . addslashes($project->getName()) . "'";
        $insert[] = "`shortDescription` = '" . addslashes($project->getShortDescription()) . "'";
        $insert[] = "`description` = '" . addslashes($project->getDescription()) . "'";
        $insert[] = "`url` = '" . addslashes($project->getUrl()) . "'";
        $insert[] = "`status` = '" . addslashes($project->getStatus()) . "'";
        $insert[] = "`startDate` = '" . addslashes($project->getStartDate()) . "'";
        $insert[] = "`endDate` = '" . addslashes($project->getEndDate()) . "'";
        $insert[] = "`created` = '" . addslashes($project->getCreationTime()) . "'";
        $insert[] = "`lastUpdate` = '" . addslashes($project->getLastUpdate()) . "'";
        if ($project->getCountry())
            $insert[] = "`countryID` = '" . addslashes($project->getCountry()->getId()) . "'";
        if ($project->getRegion())
            $insert[] = "`countryRegionID` = '" . addslashes($project->getRegion()->getId()) . "'";
        $insert[] = "`organisationsCount` = '" . (int)($project->getOrganisationsCount()) . "'";
        $insert[] = "`importID` = '" . addslashes($project->getImportID()) . "'";
        $insert[] = "`logo` = '" . addslashes($project->getLogo()) . "'";
        $insert[] = "`headerImage` = '" . addslashes($project->getHeaderImage()) . "'";
        $insert[] = "`socialImpact` = '" . addslashes($project->getSocialImpact()) . "'";
        $insert[] = "`isWaitingApproval` = '" . (bool)($project->isWaitingApproval()) . "'";
        $insert[] = "`isPublished` = '" . (bool)($project->isPublished()) . "'";

        $query = new SQL("UPDATE `projects` SET " . implode(', ', $insert) . " WHERE `id` = '{$project->getId()}'");
        $query->query();
        ProjectRepoInAPC::resetCache();
    }

    public function remove(Project $project)
    {
        $query = new SQL("SELECT id FROM `projects` WHERE id = '{$project->getId()}' LIMIT 1");
        $existingProject = $query->fetch();
        if (!$existingProject)
            throw new DSI\NotFound('projectID: ' . $project->getId());

        $query = new SQL("DELETE FROM `projects` WHERE `id` = '{$project->getId()}'");
        $query->query();
        ProjectRepoInAPC::resetCache();
    }

    public function getById(int $id): Project
    {
        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    public function getByName(string $name): Project
    {
        return $this->getObjectWhere([
            "`name` = '" . addslashes($name) . "'"
        ]);
    }

    public function getByImportID(string $importID): Project
    {
        return $this->getObjectWhere([
            "`importID` = '" . addslashes($importID) . "'"
        ]);
    }

    /**
     * @param $project
     * @return Project
     */
    private function buildProjectFromData($project)
    {
        $projectObj = new Project();
        $projectObj->setId($project['id']);

        $projectObj->setOwner(
            (new UserRepo())->getById($project['ownerID'])
        );
        $projectObj->setName($project['name']);
        $projectObj->setShortDescription($project['shortDescription']);
        $projectObj->setDescription($project['description']);
        $projectObj->setUrl($project['url']);
        $projectObj->setStatus($project['status']);
        $projectObj->setStartDate($project['startDate'] != '0000-00-00' ? $project['startDate'] : NULL);
        $projectObj->setEndDate($project['endDate'] != '0000-00-00' ? $project['endDate'] : NULL);
        $projectObj->setCreationTime($project['created'] != '0000-00-00 00:00:00' ? $project['created'] : NULL);
        $projectObj->setLastUpdate($project['lastUpdate'] != '0000-00-00 00:00:00' ? $project['lastUpdate'] : NULL);
        if ($project['countryRegionID']) {
            $projectObj->setCountryRegion(
                (new CountryRegionRepo())->getById($project['countryRegionID'])
            );
        }
        $projectObj->setOrganisationsCount($project['organisationsCount']);
        $projectObj->setImportID($project['importID']);
        $projectObj->setLogo($project['logo']);
        $projectObj->setHeaderImage($project['headerImage']);
        $projectObj->setSocialImpact($project['socialImpact']);
        $projectObj->setIsWaitingApproval($project['isWaitingApproval']);
        $projectObj->setIsPublished($project['isPublished']);

        return $projectObj;
    }

    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    public function getAllPublished()
    {
        return $this->getObjectsWhere([
            "`isPublished` = '1'"
        ]);
    }

    public function getByUser(DSI\Entity\User $user)
    {
        return $this->getObjectsWhere([
            "`ownerID` = '" . $user->getId() . "'"
        ]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `projects`");
        $query->query();
        ProjectRepoInAPC::resetCache();
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
     * @param array $options
     * @return Project[]
     */
    private function getObjectsWhere($where, $options = [])
    {
        $projects = [];
        $query = new SQL("SELECT 
            id, ownerID, name
          , shortDescription, description, url, status
          , startDate, endDate, created, lastUpdate
          , countryRegionID, organisationsCount
          , importID, logo, headerImage
          , socialImpact
          , isWaitingApproval
          , isPublished
          FROM `projects`
          WHERE " . implode(' AND ', $where) . "
          ORDER BY `name`
          " . ((isset($options['limit']) AND $options['limit'] > 0) ? "LIMIT {$options['limit']}" : '') . "
        ");
        foreach ($query->fetch_all() AS $dbProject) {
            $projects[] = $this->buildProjectFromData($dbProject);
        }
        return $projects;
    }

    /**
     * @return int
     */
    public function countAll()
    {
        return $this->countObjectsWhere(['1']);
    }

    /**
     * @param $where
     * @return int
     */
    private function countObjectsWhere($where)
    {
        $query = new SQL("SELECT count(id) AS `total`
          FROM `projects`
          WHERE " . implode(' AND ', $where) . "
        ");
        return $query->fetch('total');
    }

    public function searchByTitle(string $name, int $limit = 0)
    {
        return $this->getObjectsWhere([
            "`name` LIKE '%" . addslashes($name) . "%'"
        ], [
            "limit" => $limit
        ]);
    }
}