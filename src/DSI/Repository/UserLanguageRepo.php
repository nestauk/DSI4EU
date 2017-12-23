<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\User;
use DSI\Entity\UserLanguage;
use DSI\NotFound;
use DSI\Service\SQL;

class UserLanguageRepo
{
    /** @var UserRepo */
    private $userRepo;

    /** @var LanguageRepo */
    private $languageRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepo();
        $this->languageRepo = new LanguageRepo();
    }

    public function add(UserLanguage $userLanguage)
    {
        $query = new SQL("SELECT userID 
            FROM `user-languages`
            WHERE `userID` = '{$userLanguage->getUserID()}'
            AND `langID` = '{$userLanguage->getLanguageID()}'
            LIMIT 1
        ");
        if ($query->fetch('userID') > 0)
            throw new DuplicateEntry("userID: {$userLanguage->getUserID()} / languageID: {$userLanguage->getLanguageID()}");

        $insert = array();
        $insert[] = "`userID` = '" . (int)($userLanguage->getUserID()) . "'";
        $insert[] = "`langID` = '" . (int)($userLanguage->getLanguageID()) . "'";

        $query = new SQL("INSERT INTO `user-languages` SET " . implode(', ', $insert) . "");
        // $query->pr();
        $query->query();
    }

    public function remove(UserLanguage $userLanguage)
    {
        $query = new SQL("SELECT userID 
            FROM `user-languages`
            WHERE `userID` = '{$userLanguage->getUserID()}'
            AND `langID` = '{$userLanguage->getLanguageID()}'
            LIMIT 1
        ");
        if (!$query->fetch('userID'))
            throw new NotFound("userID: {$userLanguage->getUserID()} / languageID: {$userLanguage->getLanguageID()}");

        $insert = array();
        $insert[] = "`userID` = '" . (int)($userLanguage->getUserID()) . "'";
        $insert[] = "`langID` = '" . (int)($userLanguage->getLanguageID()) . "'";

        $query = new SQL("DELETE FROM `user-languages` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param int $userID
     * @return \DSI\Entity\UserLanguage[]
     */
    public function getByUserID(int $userID)
    {
        return $this->getUserLanguagesWhere([
            "`userID` = '{$userID}'"
        ]);
    }

    /**
     * @param int $userID
     * @return \int[]
     */
    public function getLanguageIDsForUser(int $userID)
    {
        $where = [
            "`userID` = '{$userID}'"
        ];

        /** @var int[] $languageIDs */
        $languageIDs = [];
        $query = new SQL("SELECT langID 
            FROM `user-languages`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY langID
        ");
        foreach ($query->fetch_all() AS $dbUserLanguage) {
            $languageIDs[] = $dbUserLanguage['langID'];
        }

        return $languageIDs;
    }

    /**
     * @param int $languageID
     * @return \DSI\Entity\UserLanguage[]
     */
    public function getByLanguageID(int $languageID)
    {
        return $this->getUserLanguagesWhere([
            "`langID` = '{$languageID}'"
        ]);
    }

    /**
     * @param int $languageID
     * @return \int[]
     */
    public function getUserIDsForLanguage(int $languageID)
    {
        $where = [
            "`langID` = '{$languageID}'"
        ];

        /** @var int[] $userIDs */
        $userIDs = [];
        $query = new SQL("SELECT userID 
            FROM `user-languages`
            WHERE " . implode(' AND ', $where) . "
            ORDER BY userID
        ");
        foreach ($query->fetch_all() AS $dbUserLanguage) {
            $userIDs[] = $dbUserLanguage['userID'];
        }

        return $userIDs;
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `user-languages`");
        $query->query();
    }

    /**
     * @param $where
     * @return \DSI\Entity\UserLanguage[]
     */
    private function getUserLanguagesWhere($where)
    {
        /** @var UserLanguage[] $userLanguages */
        $userLanguages = [];
        $query = new SQL("SELECT userID, langID 
            FROM `user-languages`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbUserLanguage) {
            $userLanguage = new UserLanguage();
            $userLanguage->setUser($this->userRepo->getById($dbUserLanguage['userID']));
            $userLanguage->setLanguage($this->languageRepo->getById($dbUserLanguage['langID']));
            $userLanguages[] = $userLanguage;
        }

        return $userLanguages;
    }

    public function userHasLanguageName(int $userID, string $languageName)
    {
        $userLanguages = $this->getByUserID($userID);
        foreach ($userLanguages AS $userLanguage) {
            if ($languageName == $userLanguage->getLanguage()->getName()) {
                return true;
            }
        }

        return false;
    }

    public function getLanguagesNameByUserID(int $userID)
    {
        $query = new SQL("SELECT language 
            FROM `languages` 
            LEFT JOIN `user-languages` ON `languages`.`id` = `user-languages`.`langID`
            WHERE `user-languages`.`userID` = '{$userID}'
            ORDER BY `languages`.`language`
        ");
        return $query->fetch_all('language');
    }
}