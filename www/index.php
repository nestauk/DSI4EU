<?php

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../routes/web.php';

if (!$_POST) {
    $postData = file_get_contents("php://input");
    if ($postData) {
        $_POST = json_decode($postData, true);
    } else {
        $_POST = [];
    }
}

$pageURL =
    $_SERVER['PATH_INFO'] ??
    explode('?', $_SERVER['REQUEST_URI'], 2)[0] ??
    '/';
// pr($pageURL);

if (substr($pageURL, 0, strlen(SITE_RELATIVE_PATH)) == SITE_RELATIVE_PATH) {
    $pageURL = substr($pageURL, strlen(SITE_RELATIVE_PATH));
}

$router = new Router();
if (MUST_USE_HTTPS)
    $router->forceHTTPS();

$router->exec($pageURL);