<?php

namespace DSI\Entity;

class UserLanguage
{
    /** @var User */
    private $user;

    /** @var Language */
    private $language;

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
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @return int
     */
    public function getLanguageID(): int
    {
        return $this->language->getId();
    }

    /**
     * @param Language $language
     */
    public function setLanguage(Language $language)
    {
        $this->language = $language;
    }
}