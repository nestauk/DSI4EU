<?php

namespace DSI\Entity;

class UserLink
{
    /** @var User */
    private $user;

    /** @var string */
    private $link;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getUserID(): int
    {
        return $this->user->getId();
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return (string)$this->link;
    }

    /**
     * @return int
     */
    public function getLinkService(): int
    {
        if (preg_match("<^(https?:\/\/)?((w{3}\.)?)facebook\.com\/>", $this->link))
            return UserLink_Service::Facebook;
        if (preg_match("<^(https?:\/\/)?((w{3}\.)?)twitter\.com\/>", $this->link))
            return UserLink_Service::Twitter;
        if (preg_match("<^(https?:\/\/)?plus\.google\.com\/>", $this->link))
            return UserLink_Service::GooglePlus;
        if (preg_match("<^(https?:\/\/)?((w{3}\.)?)github\.com\/>", $this->link))
            return UserLink_Service::GitHub;

        return UserLink_Service::Other;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link)
    {
        $this->link = $link;
    }
}

class UserLink_Service
{
    const Facebook = 1;
    const Twitter = 2;
    const GooglePlus = 3;
    const GitHub = 4;

    const Other = 5;
}