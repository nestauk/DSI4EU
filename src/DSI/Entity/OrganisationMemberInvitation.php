<?php

namespace DSI\Entity;

class OrganisationMemberInvitation
{
    /** @var Organisation */
    private $organisation;

    /** @var User */
    private $member;

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
}