<?php

namespace Controllers\Console;

use DSI\Entity\Organisation;
use DSI\Entity\Project;
use DSI\NotFound;
use DSI\Repository\CountryRepo;
use DSI\Repository\DsiFocusTagRepo;
use DSI\Repository\ImpactTagRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;
use DSI\UseCase\CreateOrganisation;
use DSI\UseCase\UpdateProject;
use League\Csv\Reader;
use League\Csv\Statement;

class ImportProjects_2018_07_Controller
{
    private $file = '';

    private $supportTags = [
        // 1. Skills and learning (--> Skills and learning cluster)
        1 => 1,
        // 2. Digital democracy (--> Digital democracy cluster)
        2 => 2,
        // 3. Culture and arts
        3 => 3,
        // 4. Health and care (--> Health and care cluster)
        4 => 18,
        // 5. Work and employment
        5 => 7,
        // 6. Neighbourhood regeneration (--> Cities and urban development cluster)
        6 => 20,
        // 7. Energy and environment (--> Food, environment and climate change cluster)
        7 => 37,
        // 8. Science
        8 => 27,
        // 9. Finance and economy
        9 => 14,
        // 10. Migration and integration (-> Migration and integration cluster)
        10 => 1630,
    ];

    private $focusTags = [
        // 1. Open hardware
        1 => 35,
        // 2. Open networks
        2 => 9,
        // 3. Open data
        3 => 8,
        // 4. Open knowledge
        4 => 4,
    ];

    private $technologyTags = [
        // 1. Social networks and social media
        1 => 931,
        // 2. Crowdsourcing, crowdmapping and crowdfunding
        2 => 1013,
        // 3. Open data
        3 => 8,
        // 4. Mobile and web apps
        4 => 929,
        // 5. Open-source technologies
        5 => 935,
        // 6. Peer-to-peer networks
        6 => 938,
        // 7. Online learning and MOOCs
        7 => 937,
        // 8. Online marketplaces and noticeboards
        8 => 934,
        // 9. Big data
        9 => 72,
        // 10. Sensors and internet of things
        10 => 78,
        // 11. Geotagging, GPS and GIS
        11 => 940,
        // 12. Digital fabrication and 3D-printing
        12 => 932,
        // 13. Wearables and personal monitoring
        13 => 930,
        // 14. AI and machine learning
        14 => 1631,
        // 15. Digital democracy tools
        15 => 936,
        // 16. Blockchain and distributed networks
        16 => 1014,
        // 17. Robotics
        17 => 180,
        // 18. VR and AR
        18 => 1632,
    ];

    public function __construct($args)
    {
        if (!$args[2])
            die('Please provide filename to be imported');

        $this->file = $args[2];
        if (!file_exists($this->file))
            die('Invalid file path');
    }

    public function exec()
    {
        dump('Importing projects...');
        try {
            $csv = Reader::createFromPath($this->file, 'r');
            $csv->setHeaderOffset(0); //set the CSV header offset
            $stmt = (new Statement());
            $records = $stmt->process($csv);
            foreach ($records as $record)
                $this->importRow($record);

            dump('File successfully imported');
        } catch (ErrorHandler $e) {
            dump('Could not import file');
            dump($e->getErrors());
        } catch (\Exception $e) {
            dump('Could not import file');
            dump($e->getMessage());
            dump($e->getTrace());
        }
    }

