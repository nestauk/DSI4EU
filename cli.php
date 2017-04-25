<?php
use \DSI\Service\Translate;
use \DSI\Entity\Translation;

require __DIR__ . '/src/config.php';

class CliRouter
{
    public function exec($args)
    {
        Translate::setCurrentLang(Translation::DEFAULT_LANGUAGE);

        if (!isset($args[1])) {
            echo 'cities-to-geolocation' . PHP_EOL;
            echo 'import-organisations' . PHP_EOL;
            echo 'import-organisation-urls' . PHP_EOL;
            echo 'import-projects' . PHP_EOL;
            echo 'import-user-project-organisation-links' . PHP_EOL;
            echo 'send-cached-emails' . PHP_EOL;
            echo 'update-organisations-partners-count' . PHP_EOL;
            echo 'update-project-tags' . PHP_EOL;
            echo 'update-project-tech-tags' . PHP_EOL;
            return;
        }


        if ($args[1] == 'cities-to-geolocation') {
            $this->citiesToGeolocation();
        } elseif ($args[1] == 'import-user-project-organisation-links') {
            $this->importUserProjectOrganisationLink();
        } elseif ($args[1] == 'import-organisations') {
            $this->importOrganisations();
        } elseif ($args[1] == 'import-organisation-urls') {
            $this->importOrganisationURLs();
        } elseif ($args[1] == 'import-projects') {
            $this->importProjects();
        } elseif ($args[1] == 'send-cached-emails') {
            $this->sendCachedEmails();
        } elseif ($args[1] == 'update-organisations-partners-count') {
            $this->updateOrganisationsPartnersCount();
        } elseif ($args[1] == 'update-project-tags') {
            $this->updateProjectTags($args);
        } elseif ($args[1] == 'update-project-tech-tags') {
            $this->updateProjectTechTags($args);
        } else {
            echo 'Invalid argument';
        }
    }

    private function sendCachedEmails()
    {
        $command = new \DSI\Controller\CLI\SendCachedEmailsController();
        $command->exec();
    }

    private function importUserProjectOrganisationLink()
    {
        $command = new \DSI\Controller\CLI\ImportUserProjectOrganisationLinkController();
        $command->exec();
    }

    private function importOrganisations()
    {
        $command = new \DSI\Controller\CLI\ImportOrganisationsController();
        $command->exec();
    }

    private function importProjects()
    {
        $command = new \DSI\Controller\CLI\importProjectsController();
        $command->exec();
    }

    private function importOrganisationURLs()
    {
        $command = new \DSI\Controller\CLI\ImportOrgLinksController();
        $command->exec();
    }

    private function citiesToGeolocation()
    {
        $command = new \DSI\Controller\CLI\CitiesToGeolocationController();
        $command->exec();
    }

    private function updateOrganisationsPartnersCount()
    {
        $command = new \DSI\Controller\CLI\UpdateOrganisationsPartnersCountController();
        $command->exec();
    }

    private function updateProjectTags(array $args)
    {
        $command = new \DSI\Controller\CLI\UpdateProjectTagsController();
        $command->setArgs($args);
        $command->exec();
    }

    private function updateProjectTechTags(array $args)
    {
        $command = new \DSI\Controller\CLI\UpdateProjectTechTagsController();
        $command->setArgs($args);
        $command->exec();
    }
}

$router = new CliRouter();
$router->exec($argv);