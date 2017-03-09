<?php

namespace DSI\Controller\CLI;

set_time_limit(0);

use DSI\Repository\CountryRegionRepository;
use DSI\Service\ErrorHandler;
use DSI\UseCase\GetGeolocationForRegion;

class CitiesToGeolocationController
{
    public function exec()
    {
        $countryRegionRepository = new CountryRegionRepository();
        $regions = $countryRegionRepository->getAll();
        foreach ($regions AS $region) {
            if (!$region->getLatitude() OR !$region->getLongitude()) {
                try {
                    $exec = new GetGeolocationForRegion();
                    $exec->setCountryName($region->getCountry()->getName());
                    $exec->setRegionName($region->getName());
                    $exec->exec();
                    $location = $exec->getGeoLocation();

                    $region->setLatitude($location->lat);
                    $region->setLongitude($location->lon);
                    $countryRegionRepository->save($region);
                } catch (ErrorHandler $e) {
                    echo $region->getName() . ',' . $region->getCountry()->getName() . PHP_EOL;
                }
            }
        }
    }
}