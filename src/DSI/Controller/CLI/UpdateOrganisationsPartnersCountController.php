<?php

namespace DSI\Controller\CLI;

use DSI\Repository\OrganisationRepository;
use DSI\UseCase\CalculateOrganisationPartnersCount;

class UpdateOrganisationsPartnersCountController
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