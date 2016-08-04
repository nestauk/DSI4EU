<?php
require __DIR__ . '/header.php';
/** @var $story \DSI\Entity\Story */
/** @var $author \DSI\Entity\User */
/** @var $stories \DSI\Entity\Story[] */
?>

    <div class="single-post-hero"
         style="background-image: linear-gradient(180deg, rgba(14, 23, 41, .83) 26%, rgba(58, 47, 70, .7)), url('<?php echo \DSI\Entity\Image::STORY_MAIN_IMAGE_URL . $story->getMainImage() ?>');">
        <div class="container-wide post-hero">
            <h1 class="post-hero-title"><?php echo show_input($story->getTitle()) ?></h1>
            <div class="post-single-date"><?php echo $story->getDatePublished('jS F Y') ?></div>
            <div class="breadcrumb-container">
                <div class="bread-crumbs w-clearfix">
                    <a class="breadcrumb-root w-inline-block" href="<?php echo \DSI\Service\URL::blogPosts() ?>">
                        <div class="breadcrumb-link">Stories</div>
                        <div class="arrow-right"></div>
                    </a>
                    <a class="breadcrumb-root path w-inline-block" href="#">
                        <div class="arrow-bottom-left"></div>
                        <div class="arrow-top-left"></div>
                        <div class="breadcrumb-link">
                            <?php
                            echo show_input(substr($story->getTitle(), 0, 35));
                            if (strlen($story->getTitle()) > 35) echo '...';
                            ?>
                        </div>
                        <div class="arrow-right"></div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="w-section single-post-detail">
        <div class="container-wide single-post-content">
            <div class="author-details">
                <div class="w-row">
                    <div class="w-col w-col-9">
                        <div class="w-clearfix single-post-author">
                            <div class="single-post-author-img"
                                 style="background-image: url('<?php echo SITE_RELATIVE_PATH . '/images/users/profile/' . $author->getProfilePicOrDefault() ?>');"></div>
                            <div class="single-post-author-name">Written
                                by <?php echo show_input($author->getFirstName()) ?> <?php echo show_input($author->getLastName()) ?></div>
                        </div>
                    </div>
                    <?php if ($category = $story->getStoryCategory()) { ?>
                        <div class="w-col w-col-3">
                            <div class="w-clearfix">
                                <div class="single-post-category">
                                    <?php echo $category->getName() ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="story">
        <div class="container-wide">
            <div class="w-row">
                <div class="w-col w-col-7">
                    <div class="single-post-content-card">
                        <p class="post-p"><?php echo $story->getContent() ?></p>
                    </div>
                </div>
                <div class="w-col w-col-5">
                    <div class="sidebar-content-card">
                        <h2 class="sidebar-h2">Latest posts</h2>
                        <ul class="w-list-unstyled">
                            <?php foreach ($stories AS $story) { ?>
                                <li class="latest-post-li-enc">
                                    <a class="w-inline-block latest-post-li"
                                       href="<?php echo \DSI\Service\URL::blogPost($story) ?>">
                                        <div class="w-row">
                                            <div class="w-col w-col-4 w-col-stack">
                                                <div
                                                    class="latest-post-date"><?php echo $story->getDatePublished('jS F') ?></div>
                                                <div
                                                    class="latest-post-year"><?php echo $story->getDatePublished('Y') ?></div>
                                            </div>
                                            <div class="w-col w-col-8 w-col-stack">
                                                <div class="latest-post-title">
                                                    <?php echo show_input($story->getTitle()) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>