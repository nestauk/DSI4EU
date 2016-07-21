<?php

namespace DSI\Entity;

class OrganisationLink
{
    /** @var Organisation */
    private $organisation;

    /** @var string */
    private $link;

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

    /**
     * @return string
     */
    public function getLink(): string
    {
        return (string)$this->link;
    }

    /**
     * @return int
     */
    public function getLinkService(): int
    {
        if (preg_match("<^(https?:\/\/)?((w{3}\.)?)facebook\.com\/>", $this->link))
            return OrganisationLink_Service::Facebook;
        if (preg_match("<^(https?:\/\/)?((w{3}\.)?)twitter\.com\/>", $this->link))
            return OrganisationLink_Service::Twitter;
        if (preg_match("<^(https?:\/\/)?plus\.google\.com\/>", $this->link))
            return OrganisationLink_Service::GooglePlus;
        if (preg_match("<^(https?:\/\/)?((w{3}\.)?)github\.com\/>", $this->link))
            return OrganisationLink_Service::GitHub;

        return OrganisationLink_Service::Other;
    }

    /**
     * @param string $link
     */
    public function setLink(string $link)
    {
        $this->link = $link;
    }
}

class OrganisationLink_Service
{
    const Facebook = 1;
    const Twitter = 2;
    const GooglePlus = 3;
    const GitHub = 4;

    const Other = 5;
}