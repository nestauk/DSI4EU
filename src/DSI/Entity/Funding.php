<?php

namespace DSI\Entity;

class Funding
{
    /** @var integer */
    private $id;

    /** @var string */
    private $title,
        $url,
        $description,
        $timeCreated,
        $closingDate;

    /** @var FundingSource */
    private $fundingSource;

    /** @var Country */
    private $country;

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
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return $this->country ? $this->country->getName() : '';
    }

    /**
     * @return int
     */
    public function getCountryID()
    {
        return $this->getCountry() ? $this->getCountry()->getId() : 0;
    }

    /**
     * @param Country $country
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
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
    public function getClosingDate($format = null): string
    {
        if (!$this->closingDate OR $this->closingDate == '0000-00-00')
            return '';

        if ($format !== null)
            return date($format, strtotime($this->closingDate));

        return (string)$this->closingDate;
    }

    /**
     * @param string $closingDate
     */
    public function setClosingDate($closingDate)
    {
        if ($closingDate == '0000-00-00')
            $closingDate = '';
        $this->closingDate = (string)$closingDate;
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
     * @return FundingSource
     */
    public function getSource(): FundingSource
    {
        return $this->fundingSource;
    }

    /**
     * @return string
     */
    public function getSourceTitle()
    {
        return $this->fundingSource ? $this->fundingSource->getTitle() : '';
    }

    /**
     * @return int
     */
    public function getSourceID(): int
    {
        return $this->fundingSource ? $this->fundingSource->getId() : 0;
    }

    /**
     * @param FundingSource $fundingSource
     */
    public function setSource(FundingSource $fundingSource)
    {
        $this->fundingSource = $fundingSource;
    }
}