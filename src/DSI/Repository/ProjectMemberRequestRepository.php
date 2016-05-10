<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\ProjectMember;
use DSI\Entity\ProjectMemberRequest;
use DSI\NotFound;
use DSI\Service\SQL;

class ProjectMemberRequestRepository
{
    /** @var ProjectRepository */
    private $projectRepo;

    /** @var UserRepository */
    private $userRepo;

    public function __construct()
    {
        $this->projectRepo = new ProjectRepository();
        $this->userRepo = new UserRepository();
    }

    public function add(ProjectMemberRequest $projectMemberRequest)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-member-requests`
            WHERE `projectID` = '{$projectMemberRequest->getProjectID()}'
            AND `userID` = '{$projectMemberRequest->getMemberID()}'
            LIMIT 1
        ");
        if ($query->fetch('projectID') > 0)
            throw new DuplicateEntry("projectID: {$projectMemberRequest->getProjectID()} / userID: {$projectMemberRequest->getMemberID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectMemberRequest->getProjectID()) . "'";
        $insert[] = "`userID` = '" . (int)($projectMemberRequest->getMemberID()) . "'";

        $query = new SQL("INSERT INTO `project-member-requests` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(ProjectMemberRequest $projectMemberRequest)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-member-requests`
            WHERE `projectID` = '{$projectMemberRequest->getProjectID()}'
            AND `userID` = '{$projectMemberRequest->getMemberID()}'
            LIMIT 1
        ");
        if (!$query->fetch('projectID'))
            throw new NotFound("projectID: {$projectMemberRequest->getProjectID()} / userID: {$projectMemberRequest->getMemberID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectMemberRequest->getProjectID()) . "'";
        $insert[] = "`userID` = '" . (int)($projectMemberRequest->getMemberID()) . "'";

        $query = new SQL("DELETE FROM `project-member-requests` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $projectID
     * @return \DSI\Entity\ProjectMemberRequest[]
     */
    public function getByProjectID(int $projectID)
    {
        return $this->getProjectMemberRequestsWhere([
            "`projectID` = '{$projectID}'"
        ]);
    }

    /**
     * @param int $projectID
     * @return \int[]
     */
    public function getMemberIDsForProject(int $projectID)
    {
        $where = [
            "`projectID` = '{$projectID}'"
        ];

        /** @var int[] $memberIDs */
        $memberIDs = [];
        $query = new SQL("SELECT userID 
            FROM `project-member-requests`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY userID
        ");
        foreach ($query->fetch_all() AS $dbProjectMembers) {
            $memberIDs[] = $dbProjectMembers['userID'];
        }

        return $memberIDs;
    }

    public function getMembersForProject(int $projectID)
    {
        $members = [];
        $memberIDs = $this->getMemberIDsForProject($projectID);

        foreach ($memberIDs AS $memberID)
            $members[] = $this->userRepo->getById($memberID);

        return $members;
    }

    /**
     * @param int $userID
     * @return \DSI\Entity\ProjectMemberRequest[]
     */
    public function getByMemberID(int $userID)
    {
        return $this->getProjectMemberRequestsWhere([
            "`userID` = '{$userID}'"
        ]);
    }

    /**
     * @param int $userID
     * @return \int[]
     */
    public function getProjectIDsForMember(int $userID)
    {
        $where = [
            "`userID` = '{$userID}'"
        ];

        /** @var int[] $projectIDs */
        $projectIDs = [];
        $query = new SQL("SELECT projectID 
            FROM `project-member-requests`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY projectID
        ");
        foreach ($query->fetch_all() AS $dbProjectMembers) {
            $projectIDs[] = $dbProjectMembers['projectID'];
        }

        return $projectIDs;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `project-member-requests`");
        $query->query();
    }

    public function projectHasRequestFromMember(int $projectID, int $userID)
    {
        $projectMembers = $this->getByProjectID($projectID);
        foreach ($projectMembers AS $projectMember) {
            if ($userID == $projectMember->getMemberID()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $where
     * @return \DSI\Entity\ProjectMemberRequest[]
     */
    private function getProjectMemberRequestsWhere($where)
    {
        /** @var ProjectMemberRequest[] $projectMemberRequests */
        $projectMemberRequests = [];
        $query = new SQL("SELECT projectID, userID 
            FROM `project-member-requests`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbProjectMember) {
            $projectMember = new ProjectMember();
            $projectMember->setProject($this->projectRepo->getById($dbProjectMember['projectID']));
            $projectMember->setMember($this->userRepo->getById($dbProjectMember['userID']));
            $projectMemberRequests[] = $projectMember;
        }

        return $projectMemberRequests;
    }
}