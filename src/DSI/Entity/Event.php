<?php

namespace DSI\Entity;

class Event
{
    /** @var integer */
    private $id;

    /** @var string */
    private $title,
        $url,
        $shortDescription,
        $description,
        $timeCreated,
        $startDate,
        $endDate,
        $address,
        $phoneNumber,
        $emailAddress,
        $price;

    /** @var CountryRegion */
    private $region;

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
    public function getTitle(): string
    {
        return (string)$this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
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
    public function setShortDescription(string $shortDescription)
    {
        $this->shortDescription = $shortDescription;
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
     * @return CountryRegion
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getRegionName()
    {
        return $this->region ? $this->region->getName() : '';
    }

    /**
     * @return int
     */
    public function getRegionID()
    {
        return $this->getRegion() ? $this->getRegion()->getId() : 0;
    }

    /**
     * @param CountryRegion $countryRegion
     */
    public function setRegion(CountryRegion $countryRegion)
    {
        $this->region = $countryRegion;
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
     * @return bool
     */
    public function isNew(): bool
    {
        return (bool)(strtotime($this->timeCreated) > (time() - 60 * 60 * 24 * 7));
    }

    /**
     * @return string
     */
    public function getStartDate($format = null): string
    {
        if ($format AND $this->startDate)
            return date($format, strtotime($this->startDate));

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
    public function getEndDate(): string
    {
        return (string)$this->endDate;
    }

    /**
     * @param string $endDate
     */
    public function setEndDate($endDate)
    {
        if ($endDate == '0000-00-00')
            $endDate = '';
        $this->endDate = (string)$endDate;
    }

    /**
     * @return string
     */
    public function getTimeCreated(): string
    {
        return (string)$this->timeCreated;
    }

    /**
     * @param string $timeCreated
     */
    public function setTimeCreated(string $timeCreated)
    {
        if ($timeCreated == '0000-00-00 00:00:00')
            $timeCreated = '';

        $this->timeCreated = $timeCreated;
    }

    /**
     * @return string
     */
    public function getAddress(): string
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
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return (string)$this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = (string)$phoneNumber;
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return (string)$this->emailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = (string)$emailAddress;
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return (string)$this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice($price)
    {
        $this->price = (string)$price;
    }
}