<?php

namespace DSI\Controller;

use DSI\Repository\CountryRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ListCountriesController
{
    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());

        $countryRepo = new CountryRepository();
        $countries = [];
        foreach ($countryRepo->getAll() AS $country) {
            $countries[] = [
                'id' => $country->getId(),
                'text' => $country->getName(),
            ];
        }

        echo json_encode($countries);
        die();
    }
}