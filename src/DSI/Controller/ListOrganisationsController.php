<?php

namespace DSI\Controller;

use DSI\Repository\OrganisationRepository;
use DSI\Service\Auth;
use DSI\Service\URL;

class ListOrganisationsController
{
    public function exec()
    {
        $authUser = new Auth();
        $authUser->ifNotLoggedInRedirectTo(URL::login());

        $organisationRepo = new OrganisationRepository();
        $organisations = [];
        foreach ($organisationRepo->getAll() AS $organisation) {
            $organisations[] = [
                'id' => $organisation->getId(),
                'text' => $organisation->getName(),
                'url' => URL::organisation($organisation->getId(), $organisation->getName()),
            ];
        }

        usort($organisations, function ($a, $b) {
            return ($a['text'] <= $b['text']) ? -1 : 1;
        });

        echo json_encode($organisations);
        die();
    }
}