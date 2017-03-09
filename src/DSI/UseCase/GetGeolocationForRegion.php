<?php

namespace DSI\UseCase;

use DSI\Entity\UserLink;
use DSI\NotFound;
use DSI\Repository\UserLinkRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;
use DSI\Service\Geolocation;

class GetGeolocationForRegion
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var string */
    private $countryName,
        $regionName;

    /** @var  GetGeolocationForRegion_Return */
    private $geoLocation;

    public function exec()
    {
        $this->errorHandler = new ErrorHandler();
        $this->geoLocation = new GetGeolocationForRegion_Return();

        $countryName = explode(',', $this->countryName)[0];

        $getGeo = new Geolocation();
        $getGeo->setRegionName($this->regionName);
        $getGeo->setCountryName($countryName);

        if ($getGeo->exec()) {
            $this->geoLocation->lat = $getGeo->getLat();
            $this->geoLocation->lon = $getGeo->getLon();
        } else {
            $this->errorHandler->addTaggedError('region', __('This location could not be found.'));
            throw $this->errorHandler;
        }
    }

    /**
     * @param string $countryName
     */
    public function setCountryName(string $countryName)
    {
        $this->countryName = $countryName;
    }

    /**
     * @param string $regionName
     */
    public function setRegionName(string $regionName)
    {
        $this->regionName = $regionName;
    }

    /**
     * @return GetGeolocationForRegion_Return
     */
    public function getGeoLocation(): GetGeolocationForRegion_Return
    {
        return $this->geoLocation;
    }
}

class GetGeolocationForRegion_Return
{
    /** @var string */
    public $lat,
        $lon;
}