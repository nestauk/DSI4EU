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
     * @param string $link
     */
    public function setLink(string $link)
    {
        $this->link = $link;
    }
}