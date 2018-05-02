<?php
/** @var $urlHandler Services\URL */
/** @var $fundings \DSI\Entity\Funding[] */
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>Digital Social Innovation :: Funding Opportunities</title>
        <link><?php echo $urlHandler->fullUrl($urlHandler->rssFundingOpportunities()) ?></link>
        <description>Recent Funding Opportunities on Digital Social Innovation</description>
        <generator>PHP</generator>
        <language>en</language>
        <lastBuildDate><?php echo date('r') ?></lastBuildDate>
        <atom:link href="<?php echo $urlHandler->fullUrl($urlHandler->rssEvents()) ?>" rel="self"
                   type="application/rss+xml"/>
        <?php foreach ($fundings AS $funding) { ?>
            <item>
                <title><?php echo show_input($funding->getTitle()) ?></title>
                <link><?php echo $urlHandler->fullUrl($urlHandler->funding()) ?></link>
                <pubDate><?php echo $funding->getTimeCreated('r') ?></pubDate>
                <guid>dsi-funding-<?php echo $funding->getId() ?></guid>
                <description><?php echo show_input($funding->getDescription()) ?></description>
            </item>
        <?php } ?>
    </channel>
</rss>