<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\User;
use DSI\Entity\Project;
use DSI\Service\SQL;

class ProjectRepository
{
    public function saveAsNew(Project $project)
    {
        $insert = array();
        $insert[] = "`ownerID` = '" . addslashes($project->getOwner()->getId()) . "'";
        $insert[] = "`name` = '" . addslashes($project->getName()) . "'";
        $insert[] = "`description` = '" . addslashes($project->getDescription()) . "'";

        $query = new SQL("INSERT INTO `projects` SET " . implode(', ', $insert) . "");
        $query->query();

        $project->setId($query->insert_id());
    }

    public function save(Project $project)
    {
        $query = new SQL("SELECT id FROM `projects` WHERE id = '{$project->getId()}' LIMIT 1");
        $existingProject = $query->fetch();
        if (!$existingProject)
            throw new DSI\NotFound('projectID: ' . $project->getId());

        $insert = array();
        $insert[] = "`ownerID` = '" . addslashes($project->getOwner()->getId()) . "'";
        $insert[] = "`name` = '" . addslashes($project->getName()) . "'";
        $insert[] = "`description` = '" . addslashes($project->getDescription()) . "'";

        $query = new SQL("UPDATE `projects` SET " . implode(', ', $insert) . " WHERE `id` = '{$project->getId()}'");
        $query->query();
    }

    public function getById(int $id): Project
    {
        return $this->getProjectWhere([
            "`id` = {$id}"
        ]);
    }

    private function buildProjectFromData($project)
    {
        $projectObj = new Project();
        $projectObj->setId($project['id']);

        $projectObj->setOwner(
            (new UserRepository())->getById($project['ownerID'])
        );
        $projectObj->setName($project['name']);
        $projectObj->setDescription($project['description']);
        return $projectObj;
    }

    public function getAll()
    {
        $where = ["1"];
        $projects = [];
        $query = new SQL("SELECT 
            id, ownerID, name, description
          FROM `projects` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbProject) {
            $projects[] = $this->buildProjectFromData($dbProject);
        }
        return $projects;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `projects`");
        $query->query();
    }

    private function getProjectWhere($where)
    {
        $query = new SQL("SELECT 
              id, ownerID, name, description
            FROM `projects` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        $dbProject = $query->fetch();
        if (!$dbProject) {
            throw new DSI\NotFound();
        }

        return $this->buildProjectFromData($dbProject);
    }
}