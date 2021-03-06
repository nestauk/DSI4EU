<?php

namespace Services;

class View
{
    static $pageTitle;
    static $pageDescription;

    static function render(string $view, array $data = [])
    {
        /** @var $urlHandler URL */

        foreach ($data AS $index => $datum) {
            ${$index} = $datum;
        }

        if (!$urlHandler)
            $urlHandler = new URL();

        require($view);

        return true;
    }

    static function prepare(string $view, array $data = [])
    {
        ob_start();
        self::render($view, $data);
        return ob_get_clean();
    }

    static function setPageTitle($title)
    {
        self::$pageTitle = $title;
    }

    static function getPageTitleOr($default)
    {
        return self::$pageTitle ? self::$pageTitle : $default;
    }

    static function setPageDescription($description)
    {
        self::$pageDescription = substr($description, 0, 255);
    }

    static function getPageDescription()
    {
        return self::$pageDescription;
    }
}