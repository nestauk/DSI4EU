<?php
require __DIR__ . '/header.php';
/** @var $loggedInUser \DSI\Entity\User */
/** @var $caseStudies \DSI\Entity\CaseStudy[] */
/** @var $userCanManageCaseStudies bool */
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

        .filter-block a {
            width: 140px;
        }
    </style>

    <div ng-controller="CaseStudiesController"
         data-jsonurl="<?php echo $urlHandler->caseStudies('json') ?>">
        <div class="w-section page-header stories-header">
            <div class="container-wide header">
                <h1 class="page-h1 light"><?php _ehtml('Case studies') ?></h1>
                <div class="filter-block">
                    <div class="w-row">
                        <div class="w-col w-col-9 w-col-stack">
                            <?php /*
                            <div class="w-row">
                                <div class="w-col w-col-2">
                                    <a class="w-button dsi-button top-filter" ng-click="searchCriteria = {}" href="#">
                                        All</a>
                                </div>
                                <div class="w-col w-col-2">
                                    <a class="w-button dsi-button top-filter" ng-click="searchCriteria.catg = 3"
                                       href="#">
                                        Open hardware</a>
                                </div>
                                <div class="w-col w-col-2">
                                    <a class="w-button dsi-button top-filter" ng-click="searchCriteria.catg = 2"
                                       href="#">
                                        Open networks</a>
                                </div>
                                <div class="w-col w-col-2">
                                    <a class="w-button dsi-button top-filter" ng-click="searchCriteria.catg = 1"
                                       href="#">
                                        Open data</a>
                                </div>
                                <div class="w-col w-col-2">
                                    <a class="w-button dsi-button top-filter" ng-click="searchCriteria.catg = 1"
                                       href="#">
                                        Open knowledge</a>
                                </div>
                            </div>
                            */ ?>
                        </div>
                        <div class="w-col w-col-3 w-col-stack w-clearfix">
                            <?php if ($userCanManageCaseStudies) { ?>
                                <a class="w-button dsi-button top-filter add-new-story"
                                   href="<?php echo $urlHandler->addCaseStudy() ?>">
                                    Add case study +</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="case-study-grid container-wide">
            <div class="case-studies-row case-studies-row-grid w-row">
                <?php foreach ($caseStudies AS $i => $caseStudy) { ?>
                    <div class="case-study-col-<?php echo $i % 3 + 1 ?> w-col w-col-4">
                        <div class="onloadone" data-ix="fadeinuponload-<?php echo $i % 3 + 2 ?>">
                            <div class="case-study-card" data-ix="case-study-card-overlay"
                                 style="background-image: url('<?php echo \DSI\Entity\Image::CASE_STUDY_CARD_BG_URL . $caseStudy->getCardImage() ?>');">
                                <div class="case-study-card-overlay"
                                     style="background-color:<?php echo $caseStudy->getCardColour() ?>"></div>
                                <div class="case-study-card-info">
                                    <img class="case-study-card-logo" width="75"
                                         src="<?php echo \DSI\Entity\Image::CASE_STUDY_LOGO_URL . $caseStudy->getLogo() ?>">
                                    <div class="case-study-card-p">
                                        <?php echo show_input($caseStudy->getIntroCardText()) ?>
                                    </div>
                                    <a class="case-study-card-read-more"
                                       href="<?php echo $urlHandler->caseStudy($caseStudy) ?>">
                                        <?php _ehtml('See the case study') ?>
                                    </a>
                                </div>
                                <div class="case-study-card-label w-clearfix">
                                    <div class="case-study-card-name">
                                        <?php
                                        echo show_input(substr($caseStudy->getTitle(), 0, 15));
                                        if (strlen($caseStudy->getTitle()) > 15) echo '...';
                                        if ($userCanManageCaseStudies AND !$caseStudy->isPublished())
                                            echo ' <span style="color:red">(Unpublished)</span>';
                                        ?>
                                    </div>
                                    <div class="case-study-card-name country">
                                        <?php echo show_input($caseStudy->getCountryName()) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($i % 3 == 2) { ?>
                        <div style="clear:both"></div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/CaseStudiesController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>