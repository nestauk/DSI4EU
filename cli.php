<?php
require __DIR__ . '/src/config.php';

class CliRouter
{
    public function exec($args)
    {
        if ($args[1] == 'send-cached-emails') {
            $this->sendCachedEmailsCommand();
        }
    }

    private function sendCachedEmailsCommand()
    {
        $command = new \DSI\Controller\SendCachedEmailsController();
        $command->exec();
    }
}

$router = new CliRouter();
return $router->exec($argv);