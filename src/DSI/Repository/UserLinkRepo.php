<?php

namespace DSI\Repository;

use DSI\DuplicateEntry;
use DSI\Entity\User;
use DSI\Entity\UserLink;
use DSI\NotFound;
use DSI\Service\SQL;

class UserLinkRepo
{
    /** @var UserRepo */
    private $userRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepo();
    }

    public function add(UserLink $userLink)
    {
        $query = new SQL("SELECT userID 
            FROM `user-links`
            WHERE `userID` = '{$userLink->getUserID()}'
            AND `link` = '{$userLink->getLink()}'
            LIMIT 1
        ");
        if ($query->fetch('userID') > 0)
            throw new DuplicateEntry("userID: {$userLink->getUserID()} / link: {$userLink->getLink()}");

        $insert = array();
        $insert[] = "`userID` = '" . (int)($userLink->getUserID()) . "'";
        $insert[] = "`link` = '" . ($userLink->getLink()) . "'";

        $query = new SQL("INSERT INTO `user-links` SET " . implode(', ', $insert) . "");
        $query->query();
    }

    public function remove(UserLink $userLink)
    {
        $query = new SQL("SELECT userID 
            FROM `user-links`
            WHERE `userID` = '{$userLink->getUserID()}'
            AND `link` = '{$userLink->getLink()}'
            LIMIT 1
        ");
        if (!$query->fetch('userID'))
            throw new NotFound("userID: {$userLink->getUserID()} / link: {$userLink->getLink()}");

        $insert = array();
        $insert[] = "`userID` = '" . (int)($userLink->getUserID()) . "'";
        $insert[] = "`link` = '" . ($userLink->getLink()) . "'";

        $query = new SQL("DELETE FROM `user-links` WHERE " . implode(' AND ', $insert) . "");
        $query->query();
    }

    /**
     * @param User $user
     * @return \DSI\Entity\UserLink[]
     */
    public function getByUser(User $user)
    {
        return $this->getUserLinksWhere([
            "`userID` = '{$user->getId()}'"
        ]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `user-links`");
        $query->query();
    }

    /**
     * @param $where
     * @return \DSI\Entity\UserLink[]
     */
    private function getUserLinksWhere($where)
    {
        /** @var UserLink[] $userLinks */
        $userLinks = [];
        $query = new SQL("SELECT userID, link 
            FROM `user-links`
            WHERE " . implode(' AND ', $where) . "
        ");
        foreach ($query->fetch_all() AS $dbUserLink) {
            $userLink = new UserLink();
            $userLink->setUser($this->userRepo->getById($dbUserLink['userID']));
            $userLink->setLink($dbUserLink['link']);
            $userLinks[] = $userLink;
        }

        return $userLinks;
    }

    /**
     * @param int $userID
     * @return string[]
     */
    public function getLinksByUserID(int $userID)
    {
        $query = new SQL("SELECT link 
            FROM `user-links` 
            WHERE `userID` = '{$userID}'
              AND `link` != ''
            ORDER BY `link`
        ");
        return $query->fetch_all('link');
    }

    /**
     * @param int $userID
     * @param string $link
     * @return bool
     */
    public function userHasLink($userID, $link){
        return in_array($link, $this->getLinksByUserID($userID));
    }
}