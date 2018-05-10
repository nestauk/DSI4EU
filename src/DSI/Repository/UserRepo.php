<?php

namespace DSI\Repository;

use DSI;
use DSI\Entity\User;
use DSI\Service\SQL;

class UserRepo
{
    /** @var self */
    private static $objects = [];

    public function insert(User $user)
    {
        $insert = array();
        $insert[] = "`email` = '" . addslashes($user->getEmail()) . "'";
        $insert[] = "`showEmail` = '" . (bool)($user->canShowEmail()) . "'";
        $insert[] = "`fname` = '" . addslashes($user->getFirstName()) . "'";
        $insert[] = "`lname` = '" . addslashes($user->getLastName()) . "'";
        $insert[] = "`bio` = '" . addslashes($user->getBio()) . "'";
        $insert[] = "`cityName` = '" . addslashes($user->getCityName()) . "'";
        $insert[] = "`countryName` = '" . addslashes($user->getCountryName()) . "'";
        $insert[] = "`jobTitle` = '" . addslashes($user->getJobTitle()) . "'";
        $insert[] = "`company` = '" . addslashes($user->getCompany()) . "'";
        $insert[] = "`password` = '" . addslashes($user->getHashPassword()) . "'";
        $insert[] = "`facebookUID` = '" . addslashes($user->getFacebookUID()) . "'";
        $insert[] = "`googleUID` = '" . addslashes($user->getGoogleUID()) . "'";
        $insert[] = "`gitHubUID` = '" . addslashes($user->getGitHubUID()) . "'";
        $insert[] = "`twitterUID` = '" . addslashes($user->getTwitterUID()) . "'";

        $insert[] = "`profileURL` = '" . addslashes($user->getProfileURL()) . "'";
        $insert[] = "`profilePic` = '" . addslashes($user->getProfilePic()) . "'";

        $insert[] = "`isDisabled` = '" . (bool)($user->isDisabled()) . "'";
        $insert[] = "`role` = '" . addslashes($user->getRole()) . "'";

        $insert[] = "`" . User::EmailSubscription . "` = '" . $user->hasEmailSubscription() . "'";

        $query = new SQL("INSERT INTO `users` SET " . implode(', ', $insert));
        $query->query();

        $user->setId($query->insert_id());

        self::$objects[$user->getId()] = $user;

        OrganisationRepoInAPC::resetCache();
        ProjectRepoInAPC::resetCache();
    }

    public function save(User $user)
    {
        $query = new SQL("SELECT id FROM `users` WHERE id = '{$user->getId()}' LIMIT 1");
        $existingUser = $query->fetch();
        if (!$existingUser)
            throw new DSI\NotFound('userID: ' . $user->getId());

        $insert = array();
        $insert[] = "`email` = '" . addslashes($user->getEmail()) . "'";
        $insert[] = "`showEmail` = '" . (bool)($user->canShowEmail()) . "'";
        $insert[] = "`fname` = '" . addslashes($user->getFirstName()) . "'";
        $insert[] = "`lname` = '" . addslashes($user->getLastName()) . "'";
        $insert[] = "`bio` = '" . addslashes($user->getBio()) . "'";
        $insert[] = "`cityName` = '" . addslashes($user->getCityName()) . "'";
        $insert[] = "`countryName` = '" . addslashes($user->getCountryName()) . "'";
        $insert[] = "`jobTitle` = '" . addslashes($user->getJobTitle()) . "'";
        $insert[] = "`company` = '" . addslashes($user->getCompany()) . "'";
        $insert[] = "`password` = '" . addslashes($user->getHashPassword()) . "'";
        $insert[] = "`facebookUID` = '" . addslashes($user->getFacebookUID()) . "'";
        $insert[] = "`googleUID` = '" . addslashes($user->getGoogleUID()) . "'";
        $insert[] = "`gitHubUID` = '" . addslashes($user->getGitHubUID()) . "'";
        $insert[] = "`twitterUID` = '" . addslashes($user->getTwitterUID()) . "'";

        $insert[] = "`profileURL` = '" . addslashes($user->getProfileURL()) . "'";
        $insert[] = "`profilePic` = '" . addslashes($user->getProfilePic()) . "'";

        $insert[] = "`isDisabled` = '" . (bool)($user->isDisabled()) . "'";
        $insert[] = "`role` = '" . addslashes($user->getRole()) . "'";

        $insert[] = "`" . User::EmailSubscription . "` = '" . $user->hasEmailSubscription() . "'";

        $query = new SQL("UPDATE `users` SET " . implode(', ', $insert) . " WHERE `id` = '{$user->getId()}'");
        $query->query();

        self::$objects[$user->getId()] = $user;

        OrganisationRepoInAPC::resetCache();
        ProjectRepoInAPC::resetCache();
    }

