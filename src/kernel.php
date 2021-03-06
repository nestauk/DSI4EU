<?php

error_reporting(E_ALL ^ E_NOTICE);

use \Services\App;

require __DIR__ . '/functions.php';
require __DIR__ . '/exceptions.php';
require __DIR__ . '/../vendor/autoload.php';

class Kernel
{
    public static function setConfig($config)
    {
        define('SITE_DOMAIN', $config['site-domain']);
        define('SITE_RELATIVE_PATH', $config['site-relative-path']);

        define('MUST_USE_HTTPS', $config['must-use-https']);

        App::setEnv($config['env']);
        App::setCanCreateProjects($config['can-create-projects']);
        App::setWaitingApprovalEmailAddress($config['waiting-approval-email-address']);

        if (App::getEnv() !== App::TEST) {
            session_set_cookie_params(
                $lifetime = 0,
                $path = SITE_RELATIVE_PATH . '/',
                $domain = "",
                $secure = MUST_USE_HTTPS ? true : false,
                $httponly = true
            );
            session_start();
        }

        \DSI\Repository\OrganisationRepoInAPC::setApcKey($config['apc-keys']['organisations']);

        \DSI\Repository\ProjectRepoInAPC::setApcKey($config['apc-keys']['projects']);

        if (App::getEnv() === App::TEST)
            \DSI\Service\SQL::setCredentials($config['mysql-test']);
        else
            \DSI\Service\SQL::setCredentials($config['mysql']);

        \DSI\Service\FacebookLogin::setCredentials($config['api']['facebook']);

        \DSI\Service\GitHubLogin::setCredentials($config['api']['github']);

        \DSI\Service\GoogleLogin::setCredentials($config['api']['google']);

        \DSI\Service\TwitterLogin::setCredentials($config['api']['twitter']);

        \Services\DbService::init();
    }

    public static function loadConfigFile($file)
    {
        if (!file_exists($file)) {
            http_response_code(405);
            echo '<h1>Please make sure the config file exists and is readable</h1>';
            die();
        }
        return require $file;
    }
}