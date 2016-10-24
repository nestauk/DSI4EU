<?php

namespace DSI\Service;

class JsModules
{
    /** @var bool */
    private static $tinyMCE;

    /**
     * @return boolean
     */
    public static function hasTinyMCE()
    {
        return (bool)self::$tinyMCE;
    }

    /**
     * @param boolean $tinyMCE
     */
    public static function setTinyMCE($tinyMCE)
    {
        self::$tinyMCE = (bool)$tinyMCE;
    }
}