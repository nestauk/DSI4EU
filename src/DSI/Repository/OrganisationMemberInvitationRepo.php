<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\Organisation;
use DSI\Entity\OrganisationMemberInvitation;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Service\SQL;

class OrganisationMemberInvitationRepo
{
    /** @var OrganisationRepo */
    private $organisationRepo;

    /** @var UserRepo */
    private $userRepo;

    public function __construct()
    {
        $this->organisationRepo = new OrganisationRepoInAPC();
        $this->userRepo = new UserRepo();
    }

    public function add(OrganisationMemberInvitation $organisationMemberInvitation)
    {
        $query = new SQL("SELECT organisationID 
            FROM `organisation-member-invitations`
            WHERE `organisationID` = '{$organisationMemberInvitation->getOrganisationID()}'
            AND `userID` = '{$organisationMemberInvitation->getMemberID()}'
            LIMIT 1
        ");
        if ($query->fetch('organisationID') > 0)
            throw new DuplicateEntry("organisationID: {$organisationMemberInvitation->getOrganisationID()} / userID: {$organisationMemberInvitation->getMemberID()}");

        $insert = array();
        $insert[] = "`organisationID` = '" . (int)($organisationMemberInvitation->getOrganisationID()) . "'";
        $insert[] = "`userID` = '" . (int)($organisationMemberInvitation->getMemberID()) . "'";

        $query = new SQL("INSERT INTO `organisation-member-invitations` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(OrganisationMemberInvitation $organisationMemberInvitation)
    {
        $query = new SQL("SELECT organisationID 
            FROM `organisation-member-invitations`
            WHERE `organisationID` = '{$organisationMemberInvitation->getOrganisationID()}'
            AND `userID` = '{$organisationMemberInvitation->getMemberID()}'
            LIMIT 1
        ");
        if (!$query->fetch('organisationID'))
            throw new NotFound("organisationID: {$organisationMemberInvitation->getOrganisationID()} / userID: {$organisationMemberInvitation->getMemberID()}");

        $insert = array();
        $insert[] = "`organisationID` = '" . (int)($organisationMemberInvitation->getOrganisationID()) . "'";
        $insert[] = "`userID` = '" . (int)($organisationMemberInvitation->getMemberID()) . "'";

        $query = new SQL("DELETE FROM `organisation-member-invitations` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $organisationID
     * @return \DSI\Entity\OrganisationMemberInvitation[]
     */
    public function getByOrganisationID(int $organisationID)
    {
        return $this->getObjectsWhere([
            "`organisationID` = '{$organisationID}'"
        ]);
    }

    /**
     * @param Organisation $organisation
     * @return \DSI\Entity\OrganisationMemberInvitation[]
     */
    public function getByOrganisation(Organisation $organisation)
    {
        return $this->getObjectsWhere([
            "`organisationID` = '{$organisation->getId()}'"
        ]);
    }

    /**
     * @param int $organisationID
     * @return \int[]
     */
    public function getMemberIDsForOrganisation(int $organisationID)
    {
        $where = [
            "`organisationID` = '{$organisationID}'"
        ];

        /** @var int[] $memberIDs */
        $memberIDs = [];
        $query = new SQL("SELECT userID 
            FROM `organisation-member-invitations`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY userID
        ");
        foreach ($query->fetch_all() AS $dbOrganisationMembers) {
            $memberIDs[] = $dbOrganisationMembers['userID'];
        }

        return $memberIDs;
    }

    public function getMembersForOrganisation(int $organisationID)
    {
        $members = [];
        $memberIDs = $this->getMemberIDsForOrganisation($organisationID);

        foreach ($memberIDs AS $memberID)
            $members[] = $this->userRepo->getById($memberID);

        return $members;
    }

    /**
     * @param int $userID
     * @return \DSI\Entity\OrganisationMemberInvitation[]
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
    public function getOrganisationIDsForMember(int $userID)
    {
        $where = [
            "`userID` = '{$userID}'"
        ];

        /** @var int[] $organisationIDs */
        $organisationIDs = [];
        $query = new SQL("SELECT organisationID 
            FROM `organisation-member-invitations`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY organisationID
        ");
        foreach ($query->fetch_all() AS $dbOrganisationMembers) {
            $organisationIDs[] = $dbOrganisationMembers['organisationID'];
        }

        return $organisationIDs;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `organisation-member-invitations`");
        $query->query();
    }

    public function userIdHasInvitationToOrganisationId(int $userID, int $organisationID)
    {
        $organisationMembers = $this->getByOrganisationID($organisationID);
        foreach ($organisationMembers AS $organisationMember) {
            if ($userID == $organisationMember->getMemberID()) {
                return true;
            }
        }

        return false;
    }

    public function userHasInvitationToOrganisation(User $user, Organisation $organisation)
    {
        $organisationMembers = $this->getByOrganisation($organisation);
        foreach ($organisationMembers AS $organisationMember) {
            if ($user->getId() == $organisationMember->getMemberID()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $where
     * @return \DSI\Entity\OrganisationMemberInvitation[]
     */
    private function getObjectsWhere($where)
    {
        /** @var OrganisationMemberInvitation[] $organisationMemberInvitations */
        $organisationMemberInvitations = [];
        $query = new SQL("SELECT organisationID, userID 
            FROM `organisation-member-invitations`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbOrganisationMember) {
            $organisationMember = new OrganisationMemberInvitation();
            $organisationMember->setOrganisation($this->organisationRepo->getById($dbOrganisationMember['organisationID']));
            $organisationMember->setMember($this->userRepo->getById($dbOrganisationMember['userID']));
            $organisationMemberInvitations[] = $organisationMember;
        }

        return $organisationMemberInvitations;
    }
}