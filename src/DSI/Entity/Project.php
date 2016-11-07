<?php

namespace DSI\Entity;

class Project
{
    const DEFAULT_HEADER_IMAGE = '0.png';
    const DEFAULT_LOGO = '0.png';
    const DEFAULT_LOGO_SILVER = '0-silver.png';
    /** @var integer */
    private $id,
        $organisationsCount;

    /** @var User */
    private $owner;

    /** @var string */
    private $name,
        $shortDescription,
        $description,
        $url,
        $status,
        $startDate,
        $endDate;

    /** @var CountryRegion */
    private $countryRegion;

    /** @var string */
    private $importID,
        $logo,
        $headerImage,
        $socialImpact;

    /** @var bool */
    private $isPublished;

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
    public function getShortDescription()
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
     * @return int
     */
    public function getOwnerID(): int
    {
        return $this->owner ? $this->owner->getId() : 0;
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
     * @return string
     */
    public function getExternalURL(): string
    {
        if ($this->url) {
            if (substr($this->url, 0, 4) != 'http')
                return 'http://' . $this->url;
        }

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
     * @param string $format
     * @return string
     */
    public function getStartDate($format = null)
    {
        if ($format !== null) {
            return date($format, strtotime($this->startDate));
        }

        return $this->startDate;
    }

    public function startDateHasPassed()
    {
        return strtotime($this->startDate) < time();
    }

    public function startDateIsInFuture()
    {
        return strtotime($this->startDate) > time();
    }

    /**
     * @param string $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @param string $format
     * @return string
     */
    public function getEndDate($format = null)
    {
        if ($format !== null) {
            return date($format, strtotime($this->endDate));
        }

        return $this->endDate;
    }

    public function endDateHasPassed()
    {
        return strtotime($this->endDate) < time();
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
    public function getRegion()
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
    public function getRegionID()
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
        return (string)($this->logo != '' ? $this->logo : self::DEFAULT_LOGO);
    }

    public function getLogoOrDefaultSilver()
    {
        return (string)($this->logo != '' ? $this->logo : self::DEFAULT_LOGO_SILVER);
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
    public function getHeaderImage()
    {
        return (string)$this->headerImage;
    }

    /**
     * @return string
     */
    public function getHeaderImageOrDefault()
    {
        return (string)($this->headerImage ? $this->headerImage : self::DEFAULT_HEADER_IMAGE);
    }

    /**
     * @param string $headerImage
     */
    public function setHeaderImage($headerImage)
    {
        $this->headerImage = (string)$headerImage;
    }

    /**
     * @return string
     */
    public function getSocialImpact()
    {
        return (string)$this->socialImpact;
    }

    /**
     * @param string $socialImpact
     */
    public function setSocialImpact($socialImpact)
    {
        $this->socialImpact = (string)$socialImpact;
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return (bool)$this->isPublished;
    }

    /**
     * @param boolean $isPublished
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = (bool)$isPublished;
    }
}
