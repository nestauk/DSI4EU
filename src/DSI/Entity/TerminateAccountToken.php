<?php

namespace DSI\Entity;

class TerminateAccountToken
{
    /** @var string */
    private $token,
        $expire;

    /** @var User */
    private $user;

    /**
     * @return string
     */
    public function getToken(): string
    {
        return (string)$this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getExpire(): string
    {
        return (string)$this->expire;
    }

    /**
     * @param string $expire
     */
    public function setExpire(string $expire)
    {
        $this->expire = $expire;
    }

    /**
     * @return User
     */
    public function getUser(): User
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

    public function isExpired(): bool
    {
        return strtotime($this->expire) < time();
    }
}