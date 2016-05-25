<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\ProjectEmailInvitation;
use DSI\NotFound;
use DSI\Service\SQL;

class ProjectEmailInvitationRepository
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

    public function add(ProjectEmailInvitation $projectEmailInvitation)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-email-invitations`
            WHERE `projectID` = '{$projectEmailInvitation->getProjectID()}'
            AND `email` = '" . addslashes($projectEmailInvitation->getEmail()) . "'
            LIMIT 1
        ");
        if ($query->fetch('projectID') > 0)
            throw new DuplicateEntry("projectID: {$projectEmailInvitation->getProjectID()} / email: {$projectEmailInvitation->getEmail()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectEmailInvitation->getProjectID()) . "'";
        $insert[] = "`byUserID` = '" . (int)($projectEmailInvitation->getByUserID()) . "'";
        $insert[] = "`email` = '" . addslashes($projectEmailInvitation->getEmail()) . "'";

        $query = new SQL("INSERT INTO `project-email-invitations` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(ProjectEmailInvitation $projectEmailInvitation)
    {
        $query = new SQL("SELECT projectID 
            FROM `project-email-invitations`
            WHERE `projectID` = '{$projectEmailInvitation->getProjectID()}'
            AND `email` = '" . addslashes($projectEmailInvitation->getEmail()) . "'
            LIMIT 1
        ");
        if (!$query->fetch('projectID'))
            throw new NotFound("projectID: {$projectEmailInvitation->getProjectID()} / email: {$projectEmailInvitation->getEmail()}");

        $insert = array();
        $insert[] = "`projectID` = '" . (int)($projectEmailInvitation->getProjectID()) . "'";
        $insert[] = "`byUserID` = '" . (int)($projectEmailInvitation->getByUserID()) . "'";
        $insert[] = "`email` = '" . addslashes($projectEmailInvitation->getEmail()) . "'";

        $query = new SQL("DELETE FROM `project-email-invitations` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $projectID
     * @return \DSI\Entity\ProjectEmailInvitation[]
     */
    public function getByProjectID(int $projectID)
    {
        return $this->getProjectEmailInvitationsWhere([
            "`projectID` = '{$projectID}'"
        ]);
    }

    /**
     * @param string $email
     * @return \DSI\Entity\ProjectEmailInvitation[]
     */
    public function getByEmail(string $email)
    {
        return $this->getProjectEmailInvitationsWhere([
            "`email` = '" . addslashes($email) . "'"
        ]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `project-email-invitations`");
        $query->query();
    }

    /**
     * @param $where
     * @return \DSI\Entity\ProjectEmailInvitation[]
     */
    private function getProjectEmailInvitationsWhere($where)
    {
        /** @var ProjectEmailInvitation[] $projectEmailInvitations */
        $projectEmailInvitations = [];
        $query = new SQL("SELECT projectID, byUserID, email, `date`
            FROM `project-email-invitations`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbProjectEmailInvitation) {
            $projectEmailInvitation = new ProjectEmailInvitation();
            $projectEmailInvitation->setProject($this->projectRepo->getById($dbProjectEmailInvitation['projectID']));
            $projectEmailInvitation->setByUser($this->userRepo->getById($dbProjectEmailInvitation['byUserID']));
            $projectEmailInvitation->setEmail($dbProjectEmailInvitation['email']);
            $projectEmailInvitation->setDate($dbProjectEmailInvitation['date']);
            $projectEmailInvitations[] = $projectEmailInvitation;
        }

        return $projectEmailInvitations;
    }

    public function projectInvitedEmail(int $projectID, string $email)
    {
        $invitedEmails = $this->getByProjectID($projectID);
        foreach ($invitedEmails AS $invitedEmail) {
            if ($email == $invitedEmail->getEmail()) {
                return true;
            }
        }

        return false;
    }
}