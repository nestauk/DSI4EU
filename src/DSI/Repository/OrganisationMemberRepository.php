<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\Organisation;
use DSI\Entity\OrganisationMember;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Service\SQL;

class OrganisationMemberRepository
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

    public function insert(OrganisationMember $organisationMember)
    {
        $query = new SQL("SELECT organisationID 
            FROM `organisation-members`
            WHERE `organisationID` = '{$organisationMember->getOrganisationID()}'
            AND `userID` = '{$organisationMember->getMemberID()}'
            LIMIT 1
        ");
        if ($query->fetch('organisationID') > 0)
            throw new DuplicateEntry("organisationID: {$organisationMember->getOrganisationID()} / userID: {$organisationMember->getMemberID()}");

        $insert = array();
        $insert[] = "`organisationID` = " . (int)($organisationMember->getOrganisationID()) . "";
        $insert[] = "`userID` = " . (int)($organisationMember->getMemberID()) . "";
        $insert[] = "`isAdmin` = " . (int)($organisationMember->isAdmin()) . "";

        $query = new SQL("INSERT INTO `organisation-members` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(OrganisationMember $organisationMember)
    {
        $query = new SQL("SELECT organisationID 
            FROM `organisation-members`
            WHERE `organisationID` = '{$organisationMember->getOrganisationID()}'
            AND `userID` = '{$organisationMember->getMemberID()}'
            LIMIT 1
        ");
        if (!$query->fetch('organisationID'))
            throw new NotFound("organisationID: {$organisationMember->getOrganisationID()} / userID: {$organisationMember->getMemberID()}");

        $insert = array();
        $insert[] = "`organisationID` = " . (int)($organisationMember->getOrganisationID()) . "";
        $insert[] = "`userID` = " . (int)($organisationMember->getMemberID()) . "";

        $query = new SQL("DELETE FROM `organisation-members` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $organisationID
     * @return \DSI\Entity\OrganisationMember[]
     */
    public function getByOrganisationID(int $organisationID)
    {
        return $this->getObjectsWhere([
            "`organisationID` = '{$organisationID}'"
        ]);
    }

    /**
     * @param int $memberID
     * @param int $organisationID
     * @return OrganisationMember
     */
    public function getByMemberIdAndOrganisationId(int $memberID, int $organisationID)
    {
        return $this->getObjectWhere([
            "`userID` = '{$memberID}'",
            "`organisationID` = '{$organisationID}'",
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
            FROM `organisation-members`
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
     * @return \DSI\Entity\OrganisationMember[]
     */
    public function getByMemberID(int $userID)
    {
        return $this->getObjectsWhere([
            "`userID` = '{$userID}'"
        ]);
    }

    public function getByAdmin(User $user)
    {
        return $this->getObjectsWhere([
            "`isAdmin` = 1",
            "`userID` = ".$user->getId()."",
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
            FROM `organisation-members`
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
        $query = new SQL("TRUNCATE TABLE `organisation-members`");
        $query->query();
    }

    /**
     * @param $where
     * @return OrganisationMember
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
     * @return \DSI\Entity\OrganisationMember[]
     */
    private function getObjectsWhere($where)
    {
        /** @var OrganisationMember[] $organisationMembers */
        $organisationMembers = [];
        $query = new SQL("SELECT organisationID, userID, isAdmin
            FROM `organisation-members`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbOrganisationMember) {
            $organisationMember = new OrganisationMember();
            $organisationMember->setOrganisation($this->organisationRepo->getById($dbOrganisationMember['organisationID']));
            $organisationMember->setMember($this->userRepo->getById($dbOrganisationMember['userID']));
            $organisationMember->setIsAdmin((bool)$dbOrganisationMember['isAdmin']);
            $organisationMembers[] = $organisationMember;
        }

        return $organisationMembers;
    }

    public function organisationHasMember(int $organisationID, int $userID)
    {
        $organisationMembers = $this->getByOrganisationID($organisationID);
        foreach ($organisationMembers AS $organisationMember) {
            if ($userID == $organisationMember->getMemberID()) {
                return true;
            }
        }

        return false;
    }
}