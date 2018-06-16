<?php

namespace Services;

class View
{
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
}