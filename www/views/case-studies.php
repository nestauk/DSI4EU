<?php
require __DIR__ . '/header.php';
/** @var $loggedInUser \DSI\Entity\User */
/** @var $caseStudies \DSI\Entity\CaseStudy[] */
/** @var $userCanManageCaseStudies bool */
/** @var $urlHandler Services\URL */
?>

    <div ng-controller="CaseStudiesController"
         data-jsonurl="<?php echo $urlHandler->caseStudies('json') ?>">

        <div class="content-block">
            <div class="intro-row w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1"><?php _ehtml('Case Studies') ?></h1>
                    <p class="intro"><?php _ehtml('What does digital social innovation look like in practice?') ?></p>
                    <p><?php _ehtml('Here you can find examples of digital social innovation to inform and inspire you.') ?></p>
                </div>
                <div class="sidebar w-col w-col-4 w-col-stack">
                    <?php if ($userCanManageCaseStudies) { ?>
                        <h1 class="content-h1"><?php _ehtml('Actions') ?></h1>
                        <a class="sidebar-link" href="<?php echo $urlHandler->addCaseStudy() ?>">
                            <span class="green">-&nbsp;</span>
                            <?php _ehtml('Add new case study') ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
            <div class="case-study-row-p w-row">
                <?php foreach ($caseStudies AS $i => $caseStudy) { ?>
                <div class="w-col w-col-4">
                    <a class="case-study-ind w-inline-block" data-ix="scaleimage"
                       href="<?php echo $urlHandler->caseStudy($caseStudy) ?>">
                        <div class="case-study-img-container">
                            <div class="_<?php echo ($i % 3) + 1 ?> case-study-img"
                                 style="background-image: url('<?php echo \DSI\Entity\Image::CASE_STUDY_CARD_BG_URL . $caseStudy->getCardImage() ?>');"></div>
                        </div>
                        <h3 class="case-study-card-h3">
                            <?php echo show_input($caseStudy->getTitle()) ?>
                            <?php if ($userCanManageCaseStudies AND !$caseStudy->isPublished()) {
                                echo ' <span style="color:red">(' . _html('Unpublished') . ')</span>';
                            } ?>
                        </h3>
                        <p class="cradp">
                            <?php echo show_input($caseStudy->getIntroCardText()) ?>
                        </p>
                        <div class="log-in-link read-more w-clearfix" data-ix="log-in-arrow">
                            <div class="login-li menu-li readmore-li"><?php _ehtml('Read more') ?></div>
                            <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH?>/images/ios7-arrow-thin-right.png">
                        </div>
                    </a>
                </div>
                <?php if ($i % 3 == 2) { ?>
            </div>
            <div class="case-study-row-p w-row">
                <?php } ?>
                <?php /*
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
                    */ ?>
                <?php } ?>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/CaseStudiesController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>