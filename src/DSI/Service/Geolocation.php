<?php

namespace DSI\Service;
use Services\App;

class Geolocation
{
    /** @var  string */
    private $regionName,
        $countryName;

    private $lat,
        $lon;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @return mixed
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * @return bool
     */
    public function exec()
    {
        if (App::getEnv() == App::DEV OR App::getEnv() == App::TEST) {
            $this->lat = 1;
            $this->lon = 1;
            return true;
        }

        $url = 'http://nominatim.openstreetmap.org/search?q=' . urlencode($this->regionName) . ',' . urlencode($this->countryName) . '&format=json';

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

            if ($location) {
                $this->lat = $location['lat'];
                $this->lon = $location['lon'];
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $regionName
     */
    public function setRegionName(string $regionName)
    {
        $this->regionName = $regionName;
    }

    /**
     * @param string $countryName
     */
    public function setCountryName(string $countryName)
    {
        $this->countryName = $countryName;
    }
}