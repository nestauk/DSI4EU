<?php

namespace DSI\Service;
use Services\App;

class DataVis
{
    public static function getUrl()
    {
        if (in_array(App::getEnv(), [APP::DEV, APP::TEST]))
            return 'http://dsitest.todo.to.it/viz/';
        else
            return 'https://digitalsocial.eu/viz/';
    }
}