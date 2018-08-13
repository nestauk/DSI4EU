<?php

namespace Controllers\Console;

use DSI\Entity\Organisation;
use DSI\NotFound;
use DSI\Repository\CountryRepo;
use DSI\Repository\OrganisationRepo;
use DSI\Repository\ProjectRepo;
use DSI\Repository\UserRepo;
use DSI\Service\ErrorHandler;
use DSI\UseCase\CreateProject;
use DSI\UseCase\UpdateOrganisation;
use League\Csv\Reader;
use League\Csv\Statement;

class ImportOrganisations_2018_07_Controller
{
    private $file = '';

    private $sizes = [
        // 0-5 people
        1 => 2,
        // 6-10 people
        2 => 4,
        // 11-25 people
        3 => 1,
        // 26-50 people
        4 => 3,
        // 51-100 people
        5 => 6,
        // 101-500 people
        6 => 7,
        // 501-1000 people
        7 => 8,
        // over-1000 people
        8 => 5,
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
        dump('Importing organisations...');
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
        if ($data['Name'] === '')
            return;

        if (!$data['Short description'])
            $data['Short description'] = '.';

        $executor = (new UserRepo())->getById(1);
        $organisationRepo = new OrganisationRepo();
        try {
            $organisation = $organisationRepo->getByName($data['Name']);
            dump("Updating " . $data['Name']);
        } catch (NotFound $e) {
            $organisation = new Organisation();
            $organisation->setName($data['Name']);
            $organisation->setOwner($executor);
            $organisationRepo->insert($organisation);
            dump("Created " . $data['Name']);
        }

        $exec = new UpdateOrganisation();
        $exec->data()->executor = $executor;
        $exec->data()->organisation = $organisation;
        $exec->data()->shortDescription = $data['Short description'];
        $exec->data()->description = $data['Long description'];
        $exec->data()->url = $data['Website'];
        $exec->data()->address = $data['Address'];
        $exec->data()->organisationTypeId = (int)$data['Organisation type'];

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

        if ($data['Organisation size']) {
            if (!$this->sizes[(int)$data['Organisation size']])
                die("Invalid organisation size: {$data['Organisation size']} for {$data['Name']}");
            $exec->data()->organisationSizeId = $this->sizes[(int)$data['Organisation size']];
        }

        if ($data['Tags']) {
            $tags = preg_split("/(,|\n)/", $data['Tags']);;
            $tags = array_map('trim', $tags);
            $tags = array_filter($tags);
            $exec->data()->tags = $tags;
        }

        if ($data['Network tags']) {
            $tags = preg_split("/(,|\n)/", $data['Network tags']);;
            $tags = array_map('trim', $tags);
            $tags = array_filter($tags);
            $exec->data()->networkTags = $tags;
        }

        if ($data['Linked project IDs']) {
            $projectIDs = [];
            $projectNames = preg_split("/(,|\n)/", $data['Linked project IDs']);;
            $projectNames = array_map('trim', $projectNames);
            $projectNames = array_filter($projectNames);
            $projectRepo = new ProjectRepo();

            foreach ($projectNames AS $projectName) {
                try {
                    $project = $projectRepo->getByName($projectName);
                    $projectIDs[] = $project->getId();
                    dump(" - project: " . $project->getId() . ": $projectName");
                } catch (NotFound $e) {
                    dump(" - project: Not found: $projectName");
                    $projExec = new CreateProject();
                    $projExec->setOwner($executor);
                    $projExec->setName($projectName);
                    $projExec->exec();
                    $projectIDs[] = $projExec->getProject()->getId();
                }
            }
            $exec->data()->projects = $projectIDs;
        }

        $exec->exec();
    }
}