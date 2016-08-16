<?php

namespace DSI\Entity;

class OrganisationMember
{
    /** @var Organisation */
    private $organisation;

    /** @var User */
    private $member;

    /** @var bool */
    private $isAdmin;

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

    public function getMember(): User
    {
        return $this->member;
    }

    /**
     * @return int
     */
    public function getMemberID(): int
    {
        return $this->member->getId();
    }

    public function setMember(User $member)
    {
        $this->member = $member;
    }

    /**
     * @return boolean
     */
    public function isAdmin(): bool
    {
        return (bool)$this->isAdmin;
    }

    /**
     * @param boolean $isAdmin
     */
    public function setIsAdmin(bool $isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }
}