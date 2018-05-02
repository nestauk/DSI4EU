<?php

namespace Services;

class View
{
    static function render(string $view, array $data = [])
    {
        foreach ($data AS $index => $datum) {
            ${$index} = $datum;
        }

        require($view);

        return false;
    }
}