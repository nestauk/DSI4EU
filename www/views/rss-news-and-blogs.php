<?php
/** @var $urlHandler \DSI\Service\URL */
/** @var $stories \DSI\Entity\Story[] */
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>Digital Social Innovation :: News and Blogs</title>
        <link><?php echo $urlHandler->fullUrl($urlHandler->rssNewsBlogs()) ?></link>
        <description>Recent news and blog posts on Digital Social Innovation</description>
        <generator>PHP</generator>
        <language>en</language>
        <lastBuildDate><?php echo date('r') ?></lastBuildDate>
        <atom:link href="<?php echo $urlHandler->fullUrl($urlHandler->rssNewsBlogs()) ?>" rel="self"
                   type="application/rss+xml"/>
        <?php foreach ($stories AS $story) { ?>
            <item>
                <title><?php echo show_input($story->getTitle()) ?></title>
                <link><?php echo $urlHandler->fullUrl($urlHandler->blogPost($story)) ?></link>
                <pubDate><?php echo $story->getDatePublished('r') ?></pubDate>
                <guid>dsi-news-<?php echo $story->getId() ?></guid>
                <description><?php echo show_input($story->getCardShortDescription()) ?></description>
            </item>
        <?php } ?>
    </channel>
</rss>