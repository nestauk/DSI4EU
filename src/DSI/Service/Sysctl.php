<?php

namespace DSI\Service;

use Services\Assets;

class Sysctl
{
    public static function echoVersion()
    {
        echo 'v=' . Assets::version();
    }
}