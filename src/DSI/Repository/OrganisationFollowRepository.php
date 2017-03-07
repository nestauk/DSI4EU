<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\Organisation;
use DSI\Entity\OrganisationFollow;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Service\SQL;

class OrganisationFollowRepository
{
    private $table = 'organisation-follow';
    /** @var OrganisationRepository */
    private $organisationRepo;

    /** @var UserRepository */
    private $userRepo;

    public function __construct()
    {
        $this->organisationRepo = new OrganisationRepositoryInAPC();
        $this->userRepo = new UserRepository();
    }

    public function add(OrganisationFollow $organisationFollow)
    {
        $query = new SQL("SELECT organisationID 
            FROM `{$this->table}`
            WHERE `organisationID` = '{$organisationFollow->getOrganisationID()}'
              AND `userID` = '{$organisationFollow->getUserID()}'
            LIMIT 1
        ");
        if ($query->fetch('organisationID') > 0)
            throw new DuplicateEntry("organisationID: {$organisationFollow->getOrganisationID()} / userID: {$organisationFollow->getUserID()}");

        $insert = array();
        $insert[] = "`organisationID` = '" . (int)($organisationFollow->getOrganisationID()) . "'";
        $insert[] = "`userID` = '" . (int)($organisationFollow->getUserID()) . "'";

        $query = new SQL("INSERT INTO `{$this->table}` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(OrganisationFollow $organisationFollow)
    {
        $query = new SQL("SELECT organisationID 
            FROM `{$this->table}`
            WHERE `organisationID` = '{$organisationFollow->getOrganisationID()}'
              AND `userID` = '{$organisationFollow->getUserID()}'
            LIMIT 1
        ");
        if (!$query->fetch('organisationID'))
            throw new NotFound("organisationID: {$organisationFollow->getOrganisationID()} / userID: {$organisationFollow->getUserID()}");

        $insert = array();
        $insert[] = "`organisationID` = '" . (int)($organisationFollow->getOrganisationID()) . "'";
        $insert[] = "`userID` = '" . (int)($organisationFollow->getUserID()) . "'";

        $query = new SQL("DELETE FROM `{$this->table}` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $organisationID
     * @return \DSI\Entity\OrganisationFollow[]
     */
    public function getByOrganisationID(int $organisationID)
    {
        return $this->getObjectsWhere([
            "`organisationID` = '{$organisationID}'"
        ]);
    }

    /**
     * @param Organisation $organisation
     * @return \int[]
     */
    public function getUserIDsForOrganisation(Organisation $organisation)
    {
        $where = [
            "`organisationID` = '{$organisation->getId()}'"
        ];

        /** @var int[] $userIDs */
        $userIDs = [];
        $query = new SQL("SELECT userID 
            FROM `{$this->table}`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY userID
        ");
        foreach ($query->fetch_all() AS $dbOrganisationFollows) {
            $userIDs[] = $dbOrganisationFollows['userID'];
        }

        return $userIDs;
    }

    /**
     * @param User $user
     * @return \DSI\Entity\OrganisationFollow[]
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
    public function getOrganisationIDsForUser(User $user)
    {
        $where = [
            "`userID` = '{$user->getId()}'"
        ];

        /** @var int[] $organisationIDs */
        $organisationIDs = [];
        $query = new SQL("SELECT organisationID 
            FROM `{$this->table}`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY organisationID
        ");
        foreach ($query->fetch_all() AS $dbOrganisationFollow) {
            $organisationIDs[] = $dbOrganisationFollow['organisationID'];
        }

        return $organisationIDs;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `{$this->table}`");
        $query->query();
    }

    /**
     * @param $where
     * @return \DSI\Entity\OrganisationFollow[]
     */
    private function getObjectsWhere($where)
    {
        /** @var OrganisationFollow[] $organisationFollows */
        $organisationFollows = [];
        $query = new SQL("SELECT organisationID, userID 
            FROM `{$this->table}`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbOrganisationFollow) {
            $organisationFollow = new OrganisationFollow();
            $organisationFollow->setOrganisation($this->organisationRepo->getById($dbOrganisationFollow['organisationID']));
            $organisationFollow->setUser($this->userRepo->getById($dbOrganisationFollow['userID']));
            $organisationFollows[] = $organisationFollow;
        }

        return $organisationFollows;
    }

    /**
     * @param User $user
     * @param Organisation $organisation
     * @return bool
     */
    public function userFollowsOrganisation(User $user, Organisation $organisation)
    {
        $organisationFollows = $this->getByOrganisationID($organisation->getId());
        foreach ($organisationFollows AS $organisationFollow) {
            if ($user->getId() == $organisationFollow->getUserID()) {
                return true;
            }
        }

        return false;
    }
}