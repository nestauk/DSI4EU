<?php

namespace DSI\Entity;

class OrganisationTag
{
    /** @var Organisation */
    private $organisation;

    /** @var TagForOrganisations */
    private $tag;

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

    public function getTag(): TagForOrganisations
    {
        return $this->tag;
    }

    /**
     * @return int
     */
    public function getTagID(): int
    {
        return $this->tag->getId();
    }

    public function setTag(TagForOrganisations $tag)
    {
        $this->tag = $tag;
    }
}