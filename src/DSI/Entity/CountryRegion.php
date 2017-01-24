<?php

namespace DSI\Entity;

class CountryRegion
{
    /** @var integer */
    private $id;

    /** @var Country */
    private $country;

    /** @var string */
    private $name,
        $latitude,
        $longitude;

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
     * @return Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

    public function hasCountry(): bool
    {
        return $this->country !== null;
    }

    /**
     * @param Country $country
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return (float)$this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = (float)$latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return (float)$this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = (float)$longitude;
    }
}