<?php
/** @var $urlHandler Services\URL */
/** @var $events \DSI\Entity\Event[] */
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>Digital Social Innovation :: Events</title>
        <link><?php echo $urlHandler->fullUrl($urlHandler->rssEvents()) ?></link>
        <description>Recent Events on Digital Social Innovation</description>
        <generator>PHP</generator>
        <language>en</language>
        <lastBuildDate><?php echo date('r') ?></lastBuildDate>
        <atom:link href="<?php echo $urlHandler->fullUrl($urlHandler->rssEvents()) ?>" rel="self"
                   type="application/rss+xml"/>
        <?php foreach ($events AS $event) { ?>
            <item>
                <title><?php echo show_input($event->getTitle()) ?></title>
                <link><?php echo $urlHandler->fullUrl($urlHandler->event($event)) ?></link>
                <pubDate><?php echo $event->getStartDate('r') ?></pubDate>
                <guid>dsi-event-<?php echo $event->getId() ?></guid>
                <description><?php echo show_input($event->getShortDescription()) ?></description>
            </item>
        <?php } ?>
    </channel>
</rss>