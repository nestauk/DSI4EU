<?php
require __DIR__ . '/src/config.php';

class CliRouter
{
    public function exec($args)
    {
        if (!isset($args[1])) {
            echo 'send-cached-emails' . PHP_EOL;
            echo 'import-user-project-organisation-links' . PHP_EOL;
            echo 'import-organisation-urls' . PHP_EOL;
            echo 'cities-to-geolocation' . PHP_EOL;
            echo 'update-organisations-partners-count' . PHP_EOL;
            echo 'update-project-tags' . PHP_EOL;
            return;
        }

        if ($args[1] == 'send-cached-emails') {
            $this->sendCachedEmails();
        } elseif ($args[1] == 'import-user-project-organisation-links') {
            $this->importUserProjectOrganisationLink();
        } elseif ($args[1] == 'import-organisation-urls') {
            $this->importOrganisationURLs();
        } elseif ($args[1] == 'cities-to-geolocation') {
            $this->citiesToGeolocation();
        } elseif ($args[1] == 'update-organisations-partners-count') {
            $this->updateOrganisationsPartnersCount();
        } elseif ($args[1] == 'update-project-tags') {
            $this->updateProjectTags($args);
        } else {
            echo 'Invalid argument';
        }
    }

    private function sendCachedEmails()
    {
        $command = new \DSI\Controller\SendCachedEmailsController();
        $command->exec();
    }

    private function importUserProjectOrganisationLink()
    {
        $command = new \DSI\Controller\ImportUserProjectOrganisationLinkController();
        $command->exec();
    }

    private function importOrganisationURLs()
    {
        $command = new \DSI\Controller\ImportOrgLinksController();
        $command->exec();
    }

    private function citiesToGeolocation()
    {
        $command = new \DSI\Controller\CitiesToGeolocationController();
        $command->exec();
    }

    private function updateOrganisationsPartnersCount()
    {
        $command = new \DSI\Controller\UpdateOrganisationsPartnersCountController();
        $command->exec();
    }

    private function updateProjectTags(array $args)
    {
        $command = new \DSI\Controller\UpdateProjectTagsController();
        $command->setArgs($args);
        $command->exec();
    }
}

$router = new CliRouter();
$router->exec($argv);