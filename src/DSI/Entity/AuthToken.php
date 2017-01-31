<?php

namespace DSI\Entity;

class AuthToken
{
    /** @var string */
    private $selector,
        $token,
        $ip,
        $created,
        $lastUse;

    /** @var User */
    private $user;

    /**
     * @return string
     */
    public function getSelector(): string
    {
        return (string)$this->selector;
    }

    /**
     * @param string $selector
     */
    public function setSelector($selector)
    {
        $this->selector = (string)$selector;
    }

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
    public function setToken($token)
    {
        $this->token = (string)$token;
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return (string)$this->created;
    }

    /**
     * @param string $created
     */
    public function setCreated($created)
    {
        $this->created = (string)$created;
    }

    /**
     * @return string
     */
    public function getLastUse(): string
    {
        return (string)$this->lastUse;
    }

    /**
     * @param string $lastUse
     */
    public function setLastUse($lastUse)
    {
        $this->lastUse = (string)$lastUse;
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
    public function getIp(): string
    {
        return (string)$this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = (string)$ip;
    }
}