<?php

namespace DSI\Entity;

class PasswordRecovery
{
    /** @var integer */
    private $id;

    /** @var User */
    private $user;

    /** @var string */
    private $code,
        $expires;

    /** @var bool */
    private $isUsed,
        $isExpired;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        if ($id <= 0)
            throw new \InvalidArgumentException('id: ' . $id);

        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param string $expires
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
    }

    /**
     * @return boolean
     */
    public function isUsed()
    {
        return (bool)$this->isUsed;
    }

    /**
     * @param boolean $isUsed
     */
    public function setIsUsed(bool $isUsed)
    {
        $this->isUsed = $isUsed;
    }

    /**
     * @return boolean
     */
    public function isExpired()
    {
        return (bool)$this->isExpired;
    }

    /**
     * @param boolean $isExpired
     */
    public function setIsExpired(bool $isExpired)
    {
        $this->isExpired = $isExpired;
    }
}