    public function remove(User $user)
    {
        $query = new SQL("SELECT id FROM `users` WHERE id = '{$user->getId()}' LIMIT 1");
        $existingUser = $query->fetch();
        if (!$existingUser)
            throw new DSI\NotFound('userID: ' . $user->getId());

        $query = new SQL("DELETE FROM `users` WHERE `id` = '{$user->getId()}'");
        $query->query();

        unset(self::$objects[$user->getId()]);

        OrganisationRepoInAPC::resetCache();
        ProjectRepoInAPC::resetCache();
    }

    public function getById(int $id): User
    {
        if (isset(self::$objects[$id]))
            return self::$objects[$id];

        return $this->getUserWhere([
            "`id` = {$id}"
        ]);
    }

    /**
     * @param string $email
     * @return User
     */
    public function getByEmail(string $email): User
    {
        return $this->getUserWhere([
            "`email` = '" . addslashes($email) . "'"
        ]);
    }

    /**
     * @param string $name
     * @return User[]
     */
    public function searchByName(string $name)
    {
        return $this->getObjectsWhere([
            "(`fname` LIKE '%" . addslashes($name) . "%' OR 
            `lname` LIKE '%" . addslashes($name) . "%')"
        ]);
    }

    public function facebookUIDExists(string $facebookUID): bool
    {
        return $this->checkExistingUserWhere([
            "`facebookUID` = '" . addslashes($facebookUID) . "'"
        ]);
    }

    public function getByFacebookUId(string $facebookUID): User
    {
        return $this->getUserWhere([
            "`facebookUID` = '" . addslashes($facebookUID) . "'"
        ]);
    }

    public function googleUIDExists(string $googleUID): bool
    {
        return $this->checkExistingUserWhere([
            "`googleUID` = '" . addslashes($googleUID) . "'"
        ]);
    }

    public function getByGoogleUId(string $googleUID): User
    {
        return $this->getUserWhere([
            "`googleUID` = '" . addslashes($googleUID) . "'"
        ]);
    }

    public function gitHubUIDExists(string $gitHubUID): bool
    {
        return $this->checkExistingUserWhere([
            "`gitHubUID` = '" . addslashes($gitHubUID) . "'"
        ]);
    }

    public function getByGitHubUId(string $gitHubUID): User
    {
        return $this->getUserWhere([
            "`gitHubUID` = '" . addslashes($gitHubUID) . "'"
        ]);
    }

    public function twitterUIDExists(string $twitterUID): bool
    {
        return $this->checkExistingUserWhere([
            "`twitterUID` = '" . addslashes($twitterUID) . "'"
        ]);
    }

    public function getByTwitterUId(string $twitterUID): User
    {
        return $this->getUserWhere([
            "`twitterUID` = '" . addslashes($twitterUID) . "'"
        ]);
    }

    public function profileURLExists(string $profileURL): bool
    {
        return $this->checkExistingUserWhere([
            "`profileURL` = '" . addslashes($profileURL) . "'"
        ]);
    }

    public function getByProfileURL(string $userProfileURL): User
    {
        return $this->getUserWhere([
            "`profileURL` = '" . addslashes($userProfileURL) . "'"
        ]);
    }

    public function emailAddressExists(string $emailAddress, $excludeUserID = []): bool
    {
        $where = ["`email` = '" . addslashes($emailAddress) . "'"];
        foreach ($excludeUserID AS $userID) {
            $where[] = "`id` != '" . (int)$userID . "'";
        }

        return $this->checkExistingUserWhere($where);
    }

    /**
     * @param $user
     * @return User
     */
    private function buildUserFromData($user)
    {
        $userObj = new User();
        $userObj->setId($user['id']);
        if ($user['password'])
            $userObj->setHashPassword($user['password']);
        if ($user['email'])
            $userObj->setEmail($user['email']);
        if ($user['showEmail'])
            $userObj->setShowEmail($user['showEmail']);
        if ($user['fname'])
            $userObj->setFirstName($user['fname']);
        if ($user['lname'])
            $userObj->setLastName($user['lname']);
        if ($user['bio'])
            $userObj->setBio($user['bio']);
        $userObj->setCityName($user['cityName']);
        $userObj->setCountryName($user['countryName']);
        $userObj->setJobTitle($user['jobTitle']);
        $userObj->setCompany($user['company']);
        if ($user['facebookUID'])
            $userObj->setFacebookUID($user['facebookUID']);
        if ($user['googleUID'])
            $userObj->setGoogleUID($user['googleUID']);
        if ($user['gitHubUID'])
            $userObj->setGitHubUID($user['gitHubUID']);
        if ($user['twitterUID'])
            $userObj->setTwitterUID($user['twitterUID']);
        if ($user['profileURL'])
            $userObj->setProfileURL($user['profileURL']);
        if ($user['profilePic'])
            $userObj->setProfilePic($user['profilePic']);
        if ($user[User::EmailSubscription])
            $userObj->setEmailSubscription(User::EmailSubscription);

        $userObj->setDisabled($user['isDisabled']);
        $userObj->setRole($user['role']);

        self::$objects[$userObj->getId()] = $userObj;

        return $userObj;
    }

    /** @return User[] */
    public function getAll()
    {
        return $this->getObjectsWhere(["1"]);
    }

    /** @return User[] */
    public function getAllCommunityAdmins()
    {
        return $this->getObjectsWhere([
            "(`role` = 'community-admin' OR `role` = 'sys-admin')"
        ]);
    }

    /** @return User[] */
    public function getAllSubscribedForEmail()
    {
        return $this->getObjectsWhere([
            "(`".User::EmailSubscription."` = '1')"
        ]);
    }

    public function clearAll()
    {
        $query = new SQL("TRUNCATE TABLE `users`");
        $query->query();
        $this->clearCache();
    }

    public function clearCache()
    {
        self::$objects = [];
    }

    /**
     * @param $where
     * @return User
     * @throws DSI\NotFound
     */
    private function getUserWhere($where)
    {
        $objects = $this->getObjectsWhere($where);
        if (count($objects) < 1)
            throw new DSI\NotFound();

        return $objects[0];
    }

    /**
     * @param $where
     * @return bool
     */
    private function checkExistingUserWhere($where)
    {
        $query = new SQL("SELECT id FROM `users` WHERE " . implode(' AND ', $where) . " LIMIT 1");
        return ($query->fetch() ? true : false);
    }

    /**
     * @param $where
     * @return array
     */
    private function getObjectsWhere($where)
    {
        $users = [];
        $query = new SQL("SELECT 
            id, password, email, showEmail, fname, lname, bio
          , cityName, countryName
          , jobTitle, company
          , facebookUID, googleUID, gitHubUID, twitterUID
          , profileURL, profilePic
          , isDisabled, role, `" . User::EmailSubscription . "`
          FROM `users` WHERE " . implode(' AND ', $where) . "");
        foreach ($query->fetch_all() AS $dbUser) {
            $users[] = $this->buildUserFromData($dbUser);
        }
        return $users;
    }
}