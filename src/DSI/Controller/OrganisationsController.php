<?php

namespace DSI\Controller;

use DSI\Entity\Organisation;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\UserRepository;
use DSI\Service\Auth;

class OrganisationsController
{
    public $responseFormat = 'html';

    public function exec()
    {
        if ($this->responseFormat == 'json') {
            $organisationRepo = new OrganisationRepository();
            echo json_encode(array_map(function (Organisation $organisation) {
                $region = $organisation->getCountryRegion();
                return [
                    'id' => $organisation->getId(),
                    'name' => $organisation->getName(),
                    'region' => ($region ? $region->getName() : ''),
                    'country' => ($region ? $region->getCountry()->getName() : ''),
                ];
            }, $organisationRepo->getAll()));
        } else {
            $authUser = new Auth();
            if ($authUser->getUserId())
                $loggedInUser = (new UserRepository())->getById($authUser->getUserId());
            else
                $loggedInUser = null;

            $data = [
                'loggedInUser' => $loggedInUser
            ];
            require __DIR__ . '/../../../www/organisations.php';
        }
    }
}