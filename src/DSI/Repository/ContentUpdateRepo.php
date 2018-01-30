<?php

namespace DSI\Repository;

use DSI\Entity\ContentUpdate;
use DSI\Entity\Organisation;
use DSI\Entity\Project;
use DSI\NotFound;
use DSI\Service\SQL;
use DSI\UseCase\Projects\RemoveProject;
use DSI\UseCase\WaitingApproval\RejectWaitingApproval;

class ContentUpdateRepo
{
    private $dbTable = 'content-updates';

    public function insert(ContentUpdate $contentUpdate)
    {
        $insert = array();
        $insert[] = "`projectID` = '" . (int)($contentUpdate->getProjectID()) . "'";
        $insert[] = "`organisationID` = '" . (int)($contentUpdate->getOrganisationID()) . "'";
        $insert[] = "`updated` = '" . addslashes($contentUpdate->getUpdated()) . "'";

        $query = new SQL("INSERT INTO `{$this->dbTable}` SET " . implode(', ', $insert));
        $query->query();

        $contentUpdate->setId($query->insert_id());
    }

    public function save(ContentUpdate $contentUpdate)
    {
        $query = new SQL("SELECT id FROM `{$this->dbTable}` WHERE id = '{$contentUpdate->getId()}' LIMIT 1");
        $existingObject = $query->fetch();
        if (!$existingObject)
            throw new NotFound('content update ID: ' . $contentUpdate->getId());

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($contentUpdate->getProjectID()) . "'";
        $insert[] = "`organisationID` = '" . (int)($contentUpdate->getOrganisationID()) . "'";
        $insert[] = "`updated` = '" . addslashes($contentUpdate->getUpdated()) . "'";

        $query = new SQL("UPDATE `{$this->dbTable}` SET " . implode(', ', $insert) . " WHERE `id` = '{$contentUpdate->getId()}'");
        $query->query();
    }

    public function delete(ContentUpdate $contentUpdate)
    {
        $query = new SQL("DELETE FROM `{$this->dbTable}` WHERE id = '{$contentUpdate->getId()}' LIMIT 1");
        $query->query();
    }

    /**
     * @param int $id
     * @return ContentUpdate
     * @throws NotFound
     */
    public function getById(int $id)
    {
        return $this->getObjectWhere([
            "`id` = {$id}"
        ]);
    }

    /**
     * @param Project $project
     * @return ContentUpdate[]
     */
    public function getByProject(Project $project)
    {
        return $this->getObjectsWhere([
            "`projectID` = {$project->getId()}"
        ]);
    }

    /**
     * @param Organisation $organisation
     * @return ContentUpdate[]
     */
    public function getByOrganisation(Organisation $organisation)
    {
        return $this->getObjectsWhere([
            "`organisationID` = {$organisation->getId()}"
        ]);
    }

    public function getByDate(string $date)
    {
        return $this->getObjectsWhere([
            "`timestamp` BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'"
        ]);
    }

    /** @return ContentUpdate[] */
    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `{$this->dbTable}`");
        $query->query();
    }

    /**
     * @param $where
     * @return ContentUpdate
     * @throws NotFound
     */
    private function getObjectWhere($where)
    {
        $objects = $this->getObjectsWhere($where);
        if (count($objects) < 1)
            throw new NotFound();

        return $objects[0];
    }

    /**
     * @param $where
     * @return ContentUpdate[]
     */
    private function getObjectsWhere($where)
    {
        $objects = [];
        $query = new SQL("SELECT 
              id, projectID, organisationID, updated, `timestamp`
          FROM `{$this->dbTable}` WHERE " . implode(' AND ', $where) . "
          ORDER BY `timestamp`");
        foreach ($query->fetch_all() AS $dbObject) {
            $contentUpdate = new ContentUpdate();
            $contentUpdate->setId($dbObject['id']);
            $contentUpdate->setUpdated($dbObject['updated']);
            $contentUpdate->setTimestamp($dbObject['timestamp']);

            if ($dbObject['projectID']) {
                try {
                    $project = (new ProjectRepo())->getById($dbObject['projectID']);
                    $contentUpdate->setProject($project);
                } catch (NotFound $e){
                    $this->delete($contentUpdate);
                    continue;
                }
            }

            if ($dbObject['organisationID']) {
                try {
                    $organisation = (new OrganisationRepo())->getById($dbObject['organisationID']);
                    $contentUpdate->setOrganisation($organisation);
                } catch (NotFound $e){
                    $this->delete($contentUpdate);
                    continue;
                }
            }

            $objects[] = $contentUpdate;
        }
        return $objects;
    }
}