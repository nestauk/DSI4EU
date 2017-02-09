<?php

namespace DSI\UseCase;

use DSI\Entity\UserLink;
use DSI\NotFound;
use DSI\Repository\UserLinkRepository;
use DSI\Repository\UserRepository;
use DSI\Service\ErrorHandler;

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
        $url = 'http://nominatim.openstreetmap.org/search?q=' . urlencode($this->regionName) . ',' . urlencode($countryName) . '&format=json';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, 'https://digitalsocial.eu/');
        $json = curl_exec($ch);
        curl_close($ch);

        $location = null;
        if ($json) {
            $locations = json_decode($json, true);
            if ($locations)
                $location = $locations[0];
        }

        if (!$location) {
            $this->errorHandler->addTaggedError('region', __('This location could not be found.'));
            throw $this->errorHandler;
        }

        $this->geoLocation->lat = $location['lat'];
        $this->geoLocation->lon = $location['lon'];
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