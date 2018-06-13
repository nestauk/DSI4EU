<?php

use Models\Resource;

/** @var $urlHandler Services\URL */
/** @var $mainText \Models\Text */
/** @var $subText \Models\Text */
/** @var $canEdit bool */
/** @var $resources \Models\Resource[] */

\DSI\Service\JsModules::setMasonry(true);
require __DIR__ . '/header.php';
?>

    <div class="content-block">
        <div class="w-row">
            <div class="w-col w-col-8">
                <h1 class="content-h1"><?php _ehtml('Open data, research & resources') ?></h1>
                <div class="intro">
                    <?= $mainText->getCopy() ?>
                </div>
                <div class="p-head">
                    <?= $subText->getCopy() ?>
                </div>
            </div>
            <div class="sidebar w-col w-col-4 w-col-stack">
                <?php require __DIR__ . '/partialViews/about-dsi.php' ?>

                <?php if ($canEdit) { ?>
                    <a class="sidebar-link" href="<?php echo $urlHandler->openDataResearchAndResourcesEdit() ?>">
                        <span class="green">- <?php _ehtml('Edit page') ?></span>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="content-directory">
        <div class="content">
            <div class="w-row grid w-clearfix">

                <?php foreach ($resources AS $resource) { ?>
                    <div class="w-col w-col-4 grid-item">
                        <a class="resource-card w-inline-block" href="<?= $resource->{Resource::LinkUrl} ?>"
                           target="_blank">
                            <div class="info-card resource">
                                <img class="research-paper-img"
                                     src="<?= \DSI\Entity\Image::UPLOAD_FOLDER_URL . $resource->{Resource::Image} ?>">
                                <h3><?= show_input($resource->{Resource::Title}) ?></h3>
                                <p><?= show_input($resource->{Resource::Description}) ?></p>
                                <div class="log-in-link long next-page read-more w-clearfix" data-ix="log-in-arrow">
                                    <div class="login-li long menu-li readmore-li">
                                        <?= show_input($resource->{Resource::LinkText}) ?>
                                    </div>
                                    <img src="/images/ios7-arrow-thin-right.png" class="login-arrow">
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="content-block">
        <p class="bold-p">
            <strong>
                <?php _ehtml('If you are interested in further exploring the data behind DSI4EU you can:') ?>
            </strong>
        </p>
        <ul>
            <li><p>
                    <?php _ehtml('Access all the anonymised data we have captured on DSI in Europe via the DSI4EU open data set.') ?>
                </p>
                <p><strong><?php _ehtml('Projects data:') ?></strong>
                    <a href="https://digitalsocial.eu/export/projects.json">json</a>,
                    <a href="https://digitalsocial.eu/export/projects.csv">csv</a>,
                    <a href="https://digitalsocial.eu/export/projects.xml">xml</a>
                </p>
                <p><strong><?php _ehtml('Organisations data:') ?></strong>
                    <a href="https://digitalsocial.eu/export/organisations.json">json</a>,
                    <a href="https://digitalsocial.eu/export/organisations.csv">csv</a>,
                    <a href="https://digitalsocial.eu/export/organisations.xml">xml</a>
                </p>
            </li>
            <li>
                <?php _ehtml('Download the source code. All of the code used to develop this site will be shared') ?>
                <a href="https://github.com/nestauk/DSI4EU" target="_blank"><?php _ehtml('Website') ?></a>
                |
                <a href="https://github.com/nestauk/DSI4EU_Dataviz"
                   target="_blank"><?php _ehtml('Data visualisation') ?></a>
            </li>
        </ul>
        <a class="log-in-link long next-page read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
           href="<?php echo $urlHandler->contactDSI() ?>">
            <div class="login-li long menu-li readmore-li"><?php _ehtml('Contact DSI4EU') ?></div>
            <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
        </a>
    </div>

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
        if ($(window).width() > 767) {
            $('.grid').masonry({
                itemSelector: '.grid-item',
                horizontalOrder: true
            });
        }
    </script>

<?php require __DIR__ . '/footer.php' ?>