<?php

use Models\Resource;

/** @var $urlHandler Services\URL */
/** @var $mainText \Models\Text */
/** @var $subText \Models\Text */
/** @var $canEdit bool */
/** @var $resources \Models\Resource[] */
/** @var $authors \Models\AuthorOfResource[] */
/** @var $clusters \Models\Cluster[] */
/** @var $types \Models\TypeOfResource[] */

\DSI\Service\JsModules::setMasonry(true);
require __DIR__ . '/header.php';
?>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/OpenResourcesController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <div class="resources-controller" ng-controller="OpenResourcesController"
         data-apiurl="<?php echo $urlHandler->openResourcesApi() ?>">
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
                <div class="w-row 'w-clearfix">
                    <div class="w-col w-col-4">
                        <div class="filter-bar info-card">
                            <div class="w-form">
                                <form id="email-form" name="email-form">
                                    <h3 class="sidebar-h3"><?php _ehtml('Filter resources') ?></h3>

                                    <div class="filter-title">
                                        <strong><?php _ehtml('Cluster') ?></strong>
                                    </div>

                                    <?php foreach ($clusters AS $cluster) { ?>
                                        <div class="filter-checkbox w-checkbox">
                                            <label class="w-form-label">
                                                <input class="w-checkbox-input" type="checkbox"
                                                       ng-model="clusters[<?= $cluster->getId() ?>]">
                                                <?= $cluster->getClusterLang()->getTitle() ?>
                                            </label>
                                        </div>
                                        <div class="trend-notes"></div>
                                    <?php } ?>

                                    <br/>
                                    <div class="filter-title">
                                        <strong><?php _ehtml('Type of Resource') ?></strong>
                                    </div>

                                    <?php foreach ($types AS $type) { ?>
                                        <div class="filter-checkbox w-checkbox">
                                            <label class="w-form-label">
                                                <input class="w-checkbox-input" type="checkbox"
                                                       ng-model="types[<?= $type->getId() ?>]">
                                                <?= _html($type->{\Models\TypeOfResource::Name}) ?>
                                            </label>
                                        </div>
                                        <div class="trend-notes"></div>
                                    <?php } ?>

                                    <br>

                                    <div class="filter-title">
                                        <strong><?php _ehtml('Author') ?></strong>
                                    </div>

                                    <div class="adv-options">
                                        <select class="w-select" id="field" ng-model="author_id">
                                            <option value="0">- <?php _ehtml('Select an author') ?> -</option>
                                            <?php foreach ($authors AS $author) { ?>
                                                <option value="<?= $author->getId() ?>">
                                                    <?= _html($author->{\Models\AuthorOfResource::Name}) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="w-col w-col-8">
                        <div class="w-row grid w-clearfix">
                            <div class="w-col w-col-6 grid-item" ng-repeat="resource in filteredResources">
                                <a class="resource-card w-inline-block" ng-href="resource.link_url"
                                   target="_blank">
                                    <div class="info-card resource">
                                        <img class="research-paper-img" ng-src="{{resource.image}}">
                                        <h3>{{resource.title}}</h3>
                                        <p>{{resource.description}}</p>
                                        <div class="log-in-link long next-page read-more w-clearfix">
                                            <div class="login-li long menu-li readmore-li">
                                                {{resource.link_text}}
                                            </div>
                                            <img src="/images/ios7-arrow-thin-right.png" class="login-arrow">
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
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
    </div>

<?php require __DIR__ . '/footer.php' ?>