<?php

namespace DSI\UseCase;

use DSI\Entity\CountryRegion;
use DSI\Repository\CountryRegionRepository;
use DSI\Repository\CountryRepository;
use DSI\Service\ErrorHandler;

class CreateCountryRegion
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var CountryRepository */
    private $countryRepo;

    /** @var CountryRegionRepository */
    private $countryRegionRepo;

    /** @var CountryRegion */
    private $countryRegion;

    /** @var CreateCountryRegion_Data */
    private $data;

    public function __construct()
    {
        $this->data = new CreateCountryRegion_Data();
    }

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->countryRegionRepo = new CountryRegionRepository();
        $this->countryRepo = new CountryRepository();

        if ($this->countryRegionRepo->nameExists($this->data()->countryID, $this->data()->name)) {
            $this->errorHandler->addTaggedError('region', __('The region name already exists'));
            $this->errorHandler->throwIfNotEmpty();
        }

        $country = $this->countryRepo->getById($this->data()->countryID);

        $exec = new GetGeolocationForRegion();
        $exec->setCountryName($country->getName());
        $exec->setRegionName($this->data()->name);
        $exec->exec();
        $location = $exec->getGeoLocation();

        $countryRegion = new CountryRegion();
        $countryRegion->setName((string)$this->data()->name);
        $countryRegion->setCountry($country);
        $countryRegion->setLatitude($location->lat);
        $countryRegion->setLongitude($location->lon);
        $this->countryRegionRepo->insert($countryRegion);

        $this->countryRegion = $countryRegion;
    }

    /**
     * @return CreateCountryRegion_Data
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return CountryRegion
     */
    public function getCountryRegion()
    {
        return $this->countryRegion;
    }
}

class CreateCountryRegion_Data
{
    /** @var int */
    public $countryID;

    /** @var string */
    public $name;
}