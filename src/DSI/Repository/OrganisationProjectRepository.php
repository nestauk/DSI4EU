<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\Organisation;
use DSI\Entity\OrganisationProject;
use DSI\Entity\Project;
use DSI\NotFound;
use DSI\Service\SQL;

class OrganisationProjectRepository
{
    /** @var OrganisationRepository */
    private $organisationRepo;

    /** @var ProjectRepository */
    private $projectsRepo;

    public function __construct()
    {
        $this->organisationRepo = new OrganisationRepository();
        $this->projectsRepo = new ProjectRepository();
    }

    public function add(OrganisationProject $organisationProject)
    {
        $query = new SQL("SELECT organisationID 
            FROM `organisation-projects`
            WHERE `organisationID` = '{$organisationProject->getOrganisationID()}'
            AND `projectID` = '{$organisationProject->getProjectID()}'
            LIMIT 1
        ");
        if ($query->fetch('organisationID') > 0)
            throw new DuplicateEntry("organisationID: {$organisationProject->getOrganisationID()} / projectID: {$organisationProject->getProjectID()}");

        $insert = array();
        $insert[] = "`organisationID` = '" . (int)($organisationProject->getOrganisationID()) . "'";
        $insert[] = "`projectID` = '" . (int)($organisationProject->getProjectID()) . "'";

        $query = new SQL("INSERT INTO `organisation-projects` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(OrganisationProject $organisationProject)
    {
        $query = new SQL("SELECT organisationID 
            FROM `organisation-projects`
            WHERE `organisationID` = '{$organisationProject->getOrganisationID()}'
            AND `projectID` = '{$organisationProject->getProjectID()}'
            LIMIT 1
        ");
        if (!$query->fetch('organisationID'))
            throw new NotFound("organisationID: {$organisationProject->getOrganisationID()} / projectID: {$organisationProject->getProjectID()}");

        $insert = array();
        $insert[] = "`organisationID` = '" . (int)($organisationProject->getOrganisationID()) . "'";
        $insert[] = "`projectID` = '" . (int)($organisationProject->getProjectID()) . "'";

        $query = new SQL("DELETE FROM `organisation-projects` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $organisationID
     * @return \DSI\Entity\OrganisationProject[]
     */
    public function getByOrganisationID(int $organisationID)
    {
        return $this->getOrganisationProjectsWhere([
            "`organisationID` = '{$organisationID}'"
        ]);
    }

    /**
     * @param Organisation $organisation
     * @return \int[]
     */
    public function getProjectIDsForOrganisation(Organisation $organisation)
    {
        $where = [
            "`organisationID` = '{$organisation->getId()}'"
        ];

        /** @var int[] $projectIDs */
        $projectIDs = [];
        $query = new SQL("SELECT projectID 
            FROM `organisation-projects`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY projectID
        ");
        foreach ($query->fetch_all() AS $dbOrganisationProjects) {
            $projectIDs[] = $dbOrganisationProjects['projectID'];
        }

        return $projectIDs;
    }

    /**
     * @param Organisation $organisation
     * @return Organisation[]
     */
    public function getPartnerOrganisationsFor(Organisation $organisation)
    {
        $projectIDs = $this->getProjectIDsForOrganisation($organisation);
        /** @var Organisation[] $partnerOrganisations */
        $partnerOrganisations = array_map(
            function (OrganisationProject $organisationProject) use ($organisation, $projectIDs) {
                $partnerOrganisation = $organisationProject->getOrganisation();
                if ($organisation->getId() == $partnerOrganisation->getId())
                    return null;

                $partnerProjectIDs = $this->getProjectIDsForOrganisation($partnerOrganisation);
                $partnerOrganisation->extraData['common-projects'] = count(array_intersect($projectIDs, $partnerProjectIDs));
                return $partnerOrganisation;
            },
            $this->getByProjectIDs($projectIDs)
        );

        $_existingOrgs = [];
        foreach ($partnerOrganisations AS &$partnerOrg) {
            if ($partnerOrg) {
                if (in_array($partnerOrg->getId(), $_existingOrgs)) {
                    $partnerOrg = null;
                } else {
                    $_existingOrgs[] = $partnerOrg->getId();
                }
            }
        }
        unset($partnerOrg);

        return array_filter($partnerOrganisations);
    }

    /**
     * @param int $projectID
     * @return \DSI\Entity\OrganisationProject[]
     */
    public function getByProjectID(int $projectID)
    {
        return $this->getOrganisationProjectsWhere([
            "`projectID` = '{$projectID}'"
        ]);
    }

    /**
     * @param int[] $projectIDs
     * @return \DSI\Entity\OrganisationProject[]
     */
    public function getByProjectIDs($projectIDs)
    {
        $projectIDs = array_map(function ($projectID) {
            return (int)$projectID;
        }, $projectIDs);

        if ($projectIDs) {
            return $this->getOrganisationProjectsWhere([
                "`projectID` IN (" . implode(',', $projectIDs) . ")"
            ]);
        } else {
            return [];
        }
    }

    /**
     * @param Project $project
     * @return \int[]
     */
    public function getOrganisationIDsForProject(Project $project)
    {
        $where = [
            "`projectID` = '{$project->getId()}'"
        ];

        /** @var int[] $organisationIDs */
        $organisationIDs = [];
        $query = new SQL("SELECT organisationID 
            FROM `organisation-projects`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY organisationID
        ");
        foreach ($query->fetch_all() AS $dbOrganisationProject) {
            $organisationIDs[] = $dbOrganisationProject['organisationID'];
        }

        return $organisationIDs;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `organisation-projects`");
        $query->query();
    }

    /**
     * @param $where
     * @return \DSI\Entity\OrganisationProject[]
     */
    private function getOrganisationProjectsWhere($where)
    {
        /** @var OrganisationProject[] $organisationProjects */
        $organisationProjects = [];
        $query = new SQL("SELECT organisationID, projectID 
            FROM `organisation-projects`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbOrganisationProject) {
            $organisationProject = new OrganisationProject();
            $organisationProject->setOrganisation($this->organisationRepo->getById($dbOrganisationProject['organisationID']));
            $organisationProject->setProject($this->projectsRepo->getById($dbOrganisationProject['projectID']));
            $organisationProjects[] = $organisationProject;
        }

        return $organisationProjects;
    }

    public function organisationHasProject(int $organisationID, int $projectID)
    {
        $organisationProjects = $this->getByOrganisationID($organisationID);
        foreach ($organisationProjects AS $organisationProject) {
            if ($projectID == $organisationProject->getProjectID()) {
                return true;
            }
        }

        return false;
    }
}