<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\Project;
use DSI\Entity\ProjectFollow;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Service\SQL;

class ProjectFollowRepository
{
    private $table = 'project-follow';
    /** @var ProjectRepository */
    private $projectRepo;

    /** @var UserRepository */
    private $userRepo;

    public function __construct()
    {
        $this->projectRepo = new ProjectRepositoryInAPC();
        $this->userRepo = new UserRepository();
    }

    public function add(ProjectFollow $projectFollow)
    {
        $query = new SQL("SELECT projectID 
            FROM `{$this->table}`
            WHERE `projectID` = '{$projectFollow->getProjectID()}'
              AND `userID` = '{$projectFollow->getUserID()}'
            LIMIT 1
        ");
        if ($query->fetch('projectID') > 0)
            throw new DuplicateEntry("projectID: {$projectFollow->getProjectID()} / userID: {$projectFollow->getUserID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectFollow->getProjectID()) . "'";
        $insert[] = "`userID` = '" . (int)($projectFollow->getUserID()) . "'";

        $query = new SQL("INSERT INTO `{$this->table}` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(ProjectFollow $projectFollow)
    {
        $query = new SQL("SELECT projectID 
            FROM `{$this->table}`
            WHERE `projectID` = '{$projectFollow->getProjectID()}'
              AND `userID` = '{$projectFollow->getUserID()}'
            LIMIT 1
        ");
        if (!$query->fetch('projectID'))
            throw new NotFound("projectID: {$projectFollow->getProjectID()} / userID: {$projectFollow->getUserID()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectFollow->getProjectID()) . "'";
        $insert[] = "`userID` = '" . (int)($projectFollow->getUserID()) . "'";

        $query = new SQL("DELETE FROM `{$this->table}` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $projectID
     * @return \DSI\Entity\ProjectFollow[]
     */
    public function getByProjectID(int $projectID)
    {
        return $this->getObjectsWhere([
            "`projectID` = '{$projectID}'"
        ]);
    }

    /**
     * @param Project $project
     * @return \int[]
     */
    public function getUserIDsForProject(Project $project)
    {
        $where = [
            "`projectID` = '{$project->getId()}'"
        ];

        /** @var int[] $userIDs */
        $userIDs = [];
        $query = new SQL("SELECT userID 
            FROM `{$this->table}`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY userID
        ");
        foreach ($query->fetch_all() AS $dbProjectFollows) {
            $userIDs[] = $dbProjectFollows['userID'];
        }

        return $userIDs;
    }

    /**
     * @param User $user
     * @return \DSI\Entity\ProjectFollow[]
     */
    public function getByUser(User $user)
    {
        return $this->getObjectsWhere([
            "`userID` = '{$user->getId()}'"
        ]);
    }

    /**
     * @param User $user
     * @return \int[]
     */
    public function getProjectIDsForUser(User $user)
    {
        $where = [
            "`userID` = '{$user->getId()}'"
        ];

        /** @var int[] $projectIDs */
        $projectIDs = [];
        $query = new SQL("SELECT projectID 
            FROM `{$this->table}`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY projectID
        ");
        foreach ($query->fetch_all() AS $dbProjectFollow) {
            $projectIDs[] = $dbProjectFollow['projectID'];
        }

        return $projectIDs;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `{$this->table}`");
        $query->query();
    }

    /**
     * @param $where
     * @return \DSI\Entity\ProjectFollow[]
     */
    private function getObjectsWhere($where)
    {
        /** @var ProjectFollow[] $projectFollows */
        $projectFollows = [];
        $query = new SQL("SELECT projectID, userID 
            FROM `{$this->table}`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbProjectFollow) {
            $projectFollow = new ProjectFollow();
            $projectFollow->setProject($this->projectRepo->getById($dbProjectFollow['projectID']));
            $projectFollow->setUser($this->userRepo->getById($dbProjectFollow['userID']));
            $projectFollows[] = $projectFollow;
        }

        return $projectFollows;
    }

    /**
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function userFollowsProject(User $user, Project $project)
    {
        $projectFollows = $this->getByProjectID($project->getId());
        foreach ($projectFollows AS $projectFollow) {
            if ($user->getId() == $projectFollow->getUserID()) {
                return true;
            }
        }

        return false;
    }
}