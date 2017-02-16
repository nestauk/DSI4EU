<?php

namespace DSI\Service;

class JsModules
{
    /** @var bool */
    private static $tinyMCE;

    /** @var bool */
    private static $translations;

    /**
     * @return boolean
     */
    public static function hasTinyMCE(): bool
    {
        return (bool)self::$tinyMCE;
    }

    /**
     * @param boolean $tinyMCE
     */
    public static function setTinyMCE(bool $tinyMCE)
    {
        self::$tinyMCE = (bool)$tinyMCE;
    }

    /**
     * @return bool
     */
    public static function hasTranslations(): bool
    {
        return (bool)self::$translations;
    }

    /**
     * @param bool $translations
     */
    public static function setTranslations(bool $translations)
    {
        self::$translations = $translations;
    }
}