<?php

namespace DSI\Entity;

class OrganisationFollow
{
    /** @var Organisation */
    private $organisation;

    /** @var User */
    private $user;

    /**
     * @return Organisation
     */
    public function getOrganisation(): Organisation
    {
        return $this->organisation;
    }

    /**
     * @return int
     */
    public function getOrganisationID(): int
    {
        return $this->organisation->getId();
    }

    /**
     * @param Organisation $organisation
     */
    public function setOrganisation(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }

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

    public function setUser(User $user)
    {
        $this->user = $user;
    }
}