    private function importRow($data)
    {
        if ($data['Project name'] === '')
            return;

        if (!$data['Short description'])
            $data['Short description'] = '.';

        /*
        /** @var \Models\Project $projectModel * /
        $projectModel = \Models\Project::firstOrCreate([
            \Models\Project::Name => $data['Project name']
        ], [
            \Models\Project::OwnerId => 1
        ]);

        $project = $projectRepo->getById($projectModel->getId());
        dump("Updating " . $data['Project name'] . ' : ' . $projectModel->getId());
        */

        $executor = (new UserRepo())->getById(1);
        $projectRepo = new ProjectRepo();
        try {
            $project = $projectRepo->getByName($data['Project name']);
            dump("Updating " . $data['Project name']);
        } catch (NotFound $e) {
            $project = new Project();
            $project->setName($data['Project name']);
            $project->setOwner($executor);
            $projectRepo->insert($project);
            dump("Created " . $data['Project name']);
        }

        $exec = new UpdateProject();
        $exec->data()->executor = $executor;
        $exec->data()->project = $project;
        $exec->data()->shortDescription = $data['Short description'];
        $exec->data()->description = $data['Long description'];
        $exec->data()->url = $data['Website'];
        if ($data['Start date'])
            $exec->data()->startDate = date('Y-m-d', strtotime($data['Start date']));
        if ($data['End date'])
            $exec->data()->endDate = date('Y-m-d', strtotime($data['End date']));

        if ($data['Country']) {
            if ($data['Country'] === 'UK')
                $data['Country'] = 'United Kingdom';
            if ($data['Country'] === 'Great Britain')
                $data['Country'] = 'United Kingdom';
            if ($data['Country'] === 'Neatherlands')
                $data['Country'] = 'Netherlands';
            if ($data['Country'] === '-')
                $data['Country'] = '';
            if ($data['Country'] === 'Worldwide')
                $data['Country'] = '';

            if ($data['Country']) {
                try {
                    $country = (new CountryRepo())->getByName($data['Country']);
                } catch (NotFound $e) {
                    dump($e->getMessage());
                    die('Could not find country ' . $data['Country']);
                }

                $exec->data()->countryID = $country->getId();
                if ($data['Region'])
                    $exec->data()->region = $data['Region'];
            }
        }

        if ($data['Support tags']) {
            $supportTags = explode(',', $data['Support tags']);
            $supportTags = array_map('trim', $supportTags);
            $supportTags = array_map(function ($id) use ($data) {
                if (!$this->supportTags[$id])
                    die('Could not find supporting tag: ' . $id . ' for ' . $data['Project name']);
                return $this->supportTags[$id];
            }, $supportTags);
            $impactTagRepo = new ImpactTagRepo();
            $supportTags = array_map(function ($id) use ($data, $impactTagRepo) {
                return $impactTagRepo->getById($id)->getName();
            }, $supportTags);
            $exec->data()->areasOfImpact = $supportTags;
        }

        if ($data['Focus']) {
            $focusTags = explode(',', $data['Focus']);
            $focusTags = array_map('trim', $focusTags);
            $focusTags = array_map(function ($id) use ($data) {
                if (!$this->focusTags[$id])
                    die('Could not find focus tag: ' . $id . ' for ' . $data['Project name']);
                return $this->focusTags[$id];
            }, $focusTags);
            $projectDsiFocusTagRepo = new DsiFocusTagRepo();
            $focusTags = array_map(function ($id) use ($data, $projectDsiFocusTagRepo) {
                return $projectDsiFocusTagRepo->getById($id)->getName();
            }, $focusTags);
            $exec->data()->focusTags = $focusTags;
        }

        if ($data['Technology']) {
            $technologyTags = explode(',', $data['Technology']);
            $technologyTags = array_map('trim', $technologyTags);
            $technologyTags = array_filter($technologyTags);
            $technologyTags = array_map(function ($id) use ($data) {
                if (!$this->technologyTags[$id])
                    die('Could not find technology tag: ' . $id . ' for ' . $data['Project name']);
                return $this->technologyTags[$id];
            }, $technologyTags);
            $impactTagRepo = new ImpactTagRepo();
            $technologyTags = array_map(function ($id) use ($data, $impactTagRepo) {
                return $impactTagRepo->getById($id)->getName();
            }, $technologyTags);
            $exec->data()->technologyTags = $technologyTags;
        }

        if ($data['Linked Organisation IDs']) {
            $organisationIDs = [];
            $organisationNames = preg_split("/(,|\n)/", $data['Linked Organisation IDs']);;
            $organisationNames = array_map('trim', $organisationNames);
            $organisationNames = array_filter($organisationNames);
            $organisationRepo = new OrganisationRepo();
            foreach ($organisationNames AS $organisationName) {
                try {
                    $organisation = $organisationRepo->getByName($organisationName);
                    $organisationIDs[] = $organisation->getId();
                    // dump($organisation->getId() . " $organisationName");
                } catch (NotFound $e) {
                    // dump("Not found $organisationName");
                    $orgExec = new CreateOrganisation();
                    $orgExec->setOwner($executor);
                    $orgExec->setName($organisationName);
                    $orgExec->exec();
                    $organisationIDs[] = $orgExec->getOrganisation()->getId();
                }
            }
            $exec->data()->organisations = $organisationIDs;
        }

        $exec->exec();
    }
}