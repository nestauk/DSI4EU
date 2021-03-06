<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\Project;
use DSI\Entity\ProjectMember;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Service\SQL;

class ProjectMemberRepo
{
    /** @var ProjectRepo */
    private $projectRepo;

    /** @var UserRepo */
    private $userRepo;

    public function __construct()
    {
        $this->projectRepo = new ProjectRepoInAPC();
        $this->userRepo = new UserRepo();
    }

    public function insert(ProjectMember $projectMember)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-members`
            WHERE `projectID` = '{$projectMember->getProjectID()}'
            AND `userID` = '{$projectMember->getMemberID()}'
            LIMIT 1
        ");
        if ($query->fetch('projectID') > 0)
            throw new DuplicateEntry("projectID: {$projectMember->getProjectID()} / userID: {$projectMember->getMemberID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectMember->getProjectID()) . "'";
        $insert[] = "`userID` = '" . (int)($projectMember->getMemberID()) . "'";
        $insert[] = "`isAdmin` = '" . (bool)($projectMember->isAdmin()) . "'";

        $query = new SQL("INSERT INTO `project-members` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function save(ProjectMember $projectMember)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-members`
            WHERE `projectID` = '{$projectMember->getProjectID()}'
            AND `userID` = '{$projectMember->getMemberID()}'
            LIMIT 1
        ");
        if (!$query->fetch('projectID'))
            throw new NotFound("projectID: {$projectMember->getProjectID()} / userID: {$projectMember->getMemberID()}");

        $insert = [];
        $insert[] = "`isAdmin` = '" . (bool)($projectMember->isAdmin()) . "'";
        $where = [];
        $where[] = "`projectID` = '" . (int)($projectMember->getProjectID()) . "'";
        $where[] = "`userID` = '" . (int)($projectMember->getMemberID()) . "'";

        $query = new SQL("UPDATE `project-members` SET " . implode(', ', $insert) . " WHERE " . implode(' AND ', $where) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(ProjectMember $projectMember)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-members`
            WHERE `projectID` = '{$projectMember->getProjectID()}'
            AND `userID` = '{$projectMember->getMemberID()}'
            LIMIT 1
        ");
        if (!$query->fetch('projectID'))
            throw new NotFound("projectID: {$projectMember->getProjectID()} / userID: {$projectMember->getMemberID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectMember->getProjectID()) . "'";
        $insert[] = "`userID` = '" . (int)($projectMember->getMemberID()) . "'";

        $query = new SQL("DELETE FROM `project-members` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $projectID
     * @return \DSI\Entity\ProjectMember[]
     */
    public function getByProjectID(int $projectID)
    {
        return $this->getProjectMembersWhere([
            "`projectID` = '{$projectID}'"
        ]);
    }

    /**
     * @param Project $project
     * @return ProjectMember[]
     */
    public function getByProject(Project $project)
    {
        return $this->getProjectMembersWhere([
            "`projectID` = '{$project->getId()}'"
        ]);
    }

    /**
     * @param Project $project
     * @param User $member
     * @return ProjectMember
     */
    public function getByProjectAndMember(Project $project, User $member)
    {
        $projectMember = $this->getProjectMembersWhere([
            "`projectID` = '{$project->getId()}'",
            "`userID` = '{$member->getId()}'",
        ]);
        return isset($projectMember[0]) ? $projectMember[0] : null;
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
            FROM `project-members`
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
     * @return \DSI\Entity\ProjectMember[]
     */
    public function getByMemberID(int $userID)
    {
        return $this->getProjectMembersWhere([
            "`userID` = '{$userID}'"
        ]);
    }

    /**
     * @param User $user
     * @return \DSI\Entity\ProjectMember[]
     */
    public function getByAdmin(User $user)
    {
        return $this->getProjectMembersWhere([
            "`userID` = " . $user->getId() . "",
            "`isAdmin` = 1",
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
            FROM `project-members`
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
        $query = new SQL("TRUNCATE TABLE `project-members`");
        $query->query();
    }

    /**
     * @param $where
     * @return \DSI\Entity\ProjectMember[]
     */
    private function getProjectMembersWhere($where)
    {
        /** @var ProjectMember[] $projectMembers */
        $projectMembers = [];
        $query = new SQL("SELECT projectID, userID, isAdmin
            FROM `project-members`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbProjectMember) {
            $projectMember = new ProjectMember();
            $projectMember->setProject($this->projectRepo->getById($dbProjectMember['projectID']));
            $projectMember->setMember($this->userRepo->getById($dbProjectMember['userID']));
            $projectMember->setIsAdmin($dbProjectMember['isAdmin']);
            $projectMembers[] = $projectMember;
        }

        return $projectMembers;
    }

    public function projectIDHasMemberID(int $projectID, int $userID)
    {
        $projectMembers = $this->getByProjectID($projectID);
        foreach ($projectMembers AS $projectMember) {
            if ($userID == $projectMember->getMemberID()) {
                return true;
            }
        }

        return false;
    }

    public function projectHasMember(Project $project, User $user)
    {
        return $this->projectIDHasMemberID($project->getId(), $user->getId());
    }
}