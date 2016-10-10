<?php
require __DIR__ . '/header.php';
/** @var $userCanAddStory bool */
/** @var $urlHandler \DSI\Service\URL */
?>

    <style>
        .animate.ng-enter,
        .animate.ng-leave {
            -webkit-transition: 100ms cubic-bezier(0.250, 0.250, 0.750, 0.750) all;
            -moz-transition: 100ms cubic-bezier(0.250, 0.250, 0.750, 0.750) all;
            -ms-transition: 100ms cubic-bezier(0.250, 0.250, 0.750, 0.750) all;
            -o-transition: 100ms cubic-bezier(0.250, 0.250, 0.750, 0.750) all;
            transition: 100ms cubic-bezier(0.250, 0.250, 0.750, 0.750) all;
            position: relative;
            display: block;
            overflow: hidden;
            text-overflow: clip;
            white-space: nowrap;
        }

        .animate.ng-leave.animate.ng-leave-active,
        .animate.ng-enter {
            opacity: 0;
            width: 0px;
            height: 0px;
        }

        .animate.ng-enter.ng-enter-active,
        .animate.ng-leave {
            opacity: 1;
            width: 370px;
            height: 380px;
        }
    </style>

    <div ng-controller="StoriesController"
         data-jsonurl="<?php echo $urlHandler->blogPosts('json') ?>">

        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1">News &amp; Blogs</h1>
                    <p class="intro">Stay up to date with news, events, and blogs</p>
                </div>
                <?php if ($userCanAddStory) { ?>
                    <div class="sidebar w-col w-col-4 w-col-stack">
                        <h1 class="content-h1 side-bar-space-h1">Actions</h1>
                        <a class="sidebar-link" href="<?php echo $urlHandler->addStory() ?>"><span
                                class="green">- </span>Add new post</a>
                    </div>
                <?php } ?>

            </div>
            <div class="alphabet-selectors w-clearfix">
                <a class="alphabet-link post-filter w-inline-block" ng-click="searchCriteria = {}" href="#">
                    <div>All posts</div>
                </a>
                <a class="alphabet-link post-filter w-inline-block" ng-click="searchCriteria.catg = 3" href="#">
                    <div>News</div>
                </a>
                <a class="alphabet-link post-filter w-inline-block" ng-click="searchCriteria.catg = 2" href="#">
                    <div>Events</div>
                </a>
                <a class="alphabet-link post-filter w-inline-block" ng-click="searchCriteria.catg = 1" href="#">
                    <div>Blogs</div>
                </a>
                <?php if ($userCanAddStory) { ?>
                    <a class="alphabet-link post-filter w-inline-block" ng-click="searchCriteria.published = true"
                       href="#">
                        <div>Published</div>
                    </a>
                    <a class="alphabet-link post-filter w-inline-block" ng-click="searchCriteria.published = false"
                       href="#">
                        <div>Unpublished</div>
                    </a>
                <?php } ?>
            </div>
        </div>

        <div class="content-directory">
            <div class="container-wide stories">
                <div class="w-row hp-post-row" id="lisStories">
                    <div class="w-col w-col-4 w-col-stack <?php /*animate */ ?>"
                         ng-repeat="story in filtered = (stories | filter:criteriaMatch(criteria) ) | filter: recalculatePagination() | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
                        <div class="story-grid-card admin-edit">
                            <a class="w-inline-block hp-post-link"
                               href="{{story.url}}">
                                <div class="hp-post-img"
                                     style="background-image: url('<?php echo SITE_RELATIVE_PATH ?>/images/stories/feat/{{story.featuredImage}}')"></div>
                                <div class="w-clearfix hp-post">
                                    <div class="hp-post-meta category" ng-bind="story.category"></div>
                                    <div
                                        class="hp-post-meta hp-date" ng-bind="story.datePublished"></div>
                                    <h3 class="hp-post-h3" ng-bind="story.title"></h3>
                                    <p class="hp-post-p" ng-bind="story.content"></p>
                                </div>
                            </a>
                            <?php if ($userCanAddStory) { ?>
                                <div class="w-clearfix story-admin-buttons">
                                    <a class="w-button dsi-button button-bar-button"
                                       href="{{story.editUrl}}">Edit Story</a>
                                    <?php /* <a class="w-button dsi-button button-bar-button delete-button" href="#">Delete story</a> */ ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <pagination data-boundary-links="true" data-num-pages="noOfPages" ng-show="stories.length > entryLimit"
                            data-current-page="currentPage" max-size="maxSize" class="pagination"
                            data-previous-text="&laquo;" data-next-text="&raquo;"></pagination>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/StoriesController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <script type="text/javascript">
        $('#lisStories').on('mouseenter', '.admin-edit', function () {
            $('.story-admin-buttons', $(this)).show();
        }).on('mouseleave', '.admin-edit', function () {
            $('.story-admin-buttons', $(this)).hide();
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>