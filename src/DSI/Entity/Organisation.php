<?php

namespace DSI\Entity;

class Organisation
{
    /** @var integer */
    private $id,
        $partnersCount;

    /** @var User */
    private $owner;

    /** @var string */
    private $name,
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
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address)
    {
        $this->address = $address;
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
        return $this->importID;
    }

    /**
     * @param string $importID
     */
    public function setImportID($importID)
    {
        $this->importID = $importID;
    }
}