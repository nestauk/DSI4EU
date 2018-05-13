<?php

namespace DSI\Service;

class SocialShare
{
    /** @var  string */
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function renderHtml()
    {
        $url = $this->url;
        require __DIR__ . '/../../Views/partialViews/socialShare.php';
    }
}