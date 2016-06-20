<?php

namespace DSI\Entity;

class Project
{
    /** @var integer */
    private $id,
        $organisationsCount;

    /** @var User */
    private $owner;

    /** @var string */
    private $name,
        $description,
        $url,
        $status,
        $startDate,
        $endDate;

    /** @var CountryRegion */
    private $countryRegion;

    /** @var string */
    private $importID,
        $logo;

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
     * @return string
     */
    public function getUrl(): string
    {
        return (string)$this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return (string)$this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return string
     */
    public function getUnixStartDate()
    {
        return strtotime($this->startDate);
    }

    /**
     * @param string $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return string
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return string
     */
    public function getUnixEndDate()
    {
        return strtotime($this->endDate);
    }

    /**
     * @param string $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
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

    public function unsetCountryRegion()
    {
        $this->countryRegion = null;
    }

    /**
     * @return int
     */
    public function getOrganisationsCount()
    {
        return (int)$this->organisationsCount;
    }

    /**
     * @param int $organisationsCount
     */
    public function setOrganisationsCount($organisationsCount)
    {
        $this->organisationsCount = (int)$organisationsCount;
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

    /**
     * @return string
     */
    public function getLogo()
    {
        return (string)$this->logo;
    }

    public function getLogoOrDefault()
    {
        return ($this->logo != '' ? $this->logo : '0.svg');
    }

    /**
     * @param string $logo
     */
    public function setLogo($logo)
    {
        $this->logo = (string)$logo;
    }
}
