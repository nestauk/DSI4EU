<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\Project;
use DSI\Entity\ProjectMemberInvitation;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Service\SQL;

class ProjectMemberInvitationRepo
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

    public function add(ProjectMemberInvitation $projectMemberInvitation)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-member-invitations`
            WHERE `projectID` = '{$projectMemberInvitation->getProjectID()}'
            AND `userID` = '{$projectMemberInvitation->getMemberID()}'
            LIMIT 1
        ");
        if ($query->fetch('projectID') > 0)
            throw new DuplicateEntry("projectID: {$projectMemberInvitation->getProjectID()} / userID: {$projectMemberInvitation->getMemberID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectMemberInvitation->getProjectID()) . "'";
        $insert[] = "`userID` = '" . (int)($projectMemberInvitation->getMemberID()) . "'";

        $query = new SQL("INSERT INTO `project-member-invitations` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(ProjectMemberInvitation $projectMemberInvitation)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-member-invitations`
            WHERE `projectID` = '{$projectMemberInvitation->getProjectID()}'
            AND `userID` = '{$projectMemberInvitation->getMemberID()}'
            LIMIT 1
        ");
        if (!$query->fetch('projectID'))
            throw new NotFound("projectID: {$projectMemberInvitation->getProjectID()} / userID: {$projectMemberInvitation->getMemberID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectMemberInvitation->getProjectID()) . "'";
        $insert[] = "`userID` = '" . (int)($projectMemberInvitation->getMemberID()) . "'";

        $query = new SQL("DELETE FROM `project-member-invitations` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param Project $project
     * @return \DSI\Entity\ProjectMemberInvitation[]
     */
    public function getByProject(Project $project)
    {
        return $this->getObjectsWhere([
            "`projectID` = '{$project->getId()}'"
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
            FROM `project-member-invitations`
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
     * @return \DSI\Entity\ProjectMemberInvitation[]
     */
    public function getByMemberID(int $userID)
    {
        return $this->getObjectsWhere([
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
            FROM `project-member-invitations`
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
        $query = new SQL("TRUNCATE TABLE `project-member-invitations`");
        $query->query();
    }

    public function userHasBeenInvitedToProject(User $user, Project $project)
    {
        $projectMembers = $this->getByProject($project);
        foreach ($projectMembers AS $projectMember) {
            if ($user->getId() == $projectMember->getMemberID()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $where
     * @return \DSI\Entity\ProjectMemberInvitation[]
     */
    private function getObjectsWhere($where)
    {
        /** @var ProjectMemberInvitation[] $projectMemberInvitations */
        $projectMemberInvitations = [];
        $query = new SQL("SELECT projectID, userID 
            FROM `project-member-invitations`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbProjectMember) {
            $projectMember = new ProjectMemberInvitation();
            $projectMember->setProject($this->projectRepo->getById($dbProjectMember['projectID']));
            $projectMember->setMember($this->userRepo->getById($dbProjectMember['userID']));
            $projectMemberInvitations[] = $projectMember;
        }

        return $projectMemberInvitations;
    }
}