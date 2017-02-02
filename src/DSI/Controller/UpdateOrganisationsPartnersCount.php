<?php

namespace DSI\Controller;

use DSI\Repository\OrganisationRepository;
use DSI\UseCase\CalculateOrganisationPartnersCount;

class UpdateOrganisationsPartnersCount
{
    public function exec()
    {
        $organisationRepo = new OrganisationRepository();
        foreach($organisationRepo->getAll() AS $organisation){
            $exec = new CalculateOrganisationPartnersCount();
            $exec->setOrganisation($organisation);
            $exec->exec();
        }
    }
}