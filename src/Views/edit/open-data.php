<?php

use \Models\Resource;

/** @var $urlHandler Services\URL */
/** @var $mainText \Models\Text */
/** @var $subText \Models\Text */
/** @var $resources Resource[] */

\DSI\Service\JsModules::setTinyMCE(true);
\DSI\Service\JsModules::setMasonry(true);
$angularModules['fileUpload'] = true;

require __DIR__ . '/../header.php';
?>

    <div class="edit-open-data-controller" ng-controller="EditOpenDataController">
        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8">
                    <?php /* <h1 class="content-h1"><?php _ehtml('Open data, research & resources') ?></h1> */ ?>
                    <h1 class="content-h1"><?php _ehtml('Research and resources') ?></h1>

                    <div>
                        <label>Main paragraph</label>
                        <textarea class="creator-data-entry end long-description w-input editor"
                                  id="main-text"
                                  data-placeholder="Cluster paragraph"><?= show_input($mainText->getCopy()) ?></textarea>
                        <div class="error" ng-bind="errors.mainText"></div>
                    </div>

                    <p class="intro">
                        <?php // _ehtml('DSI4EU is committed to being open and transparent') ?>
                    </p>

                    <div>
                        <label>Second paragraph</label>
                        <textarea class="creator-data-entry end long-description w-input editor"
                                  id="sub-text"
                                  data-placeholder="Cluster paragraph"><?= show_input($subText->getCopy()) ?></textarea>
                        <div class="error" ng-bind="errors.subText"></div>
                    </div>

                    <p class="p-head">
                        <?php // _ehtml('You can read all of our previous and current research publications here.') ?>
                    </p>

                    <div style="text-align:right">
                        <a href="<?= $urlHandler->openDataResearchAndResources() ?>"
                           class="w-button dsi-button creat-button"
                           style="width:auto;background:white;display: inline-block;">
                            <?= __('Back') ?>
                        </a>

                        <button type="button" class="w-button dsi-button creat-button"
                                style="width:auto;display: inline-block;"
                                ng-click="saveTexts()"
                                ng-bind="loading ? '<?= __('Loading...') ?>' : '<?= __('Save page details') ?>'">
                        </button>
                    </div>
                </div>
                <div class="sidebar w-col w-col-4 w-col-stack">
                    <?php require __DIR__ . '/../partialViews/about-dsi.php' ?>
                </div>
            </div>
        </div>
        <div class="content-directory">
            <div class="content">
                <div class="w-row grid w-clearfix">
                    <div class="w-col w-col-4 grid-item">
                        <a class="resource-card w-inline-block" href="<?= $urlHandler->openResourceCreate() ?>">
                            <div class="info-card resource">
                                <h3>Add new resource</h3>
                                <p>Click to add new resource</p>
                                <div class="log-in-link long next-page read-more w-clearfix" data-ix="log-in-arrow"
                                     href="<?= $resource->{Resource::LinkUrl} ?>" target="_blank">
                                    <div class="login-li long menu-li readmore-li">
                                        Link to add new resource
                                    </div>
                                    <img src="/images/ios7-arrow-thin-right.png" class="login-arrow">
                                </div>
                            </div>
                        </a>
                    </div>

                    <?php foreach ($resources AS $resource) { ?>
                        <div class="w-col w-col-4 grid-item">
                            <div class="resource-card w-inline-block">
                                <div class="info-card resource">
                                    <img class="research-paper-img"
                                         src="<?= \DSI\Entity\Image::UPLOAD_FOLDER_URL . $resource->{Resource::Image} ?>">
                                    <h3><?= show_input($resource->{Resource::Title}) ?></h3>
                                    <p><?= show_input($resource->{Resource::Description}) ?></p>
                                    <a class="log-in-link long next-page read-more w-clearfix" data-ix="log-in-arrow"
                                       href="<?= $resource->{Resource::LinkUrl} ?>" target="_blank">
                                        <div class="login-li long menu-li readmore-li">
                                            <?= show_input($resource->{Resource::LinkText}) ?>
                                        </div>
                                        <img src="/images/ios7-arrow-thin-right.png" class="login-arrow">
                                    </a>

                                    <div class="w-clearfix">
                                        <a href="<?= $urlHandler->openResourceEdit($resource) ?>"
                                           class="edit-resource">Edit</a>
                                        <a href="#"
                                           ng-click="deleteResource('<?= $urlHandler->openResourceEditApi($resource) ?>')"
                                           class="delete-resource">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <!-- end of second row -->
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="/js/controllers/EditOpenDataController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <script>
        $(function () {
            tinymce.init({
                selector: '.editor',
                statusbar: false,
                height: 500,
                plugins: "autoresize autolink lists link preview paste textcolor colorpicker image imagetools media",
                autoresize_bottom_margin: 5,
                autoresize_max_height: 500,
                menubar: false,
                toolbar1: 'styleselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | preview',
                image_advtab: true,
                paste_data_images: false
            });
        });
    </script>

    <style>
        .grid-item {
            width: 33.3333333%;
        }

        @media screen and (max-width: 767px) {
            .grid-item {
                width: 100%;
            }
        }
    </style>

    <script>
        $(function () {
            if ($(window).width() > 767) {
                $('.grid').masonry({
                    itemSelector: '.grid-item',
                    horizontalOrder: true
                });
            }
        })
    </script>

<?php require __DIR__ . '/../footer.php' ?>