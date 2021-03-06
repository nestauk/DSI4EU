<?php

namespace DSI\Controller;

set_time_limit(0);

use DSI\Entity\Country;
use DSI\Entity\CountryRegion;
use DSI\Entity\ImpactTag;
use DSI\Entity\Organisation;
use DSI\Entity\OrganisationSize;
use DSI\Entity\OrganisationType;
use DSI\Entity\Project;
use DSI\Entity\ProjectImpactHelpTag;
use DSI\Entity\ProjectDsiFocusTag;
use DSI\Entity\ProjectImpactTechTag;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Repository\CountryRegionRepo;
use DSI\Repository\CountryRepo;
use DSI\Repository\ImpactTagRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\OrganisationSizeRepo;
use DSI\Repository\OrganisationTypeRepo;
use DSI\Repository\ProjectImpactHelpTagRepo;
use DSI\Repository\ProjectDsiFocusTagRepo;
use DSI\Repository\ProjectImpactTechTagRepo;
use DSI\Repository\ProjectRepo;
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
        $organisation->setOwner($this->getRootUser());

        $organisation->setImportID($data[0]);
        $organisation->setName($data[1]);
        $this->setOrganisationType($data[2], $organisation);
        $this->setOrganisationSize($data[3], $organisation);
        $organisation->setAddress("{$data[11]},\n{$data[12]}");
        // ignore region $data[13]
        $this->setCountryRegion($organisation, $data[14], $data[15]);

        (new OrganisationRepo())->insert($organisation);
    }

    /**
     * @return User
     */
    private function getRootUser()
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

        $organisationTypeRepo = new OrganisationTypeRepo();
        try {
            $organisationType = $organisationTypeRepo->getByName($organisationTypeName);
        } catch (NotFound $e) {
            $organisationType = new OrganisationType();
            $organisationType->setName($organisationTypeName);
            $organisationTypeRepo->insert($organisationType);
        }

        if ($organisationType)
            $organisation->setType($organisationType);
    }

    private function setOrganisationSize($organisationSizeName, Organisation $organisation)
    {
        if ($organisationSizeName == '')
            return;

        $organisationSizeRepo = new OrganisationSizeRepo();
        try {
            $organisationSize = $organisationSizeRepo->getByName($organisationSizeName);
        } catch (NotFound $e) {
            $organisationSize = new OrganisationSize();
            $organisationSize->setName($organisationSizeName);
            $organisationSizeRepo->insert($organisationSize);
        }

        if ($organisationSize)
            $organisation->setSize($organisationSize);
    }

    private function setCountryRegion(Organisation $organisation, $countryName, $regionName)
    {
        if ($countryName == '')
            return;

        $countryRepository = new CountryRepo();
        try {
            $country = $countryRepository->getByName($countryName);
        } catch (NotFound $e) {
            $country = new Country();
            $country->setName($countryName);
            $countryRepository->insert($country);
        }

        $countryRegionRepo = new CountryRegionRepo();

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
        $project->setOwner($this->getRootUser());

        $project->setImportID($data[0]);
        $project->setName($data[1]);
        $project->setDescription($data[6]);
        // TODO project type $data[2] - use as tag
        // TODO project links $data[3] - link
        (new ProjectRepo())->insert($project);
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
        $impactTagRepository = (new ImpactTagRepo());

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

            $projectTag = new ProjectImpactHelpTag();
            $projectTag->setProject($project);
            $projectTag->setTag($tag);
            (new ProjectImpactHelpTagRepo())->add($projectTag);
        }
    }

    private function setTechnologyFocus($areasOfTechnologyFocus, $project)
    {
        $areasOfTechnologyFocus = explode(',', $areasOfTechnologyFocus);
        $areasOfTechnologyFocus = array_map('trim', $areasOfTechnologyFocus);
        $impactTagRepository = (new ImpactTagRepo());

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

            $projectTag = new ProjectDsiFocusTag();
            $projectTag->setProject($project);
            $projectTag->setTag($tag);
            (new ProjectDsiFocusTagRepo())->add($projectTag);
        }
    }

    private function setTechnologyMethod($areasOfTechnologyMethod, $project)
    {
        $areasOfTechnologyMethod = explode(',', $areasOfTechnologyMethod);
        $areasOfTechnologyMethod = array_map('trim', $areasOfTechnologyMethod);
        $impactTagRepository = (new ImpactTagRepo());

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

            $projectTag = new ProjectImpactTechTag();
            $projectTag->setProject($project);
            $projectTag->setTag($tag);
            (new ProjectImpactTechTagRepo())->add($projectTag);
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
            $organisation = (new OrganisationRepo())->getByImportID($data[1]);
        } catch (NotFound $e) {
            var_dump(['invalid org' => $data[1]]);
        }
        try {
            $project = (new ProjectRepo())->getByImportID($data[2]);
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