<?php
/**
 * Created by PhpStorm.
 * User: apandele
 * Date: 25/04/2016
 * Time: 16:13
 */

function getIP()
{
    return "{$_SERVER['REMOTE_ADDR']}" . (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? '/' . $_SERVER['HTTP_X_FORWARDED_FOR'] : '');
}

function go_to($url = NULL, $perm = 302)
{
    if ($url == NULL)
        $url = (isSecureConnection() ? 'https' : 'http') . "://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}";

    header('Content-Type: text/html; charset=UTF-8');

    if ($perm == 301)
        header('HTTP/1.1 301 Moved Permanently');

    header("Location:$url", TRUE, $perm);
    die();
}

function isSecureConnection()
{
    return
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || $_SERVER['SERVER_PORT'] == 443;
}

function show_input($text)
{
    return @htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8');
}

function pr($text)
{
    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
    echo '<pre>';
    print_r($text);
    echo "</pre>\n";
}

function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function __($text)
{
    return \DSI\Service\Translate::getTranslation($text);
}

function _e($text)
{
    echo \DSI\Service\Translate::getTranslation($text);
}

function _ehtml($text)
{
    echo show_input(\DSI\Service\Translate::getTranslation($text));
}

function _html($text)
{
    return show_input(\DSI\Service\Translate::getTranslation($text));
}

spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../src/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require($file);
        return true;
    }

    return false;
});