<?php

namespace DSI\Controller;

set_time_limit(0);

use DSI\DuplicateEntry;
use DSI\Entity\Country;
use DSI\Entity\CountryRegion;
use DSI\Entity\ImpactTag;
use DSI\Entity\Organisation;
use DSI\Entity\OrganisationProject;
use DSI\Entity\OrganisationSize;
use DSI\Entity\OrganisationType;
use DSI\Entity\Project;
use DSI\Entity\ProjectImpactTagA;
use DSI\Entity\ProjectImpactTagB;
use DSI\Entity\ProjectImpactTagC;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Repository\CountryRegionRepository;
use DSI\Repository\CountryRepository;
use DSI\Repository\ImpactTagRepository;
use DSI\Repository\OrganisationProjectRepository;
use DSI\Repository\OrganisationRepository;
use DSI\Repository\OrganisationSizeRepository;
use DSI\Repository\OrganisationTypeRepository;
use DSI\Repository\ProjectImpactTagARepository;
use DSI\Repository\ProjectImpactTagBRepository;
use DSI\Repository\ProjectImpactTagCRepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\ErrorHandler;
use DSI\UseCase\AddProjectToOrganisation;

class ImportController
{
    public function exec()
    {
        $this->importOrganisationsFrom(__DIR__ . '/../../../import/orgs.csv');
        $this->importProjectsFrom(__DIR__ . '/../../../import/projects.csv');
        $this->importOrgProjectsFrom(__DIR__ . '/../../../import/org-projects.csv');
    }

    /**
     * @param $file
     */
    private function importOrganisationsFrom($file)
    {
        $row = 0;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                $row++;

                if ($row == 1) {
                    var_dump(['skip', $data]);
                    continue;
                }

                $this->importOrganisationRow($data);
            }
            fclose($handle);
        }
    }

    /**
     * @param $data
     */
    private function importOrganisationRow($data)
    {
        var_dump($data);
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

    /**
     * @param $organisationTypeName
     * @return OrganisationType
     */
    private function getOrganisationType($organisationTypeName)
    {
        if ($organisationTypeName == '')
            return null;

        $organisationTypeRepo = new OrganisationTypeRepository();
        try {
            $organisationType = $organisationTypeRepo->getByName($organisationTypeName);
            return $organisationType;
        } catch (NotFound $e) {
            $organisationType = new OrganisationType();
            $organisationType->setName($organisationTypeName);
            $organisationTypeRepo->insert($organisationType);
            return $organisationType;
        }
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


    /**
     * @param $file
     */
    private function importProjectsFrom($file)
    {
        $row = 0;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                $row++;

                if ($row == 1) {
                    var_dump(['skip', $data]);
                    continue;
                }

                $this->importProjectRow($data);
            }
            fclose($handle);
        }
    }


    /**
     * @param $data
     */
    private function importProjectRow($data)
    {
        var_dump($data);
        $project = new Project();
        $project->setOwner($this->setOwner());

        $project->setImportID($data[0]);
        $project->setName($data[1]);
        $project->setDescription($data[6]);
        // TODO project type $data[2] - use as tag
        // TODO project links $data[3] - link
        (new ProjectRepository())->insert($project);
        $this->setAreasOfSociety($data[4], $project);
        // TODO DSI Areas?
        $this->setTechnologyFocus($data[7], $project);
        $this->setTechnologyMethod($data[8], $project);
    }

    /**
     * @param $areasOfSociety
     * @param $project
     * @throws \DSI\DuplicateEntry
     * @throws \DSI\NotEnoughData
     */
    private function setAreasOfSociety($areasOfSociety, $project)
    {
        $areasOfSociety = explode(',', $areasOfSociety);
        $areasOfSociety = array_map('trim', $areasOfSociety);
        $impactTagRepository = (new ImpactTagRepository());

        foreach ($areasOfSociety AS $areaOfSociety) {
            if ($areaOfSociety == '')
                continue;

            try {
                $tag = $impactTagRepository->getByName($areaOfSociety);
            } catch (NotFound $e) {
                $tag = new ImpactTag();
                $tag->setName($areaOfSociety);
                $impactTagRepository->insert($tag);
            }

            $projectTag = new ProjectImpactTagA();
            $projectTag->setProject($project);
            $projectTag->setTag($tag);
            (new ProjectImpactTagARepository())->add($projectTag);
        }
    }

    private function setTechnologyFocus($areasOfTechnologyFocus, $project)
    {
        $areasOfTechnologyFocus = explode(',', $areasOfTechnologyFocus);
        $areasOfTechnologyFocus = array_map('trim', $areasOfTechnologyFocus);
        $impactTagRepository = (new ImpactTagRepository());

        foreach ($areasOfTechnologyFocus AS $areaOfTechnologyFocus) {
            if ($areaOfTechnologyFocus == '')
                continue;

            try {
                $tag = $impactTagRepository->getByName($areaOfTechnologyFocus);
            } catch (NotFound $e) {
                $tag = new ImpactTag();
                $tag->setName($areaOfTechnologyFocus);
                $impactTagRepository->insert($tag);
            }

            $projectTag = new ProjectImpactTagB();
            $projectTag->setProject($project);
            $projectTag->setTag($tag);
            (new ProjectImpactTagBRepository())->add($projectTag);
        }
    }

    private function setTechnologyMethod($areasOfTechnologyMethod, $project)
    {
        $areasOfTechnologyMethod = explode(',', $areasOfTechnologyMethod);
        $areasOfTechnologyMethod = array_map('trim', $areasOfTechnologyMethod);
        $impactTagRepository = (new ImpactTagRepository());

        foreach ($areasOfTechnologyMethod AS $areaOfTechnologyMethod) {
            if ($areaOfTechnologyMethod == '')
                continue;

            try {
                $tag = $impactTagRepository->getByName($areaOfTechnologyMethod);
            } catch (NotFound $e) {
                $tag = new ImpactTag();
                $tag->setName($areaOfTechnologyMethod);
                $impactTagRepository->insert($tag);
            }

            $projectTag = new ProjectImpactTagC();
            $projectTag->setProject($project);
            $projectTag->setTag($tag);
            (new ProjectImpactTagCRepository())->add($projectTag);
        }
    }


    /**
     * @param $file
     */
    private function importOrgProjectsFrom($file)
    {
        $row = 0;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                $row++;

                if ($row == 1) {
                    var_dump(['skip', $data]);
                    continue;
                }

                $this->importOrgProjectRow($data);
            }
            fclose($handle);
        }
    }

    /**
     * @param $data
     */
    private function importOrgProjectRow($data)
    {
        try {
            $organisation = (new OrganisationRepository())->getByImportID($data[1]);
        } catch (NotFound $e) {
            var_dump(['invalid org' => $data[1]]);
        }
        try {
            $project = (new ProjectRepository())->getByImportID($data[2]);
        } catch (NotFound $e) {
            var_dump(['invalid proj' => $data[2]]);
        }

        if (isset($organisation) AND isset($project)) {
            $addProjectToOrgCmd = new AddProjectToOrganisation();
            $addProjectToOrgCmd->data()->organisationID = $organisation->getId();
            $addProjectToOrgCmd->data()->projectID = $project->getId();
            try {
                $addProjectToOrgCmd->exec();
            } catch (ErrorHandler $e) {
                var_dump([
                    'error' => $e->getErrors(),
                    'org' => $data[1],
                    'proj' => $data[2],
                ]);
            }

            // var_dump($data);
        }
    }
}