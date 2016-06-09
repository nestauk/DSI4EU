<?php
require __DIR__ . '/header.php';
/** @var $stories \DSI\Entity\Story[] */
?>

    <div class="container-wide stories">
        <div class="filter-block">
            <div class="w-row">
                <div class="w-col w-col-9 w-col-stack">
                    <div class="w-row">
                        <div class="w-col w-col-2">
                            <a class="w-button dsi-button top-filter" href="#">All stories</a>
                        </div>
                        <div class="w-col w-col-2">
                            <a class="w-button dsi-button top-filter" href="#">News</a>
                        </div>
                        <div class="w-col w-col-2">
                            <a class="w-button dsi-button top-filter" href="#">Events</a>
                        </div>
                        <div class="w-col w-col-2">
                            <a class="w-button dsi-button top-filter" href="#">Case studies</a>
                        </div>
                        <div class="w-col w-col-2">
                            <a class="w-button dsi-button top-filter published-stories" href="#">Published</a>
                        </div>
                        <div class="w-col w-col-2">
                            <a class="w-button dsi-button top-filter unpublished" href="#">Unpublished</a>
                        </div>
                    </div>
                </div>
                <div class="w-col w-col-3 w-col-stack w-clearfix">
                    <a class="w-button dsi-button top-filter add-new-story"
                       href="<?php echo \DSI\Service\URL::addStory() ?>">Add new story +</a>
                </div>
            </div>
        </div>
        <div class="w-row hp-post-row">
            <?php foreach ($stories AS $story) { ?>
                <div class="w-col w-col-4 w-col-stack">
                    <div class="story-grid-card" data-ix="admin-edit">
                        <a class="w-inline-block hp-post-link"
                           href="<?php echo \DSI\Service\URL::story($story->getId(), $story->getTitle()) ?>">
                            <div class="hp-post-img"
                                 style="background-image: url('<?php echo SITE_RELATIVE_PATH . '/images/stories/feat/' . $story->getFeaturedImage() ?>');"></div>
                            <div class="w-clearfix hp-post">
                                <div class="hp-post-meta category">News</div>
                                <div class="hp-post-meta hp-date"><?php echo $story->getDatePublished('jS F Y') ?></div>
                                <h3 class="hp-post-h3"><?php echo show_input($story->getTitle()) ?></h3>
                                <p class="hp-post-p"><?php echo substr(strip_tags($story->getContent()), 0, 150) ?></p>
                            </div>
                        </a>
                        <div class="w-clearfix story-admin-buttons">
                            <a class="w-button dsi-button button-bar-button"
                               href="<?php echo \DSI\Service\URL::storyEdit($story->getId()) ?>">Edit Story</a>
                            <a class="w-button dsi-button button-bar-button delete-button" href="#">Delete story</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>