<?php

namespace DSI\Entity;

class Organisation
{
    public $extraData;

    /** @var integer */
    private $id;

    /** @var User */
    private $owner;

    /** @var string */
    private $name,
        $url,
        $shortDescription,
        $description,
        $address;

    /** @var CountryRegion */
    private $countryRegion;

    /** @var OrganisationType */
    private $organisationType;

    /** @var OrganisationSize */
    private $organisationSize;

    /** @var string */
    private $importID;

    /** @var int */
    private $partnersCount,
        $projectsCount;

    /** @var string */
    private $startDate,
        $logo,
        $headerImage;

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
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return (string)$this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     */
    public function setOwner(User $owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->countryRegion ? $this->countryRegion->getCountry() : null;
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryRegion ? $this->countryRegion->getCountry()->getName() : '';
    }

    /**
     * @return int
     */
    public function getCountryID()
    {
        return $this->getCountry() ? $this->getCountry()->getId() : 0;
    }

    /**
     * @return CountryRegion
     */
    public function getCountryRegion()
    {
        return $this->countryRegion;
    }

    /**
     * @return string
     */
    public function getRegionName()
    {
        return $this->countryRegion ? $this->countryRegion->getName() : '';
    }

    /**
     * @return int
     */
    public function getCountryRegionID()
    {
        return $this->countryRegion ? $this->countryRegion->getId() : 0;
    }

    /**
     * @param CountryRegion $countryRegion
     */
    public function setCountryRegion(CountryRegion $countryRegion)
    {
        $this->countryRegion = $countryRegion;
    }

    /**
     * @return OrganisationType
     */
    public function getOrganisationType()
    {
        return $this->organisationType;
    }

    /**
     * @return int
     */
    public function getOrganisationTypeId()
    {
        return $this->organisationType ? $this->organisationType->getId() : 0;
    }

    /**
     * @param OrganisationType $organisationType
     */
    public function setOrganisationType(OrganisationType $organisationType)
    {
        $this->organisationType = $organisationType;
    }

    /**
     * @return OrganisationSize
     */
    public function getOrganisationSize()
    {
        return $this->organisationSize;
    }

    /**
     * @return int
     */
    public function getOrganisationSizeId()
    {
        return $this->organisationSize ? $this->organisationSize->getId() : 0;
    }

    /**
     * @param OrganisationSize $organisationSize
     */
    public function setOrganisationSize(OrganisationSize $organisationSize)
    {
        $this->organisationSize = $organisationSize;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return (string)$this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = (string)$address;
    }

    /**
     * @return int
     */
    public function getPartnersCount()
    {
        return (int)$this->partnersCount;
    }

    /**
     * @param int $partnersCount
     */
    public function setPartnersCount($partnersCount)
    {
        $this->partnersCount = (int)$partnersCount;
    }

    /**
     * @return string
     */
    public function getImportID()
    {
        return (string)$this->importID;
    }

    /**
     * @param string $importID
     */
    public function setImportID($importID)
    {
        $this->importID = (string)$importID;
    }

    /**
     * @return int
     */
    public function getProjectsCount()
    {
        return (int)$this->projectsCount;
    }

    /**
     * @param int $projectsCount
     */
    public function setProjectsCount($projectsCount)
    {
        $this->projectsCount = (int)$projectsCount;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return (string)$this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = (string)$url;
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return (string)$this->shortDescription;
    }

    /**
     * @param string $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = (string)$shortDescription;
    }

    /**
     * @return string
     */
    public function getStartDate(): string
    {
        return (string)$this->startDate;
    }

    /**
     * @param string $startDate
     */
    public function setStartDate($startDate)
    {
        if ($startDate == '0000-00-00')
            $startDate = '';
        $this->startDate = (string)$startDate;
    }

    /**
     * @return string
     */
    public function getLogo(): string
    {
        return (string)$this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo($logo)
    {
        $this->logo = (string)$logo;
    }

    /**
     * @return string
     */
    public function getHeaderImage(): string
    {
        return (string)$this->headerImage;
    }

    /**
     * @param string $headerImage
     */
    public function setHeaderImage($headerImage)
    {
        $this->headerImage = (string)$headerImage;
    }
}