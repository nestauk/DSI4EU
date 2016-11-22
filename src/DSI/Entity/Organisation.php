<?php

namespace DSI\Entity;

class Organisation
{
    public $extraData;
    const DEFAULT_HEADER_IMAGE = '0.png';
    const DEFAULT_LOGO = '0.png';
    const DEFAULT_LOGO_SILVER = '0-silver.png';

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
    private $type;

    /** @var OrganisationSize */
    private $size;

    /** @var string */
    private $importID;

    /** @var int */
    private $partnersCount,
        $projectsCount;

    /** @var string */
    private $startDate,
        $creationTime,
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return $this->type ? $this->type->getName() : '';
    }

    /**
     * @return int
     */
    public function getTypeId()
    {
        return $this->type ? $this->type->getId() : 0;
    }

    /**
     * @param OrganisationType $type
     */
    public function setType(OrganisationType $type)
    {
        $this->type = $type;
    }

    /**
     * @return OrganisationSize
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getSizeName()
    {
        return $this->size ? $this->size->getName() : '';
    }

    /**
     * @return int
     */
    public function getSizeId()
    {
        return $this->size ? $this->size->getId() : 0;
    }

    /**
     * @param OrganisationSize $size
     */
    public function setSize(OrganisationSize $size)
    {
        $this->size = $size;
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
     * @return string
     */
    public function getExternalUrl(): string
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
     * @return int
     */
    public function getUnixStartDate()
    {
        return (int)strtotime($this->startDate);
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
    public function getCreationTime($format = null)
    {
        if ($format !== null) {
            return date($format, strtotime($this->creationTime));
        }

        return $this->creationTime;
    }

    /**
     * @param string $creationTime
     */
    public function setCreationTime($creationTime)
    {
        $this->creationTime = $creationTime;
    }

    /**
     * @return string
     */
    public function getLogo(): string
    {
        return (string)$this->logo;
    }

    /**
     * @return string
     */
    public function getLogoOrDefault(): string
    {
        return (string)($this->logo ? $this->logo : self::DEFAULT_LOGO);
    }

    /**
     * @return string
     */
    public function getLogoOrDefaultSilver(): string
    {
        return (string)($this->logo ? $this->logo : self::DEFAULT_LOGO_SILVER);
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
     * @return string
     */
    public function getHeaderImageOrDefault(): string
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
}