<?php
/** @var $links \DSI\Controller\SitemapLink[] */
?>
<?php echo '<?xml version = "1.0" encoding = "UTF-8"?>'; ?>
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($links as $link) { ?>
        <url>
            <loc><?php echo $link->loc ?></loc>
            <lastmod><?php echo $link->lastMod ?></lastmod>
            <changefreq><?php echo $link->changeFreq ?></changefreq>
            <priority><?php echo $link->priority ?></priority>
        </url>
    <?php } ?>
</urlset>