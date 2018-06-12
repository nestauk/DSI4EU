<?php

namespace DSI\Service;

class JsModules
{
    /** @var bool */
    private static $tinyMCE,
        $jqueryUI;

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
     * @return boolean
     */
    public static function hasJqueryUI(): bool
    {
        return (bool)self::$jqueryUI;
    }

    /**
     * @param boolean $jqueryUI
     */
    public static function setJqueryUI(bool $jqueryUI)
    {
        self::$jqueryUI= (bool)$jqueryUI;
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