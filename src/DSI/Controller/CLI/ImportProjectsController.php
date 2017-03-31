<?php

namespace DSI\Controller\CLI;

use DSI\Entity\Project;
use DSI\Entity\User;
use DSI\NotFound;
use DSI\Repository\CountryRepository;
use DSI\Repository\ProjectRepository;
use DSI\Service\ErrorHandler;
use DSI\UseCase\UpdateProject;
use DSI\UseCase\UpdateProjectCountryRegion;

set_time_limit(0);

class ImportProjectsController
{
    /** @var User */
    private $sysAdminUser;

    /** @var ProjectRepository */
    private $projectRepo;

    public function exec()
    {
        $this->sysAdminUser = new User;
        $this->sysAdminUser->setId(1);
        $this->sysAdminUser->setRole('sys-admin');

        $this->projectRepo = new ProjectRepository();

        $this->importFrom(__DIR__ . '/../../../../import-projects.csv');
    }

    /**
     * @param $file
     */
    private function importFrom($file)
    {
        $row = 0;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle)) !== FALSE) {
                if ($row > 2)
                    $this->importRow($data);

                echo 'imported row ' . $row . PHP_EOL;
                $row++;
            }
            fclose($handle);
        }
    }

    /**
     * @param $data
     */
    private function importRow($data)
    {
        list(
            $unusedId,
            $title,
            $website,
            $shortDescription,
            $longDescription,
            $socialImpact,
            $startDate,
            $endDate,
            $country,
            $region,
            $unusedLatitude,
            $unusedLongitude,
            $unusedLinkedOrganisations,
            $unusedWhoWeHelpTags,
            $supportTags,
            $focus,
            $technology
            ) = $data;

        // print_r($data);
        $project = new Project();
        $project->setName($title);
        $project->setOwner($this->sysAdminUser);
        $project->setUrl($website);
        $project->setShortDescription($shortDescription);
        $project->setDescription($longDescription);
        $project->setStartDate($startDate);
        $project->setEndDate($endDate);
        $project->setSocialImpact($socialImpact);

        $this->projectRepo->insert($project);

        if (trim($country)) {
            $country = trim($country);

            if ($country == 'Croatia, Italy, Netherlands')
                $country = 'EU';
            if ($country == 'Greece, Norway, Germany, Belgium, Netherlands.')
                $country = 'EU';

            if ($country == 'Russia')
                $country = 'Russian Federation';
            if (strtolower($country) == 'uk')
                $country = 'United Kingdom';

            if ($region == '')
                $region = $country;

            try {
                $project = $this->setRegion($project, $country, $region);
                $project = $this->setTags($project, $supportTags, $focus, $technology);

                $this->projectRepo->save($project);
                // print_r($organisation);
            } catch (NotFound $e) {
                pr('Not Found');
                pr($e->getMessage());
                pr($e->getTrace());
            } catch (ErrorHandler $e) {
                pr('Error');
                pr($e->getErrors());
            }
        }
    }

    private function setRegion(Project $project, $country, $region)
    {
        $countryRepo = new CountryRepository();
        $countryObj = $countryRepo->getByName($country);

        $exec = new UpdateProjectCountryRegion();
        $exec->data()->countryID = $countryObj->getId();
        $exec->data()->projectID = $project->getId();
        $exec->data()->region = $region;
        $exec->exec();

        return $project;
    }

    private function getSupportTagName($typeID)
    {
        $types = [
            1 => "Education and skills",
            2 => "Participation and democracy",
            3 => "Culture and arts",
            4 => "Health and wellbeing",
            5 => "Work and employment",
            6 => "Neighbourhood regeneration",
            7 => "Energy and environment",
            8 => "Science",
            9 => "Finance and economy",
        ];
        return $types[$typeID];
    }

    private function getTechnologyTagName($typeID)
    {
        $types = [
            1 => "Social networks and social media",
            2 => "Crowdsourcing, crowdmapping and crowdfunding",
            3 => "Open data",
            4 => "Mobile and web apps",
            5 => "Open-source technologies",
            6 => "Peer-to-peer networks",
            7 => "Online learning and MOOCs",
            8 => "Online marketplaces and noticeboards",
            9 => "Big data",
            10 => "Sensors and internet of things",
            11 => "Geotagging, GPS and GIS",
            12 => "Digital fabrication and 3D-printing",
            13 => "Wearables and personal monitoring",
            14 => "AI and machine learning",
            15 => "Digital democracy tools",
            16 => "Blockchain and distributed networks",
            17 => "Robotics",
            18 => "VR and AR"
        ];
        return $types[$typeID];
    }

    private function setTags(Project $project, $supportTags, $focusTags, $technologyTags)
    {
        if ($supportTags) {
            $supportTagsList = explode(',', $supportTags);
            $supportTagsList = array_map('trim', $supportTagsList);
            $supportTagsList = array_filter($supportTagsList);
            $supportTagsList = array_map(function ($id) {
                return $this->getSupportTagName($id);
            }, $supportTagsList);
        } else {
            $supportTagsList = [];
        }

        if ($focusTags) {
            $focusTagsList = explode(',', $focusTags);
            $focusTagsList = array_map('trim', $focusTagsList);
            $focusTagsList = array_filter($focusTagsList);
        } else {
            $focusTagsList = [];
        }

        if ($technologyTags) {
            $technologyTagsList = explode(',', $technologyTags);
            $technologyTagsList = array_map('trim', $technologyTagsList);
            $technologyTagsList = array_filter($technologyTagsList);
            $technologyTagsList = array_map(function ($id) {
                return $this->getTechnologyTagName($id);
            }, $technologyTagsList);
        } else {
            $technologyTagsList = [];
        }
        print_r($technologyTagsList);

        $exec = new UpdateProject();
        $exec->data()->executor = $this->sysAdminUser;
        $exec->data()->project = $project;
        $exec->data()->areasOfImpact = $supportTagsList;
        $exec->data()->focusTags = $focusTagsList;
        $exec->data()->technologyTags = $technologyTagsList;
        $exec->exec();

        return $project;
    }
}