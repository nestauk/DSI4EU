<?php
require __DIR__ . '/header.php';
/** @var $story \DSI\Entity\Story */
/** @var $author \DSI\Entity\User */
?>
    <div class="w-section single-post-hero"
         style="background-image: linear-gradient(180deg, rgba(14, 23, 41, .83) 26%, rgba(58, 47, 70, .7)), url('<?php echo SITE_RELATIVE_PATH ?>/images/stories/main/<?php echo $story->getMainImage() ?>');">
        <div class="container-wide post-hero">
            <h1 class="post-hero-title"><?php echo show_input($story->getTitle()) ?></h1>
            <div class="post-single-date"><?php echo $story->getDatePublished('jS F Y') ?></div>
            <div class="w-clearfix bread-crumbs">
                <a class="w-inline-block breadcrumb-root" href="<?php echo \DSI\Service\URL::stories() ?>">
                    <div class="breadcrumb-link">Stories</div>
                    <div class="arrow-right"></div>
                </a>
                <a class="w-inline-block breadcrumb-root path" href="#">
                    <div class="arrow-bottom-left"></div>
                    <div class="arrow-top-left"></div>
                    <div class="breadcrumb-link">
                        <?php echo substr(show_input($story->getTitle()), 0, 35) ?>
                        <?php echo strlen($story->getTitle()) > 35 ? '...' : '' ?>
                    </div>
                    <div class="arrow-right"></div>
                </a>
            </div>
        </div>
    </div>

<?php require(__DIR__ . '/partialViews/search-results.php'); ?>

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
                            <div>
                                <div class="single-post-category">
                                    <?php echo $category->getName() ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
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
                            <li class="latest-post-li-enc">
                                <a class="w-inline-block latest-post-li" href="#">
                                    <div class="w-row">
                                        <div class="w-col w-col-4 w-col-stack">
                                            <div class="latest-post-date">14Th July</div>
                                            <div class="latest-post-year">2015</div>
                                        </div>
                                        <div class="w-col w-col-8 w-col-stack">
                                            <div class="latest-post-title">In a rapidly changing world, we are all
                                                designers
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="w-inline-block latest-post-li" href="#">
                                    <div class="w-row">
                                        <div class="w-col w-col-4 w-col-stack">
                                            <div class="latest-post-date">21St September</div>
                                            <div class="latest-post-year">2015</div>
                                        </div>
                                        <div class="w-col w-col-8 w-col-stack">
                                            <div class="latest-post-title">Digital Social Innovation, a relatively new
                                                concept
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="w-inline-block latest-post-li" href="#">
                                    <div class="w-row">
                                        <div class="w-col w-col-4 w-col-stack">
                                            <div class="latest-post-date">14Th July</div>
                                            <div class="latest-post-year">2015</div>
                                        </div>
                                        <div class="w-col w-col-8 w-col-stack">
                                            <div class="latest-post-title">Here is an example of a really long post
                                                title. This should be tested as it's quite likely that there will be a
                                                really long post title
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="w-inline-block latest-post-li" href="#">
                                    <div class="w-row">
                                        <div class="w-col w-col-4 w-col-stack">
                                            <div class="latest-post-date">28Th October</div>
                                            <div class="latest-post-year">2014</div>
                                        </div>
                                        <div class="w-col w-col-8 w-col-stack">
                                            <div class="latest-post-title">In a rapidly changing world, we are all
                                                designers
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="w-inline-block latest-post-li" href="#">
                                    <div class="w-row">
                                        <div class="w-col w-col-4 w-col-stack">
                                            <div class="latest-post-date">14Th July</div>
                                            <div class="latest-post-year">2012</div>
                                        </div>
                                        <div class="w-col w-col-8 w-col-stack">
                                            <div class="latest-post-title">In a rapidly changing world, we are all
                                                designers
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>