<?php

namespace DSI\Controller;

set_time_limit(0);

use DSI\Entity\Country;
use DSI\Entity\CountryRegion;
use DSI\Entity\Organisation;
use DSI\Entity\OrganisationSize;
use DSI\Entity\OrganisationType;
use DSI\Entity\Project;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Repository\CountryRegionRepository;
use DSI\Repository\CountryRepository;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\OrganisationSizeRepository;
use DSI\Repository\OrganisationTypeRepository;
use DSI\Repository\ProjectRepository;
use DSI\Repository\UserRepository;
use DSI\UseCase\AddProjectToOrganisation;
use DSI\UseCase\SetAdminStatusToOrganisationMember;
use DSI\UseCase\SetAdminStatusToProjectMember;

class ImportUserProjectOrganisationLinkController
{
    private $sysAdminUser;

    public function exec()
    {
        $this->sysAdminUser = new User;
        $this->sysAdminUser->setRole('sys-admin');

        $this->importLinksFrom(__DIR__ . '/../../../import/dsi-users-organisations-projects.csv');
    }

    /**
     * @param $file
     */
    private function importLinksFrom($file)
    {
        $row = 0;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                $row++;
                if ($row === 1) {
                    continue;
                }

                $this->importRow($data);
            }
            fclose($handle);
        }
    }

    /**
     * @param $data
     */
    private function importRow($data)
    {
        $email = $data[0];
        $organisationUID = $data[1];
        $projectUID = $data[4];

        if($organisationUID == 'nan')
            $organisationUID = '';
        if(!$organisationUID AND !$projectUID)
            return;
        if($email == 'samuel@.iv.io')
            return;

        /*
        print_r([
            'email' => $email,
            'organisationUID' => $organisationUID,
            'projectUID' => $projectUID,
        ]);
        */

        try {
            $user = (new UserRepository())->getByEmail($email);
        } catch(NotFound $e){
            $user = new User();
            $user->setEmail($email);
            (new UserRepository())->insert($user);
        }

        try{
            $organisation = (new OrganisationRepository())->getByImportID($organisationUID);
        } catch (NotFound $e){
            return;
        }

        try{
            $project = (new ProjectRepository())->getByImportID($projectUID);
        } catch (NotFound $e){
            return;
        }

        $this->organisationMustHaveProject($organisation, $project);
        $this->addAdminToProject($project, $user);
        $this->addAdminToOrganisation($organisation, $user);

        return;

        $organisation = new Organisation();
        $organisation->setOwner($this->setOwner());

        $organisation->setImportID($data[0]);
        $organisation->setName($data[1]);
        $this->setOrganisationType($data[2], $organisation);
        $this->setOrganisationSize($data[3], $organisation);
        // TODO create organisation URLs
        // TODO add twitter $data[4]
        // TODO add website $data[5]
        // TODO add lat + long $data[8,9]
        $organisation->setAddress("{$data[11]},\n{$data[12]}");
        // ignore region $data[13]
        $this->setCountryRegion($organisation, $data[14], $data[15]);

        (new OrganisationRepository())->insert($organisation);
    }

    /**
     * @return User
     */
    private function setOwner()
    {
        // TODO create root user
        $owner = new User();
        $owner->setId(1);
        return $owner;
    }

    private function setOrganisationType($organisationTypeName, Organisation $organisation)
    {
        if ($organisationTypeName == '')
            return;

        $organisationTypeRepo = new OrganisationTypeRepository();
        try {
            $organisationType = $organisationTypeRepo->getByName($organisationTypeName);
        } catch (NotFound $e) {
            $organisationType = new OrganisationType();
            $organisationType->setName($organisationTypeName);
            $organisationTypeRepo->insert($organisationType);
        }

        if ($organisationType)
            $organisation->setOrganisationType($organisationType);
    }

    private function setOrganisationSize($organisationSizeName, Organisation $organisation)
    {
        if ($organisationSizeName == '')
            return;

        $organisationSizeRepo = new OrganisationSizeRepository();
        try {
            $organisationSize = $organisationSizeRepo->getByName($organisationSizeName);
        } catch (NotFound $e) {
            $organisationSize = new OrganisationSize();
            $organisationSize->setName($organisationSizeName);
            $organisationSizeRepo->insert($organisationSize);
        }

        if ($organisationSize)
            $organisation->setOrganisationSize($organisationSize);
    }

    private function setCountryRegion(Organisation $organisation, $countryName, $regionName)
    {
        if ($countryName == '')
            return;

        $countryRepository = new CountryRepository();
        try {
            $country = $countryRepository->getByName($countryName);
        } catch (NotFound $e) {
            $country = new Country();
            $country->setName($countryName);
            $countryRepository->insert($country);
        }

        $countryRegionRepo = new CountryRegionRepository();

        try {
            $countryRegion = $countryRegionRepo->getByName($country->getId(), $regionName);
        } catch (NotFound $e) {
            $countryRegion = new CountryRegion();
            $countryRegion->setCountry($country);
            $countryRegion->setName($regionName);
            $countryRegionRepo->insert($countryRegion);
        }

        $organisation->setCountryRegion($countryRegion);
    }

    private function addAdminToProject(Project $project, User $user)
    {
        $projectAdmin = new SetAdminStatusToProjectMember();
        $projectAdmin->data()->project = $project;
        $projectAdmin->data()->executor = $this->sysAdminUser;
        $projectAdmin->data()->member = $user;
        $projectAdmin->data()->isAdmin = true;
        $projectAdmin->exec();
    }

    private function addAdminToOrganisation(Organisation $organisation, User $user)
    {
        $organisationAdmin = new SetAdminStatusToOrganisationMember();
        $organisationAdmin->data()->organisation = $organisation;
        $organisationAdmin->data()->executor = $this->sysAdminUser;
        $organisationAdmin->data()->member = $user;
        $organisationAdmin->data()->isAdmin = true;
        $organisationAdmin->exec();
    }

    private function organisationMustHaveProject(Organisation $organisation, Project $project)
    {
        if (!(new OrganisationProjectRepository())->organisationHasProject(
            $organisation->getId(),
            $project->getId()
        )
        ) {
            $addProjectToOrg = new AddProjectToOrganisation();
            $addProjectToOrg->data()->organisationID = $organisation->getId();
            $addProjectToOrg->data()->projectID = $project->getId();
            $addProjectToOrg->exec();
        }
    }
}