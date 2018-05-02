<?php
if (!isset($urlHandler))
    $urlHandler = new Services\URL();

echo 'User-agent: *' . PHP_EOL;
echo 'Allow: /' . PHP_EOL;
echo PHP_EOL;
echo 'Sitemap: https://' . SITE_DOMAIN . $urlHandler->sitemapXML() . PHP_EOL;

foreach (\DSI\Entity\Translation::LANGUAGES AS $language) {
    //$localUrlHandler = new Services\URL();
    //$localUrlHandler->setCurrentLanguage($language);
    //echo 'Sitemap: https://' . SITE_DOMAIN . $localUrlHandler->sitemapXML() . PHP_EOL;
}