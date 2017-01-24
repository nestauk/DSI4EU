<?php

namespace DSI\Controller;

set_time_limit(0);

use DSI\Entity\Organisation;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Repository\CountryRegionRepository;
use DSI\Repository\OrganisationLinkRepository;
use DSI\Repository\OrganisationRepository;
use DSI\UseCase\UpdateOrganisation;

class CitiesToGeolocationController
{
    public function exec()
    {
        $countryRegionRepository = new CountryRegionRepository();
        $regions = $countryRegionRepository->getAll();
        foreach ($regions AS $region) {
            $countryName = $region->getCountry()->getName();
            $countryName = explode(',', $countryName)[0];
            $url = 'http://nominatim.openstreetmap.org/search?q=' . urlencode($region->getName()) . ',' . urlencode($countryName) . '&format=json';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $json = curl_exec($ch);
            curl_close($ch);

            $location = null;
            if ($json) {
                $locations = json_decode($json, true);
                if ($locations)
                    $location = $locations[0];
            }

            if (!$location) {
                echo $url;
                echo PHP_EOL;
            } else {
                $region->setLatitude($location['lat']);
                $region->setLongitude($location['lon']);
                $countryRegionRepository->save($region);
            }
        }
    }
}