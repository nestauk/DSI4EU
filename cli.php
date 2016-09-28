<?php
require __DIR__ . '/src/config.php';

class CliRouter
{
    public function exec($args)
    {
        if(!isset($args[1])){
            echo 'send-cached-emails' . PHP_EOL;
            echo 'import-user-project-organisation-links' . PHP_EOL;
            return;
        }

        if ($args[1] == 'send-cached-emails') {
            $this->sendCachedEmails();
        } elseif ($args[1] == 'import-user-project-organisation-links') {
            $this->importUserProjectOrganisationLink();
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
}

$router = new CliRouter();
return $router->exec($argv);