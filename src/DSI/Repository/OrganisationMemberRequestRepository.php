<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\Organisation;
use DSI\Entity\OrganisationMember;
use DSI\Entity\OrganisationMemberRequest;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Service\SQL;

class OrganisationMemberRequestRepository
{
    /** @var OrganisationRepository */
    private $organisationRepo;

    /** @var UserRepository */
    private $userRepo;

    public function __construct()
    {
        $this->organisationRepo = new OrganisationRepository();
        $this->userRepo = new UserRepository();
    }

    public function add(OrganisationMemberRequest $organisationMemberRequest)
    {
        $query = new SQL("SELECT organisationID 
            FROM `organisation-member-requests`
            WHERE `organisationID` = '{$organisationMemberRequest->getOrganisationID()}'
            AND `userID` = '{$organisationMemberRequest->getMemberID()}'
            LIMIT 1
        ");
        if ($query->fetch('organisationID') > 0)
            throw new DuplicateEntry("organisationID: {$organisationMemberRequest->getOrganisationID()} / userID: {$organisationMemberRequest->getMemberID()}");

        $insert = array();
        $insert[] = "`organisationID` = '" . (int)($organisationMemberRequest->getOrganisationID()) . "'";
        $insert[] = "`userID` = '" . (int)($organisationMemberRequest->getMemberID()) . "'";

        $query = new SQL("INSERT INTO `organisation-member-requests` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(OrganisationMemberRequest $organisationMemberRequest)
    {
        $query = new SQL("SELECT organisationID 
            FROM `organisation-member-requests`
            WHERE `organisationID` = '{$organisationMemberRequest->getOrganisationID()}'
            AND `userID` = '{$organisationMemberRequest->getMemberID()}'
            LIMIT 1
        ");
        if (!$query->fetch('organisationID'))
            throw new NotFound("organisationID: {$organisationMemberRequest->getOrganisationID()} / userID: {$organisationMemberRequest->getMemberID()}");

        $insert = array();
        $insert[] = "`organisationID` = '" . (int)($organisationMemberRequest->getOrganisationID()) . "'";
        $insert[] = "`userID` = '" . (int)($organisationMemberRequest->getMemberID()) . "'";

        $query = new SQL("DELETE FROM `organisation-member-requests` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $organisationID
     * @return \DSI\Entity\OrganisationMemberRequest[]
     */
    public function getByOrganisationID(int $organisationID)
    {
        return $this->getOrganisationMemberRequestsWhere([
            "`organisationID` = '{$organisationID}'"
        ]);
    }

    /**
     * @param int[] $organisationIDs
     * @return \DSI\Entity\OrganisationMemberRequest[]
     */
    public function getByOrganisationIDs($organisationIDs)
    {
        if(count($organisationIDs) < 1)
            return [];

        return $this->getOrganisationMemberRequestsWhere([
            "`organisationID` IN (".implode(', ', $organisationIDs).")"
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
            FROM `organisation-member-requests`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY userID
        ");
        foreach ($query->fetch_all() AS $dbOrganisationMembers) {
            $memberIDs[] = $dbOrganisationMembers['userID'];
        }

        return $memberIDs;
    }

    /**
     * @param Organisation $organisation
     * @return User[]
     */
    public function getMembersForOrganisation(Organisation $organisation)
    {
        $members = [];
        $memberIDs = $this->getMemberIDsForOrganisation($organisation->getId());

        foreach ($memberIDs AS $memberID)
            $members[] = $this->userRepo->getById($memberID);

        return $members;
    }

    /**
     * @param int $userID
     * @return \DSI\Entity\OrganisationMemberRequest[]
     */
    public function getByMemberID(int $userID)
    {
        return $this->getOrganisationMemberRequestsWhere([
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
            FROM `organisation-member-requests`
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
        $query = new SQL("TRUNCATE TABLE `organisation-member-requests`");
        $query->query();
    }

    public function organisationHasRequestFromMember(int $organisationID, int $userID)
    {
        $organisationMembers = $this->getByOrganisationID($organisationID);
        foreach ($organisationMembers AS $organisationMember) {
            if ($userID == $organisationMember->getMemberID()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $where
     * @return \DSI\Entity\OrganisationMemberRequest[]
     */
    private function getOrganisationMemberRequestsWhere($where)
    {
        /** @var OrganisationMemberRequest[] $organisationMemberRequests */
        $organisationMemberRequests = [];
        $query = new SQL("SELECT organisationID, userID 
            FROM `organisation-member-requests`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbOrganisationMember) {
            $organisationMember = new OrganisationMemberRequest();
            $organisationMember->setOrganisation($this->organisationRepo->getById($dbOrganisationMember['organisationID']));
            $organisationMember->setMember($this->userRepo->getById($dbOrganisationMember['userID']));
            $organisationMemberRequests[] = $organisationMember;
        }

        return $organisationMemberRequests;
    }
}