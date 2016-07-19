<?php
require __DIR__ . '/header.php';
/** @var $loggedInUser \DSI\Entity\User */
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

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/StoriesController.js"></script>

    <div ng-controller="StoriesController">
        <div class="w-section page-header stories-header">
            <div class="container-wide header">
                <h1 class="page-h1 light">Stories</h1>
                <div class="filter-block">
                    <div class="w-row">
                        <div class="w-col w-col-9 w-col-stack">
                            <div class="w-row">
                                <div class="w-col w-col-2">
                                    <a class="w-button dsi-button top-filter" ng-click="searchCriteria = {}" href="#">
                                        All stories</a>
                                </div>
                                <div class="w-col w-col-2">
                                    <a class="w-button dsi-button top-filter" ng-click="searchCriteria.catg = 3"
                                       href="#">
                                        News</a>
                                </div>
                                <div class="w-col w-col-2">
                                    <a class="w-button dsi-button top-filter" ng-click="searchCriteria.catg = 2"
                                       href="#">
                                        Events</a>
                                </div>
                                <div class="w-col w-col-2">
                                    <a class="w-button dsi-button top-filter" ng-click="searchCriteria.catg = 1"
                                       href="#">
                                        Case studies</a>
                                </div>
                                <?php if ($loggedInUser) { ?>
                                    <div class="w-col w-col-2">
                                        <a class="w-button dsi-button top-filter published-stories"
                                           ng-click="searchCriteria.published = true"
                                           href="#">Published</a>
                                    </div>
                                    <div class="w-col w-col-2">
                                        <a class="w-button dsi-button top-filter unpublished"
                                           ng-click="searchCriteria.published = false"
                                           href="#">Unpublished</a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="w-col w-col-3 w-col-stack w-clearfix">
                            <?php if ($loggedInUser) { ?>
                                <a class="w-button dsi-button top-filter add-new-story"
                                   href="<?php echo \DSI\Service\URL::addStory() ?>">
                                    Add new story +</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                        <?php if ($loggedInUser) { ?>
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

    <script type="text/javascript">
        $('#lisStories').on('mouseenter', '.admin-edit', function () {
            $('.story-admin-buttons', $(this)).show();
        }).on('mouseleave', '.admin-edit', function () {
            $('.story-admin-buttons', $(this)).hide();
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>