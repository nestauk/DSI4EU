<?php

namespace DSI\Entity;

class AppRegistration
{
    /** @var integer */
    private $id;

    /** @var User */
    private $loggedInUser,
        $registeredUser;

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        if ($id <= 0)
            throw new \InvalidArgumentException('id: ' . $id);

        $this->id = $id;
    }

    /**
     * @return User|null
     */
    public function getLoggedInUser()
    {
        return $this->loggedInUser;
    }

    /**
     * @return int
     */
    public function getLoggedInUserID()
    {
        return $this->loggedInUser ? $this->loggedInUser->getId() : 0;
    }

    /**
     * @param User $loggedInUser
     */
    public function setLoggedInUser(User $loggedInUser)
    {
        $this->loggedInUser = $loggedInUser;
    }

    /**
     * @return User|null
     */
    public function getRegisteredUser()
    {
        return $this->registeredUser;
    }

    /**
     * @return int
     */
    public function getRegisteredUserID()
    {
        return $this->registeredUser ? $this->registeredUser->getId() : 0;
    }

    /**
     * @param User $registeredUser
     */
    public function setRegisteredUser(User $registeredUser)
    {
        $this->registeredUser = $registeredUser;
    }
}