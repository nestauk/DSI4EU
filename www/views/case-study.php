<?php
require __DIR__ . '/header.php';
/** @var $loggedInUser \DSI\Entity\User */
/** @var $caseStudy \DSI\Entity\CaseStudy */
/** @var $caseStudies \DSI\Entity\CaseStudy[] */
/** @var $userCanAddCaseStudy bool */
/** @var $urlHandler \DSI\Service\URL */
?>

    <div class="case-study-intro">
        <div class="header-content">
            <div class="case-study-img-bg-blur"></div>
            <div class="container-wide">
                <h1 class="case-study-h1"><?php echo show_input($caseStudy->getTitle()) ?></h1>
                <h3 class="home-hero-h3"><?php echo show_input($caseStudy->getIntroCardText()) ?></h3>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="w-row">
            <div class="content-left w-col w-col-8">
                <div class="intro"><?php echo $caseStudy->getIntroPageText() ?></div>
                <div><?php echo $caseStudy->getMainText() ?></div>
            </div>
            <div class="column-right-small w-col w-col-4">
                <?php if ($userCanAddCaseStudy) { ?>
                    <h1 class="content-h1">Actions</h1>
                    <a class="sidebar-link" href="<?php echo $urlHandler->caseStudyEdit($caseStudy) ?>">
                        <span class="green">-&nbsp;</span>Edit case study
                    </a>
                <?php } ?>

                <h3 class="cse side-bar-h3">Info</h3>
                <div><?php echo show_input($caseStudy->getInfoText()) ?></div>
                <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="<?php echo $caseStudy->getUrl() ?>">
                    <div class="login-li long menu-li readmore-li">
                        <?php echo show_input($caseStudy->getButtonLabel()) ?>
                    </div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>

                <?php if ($caseStudy->getProject() OR $caseStudy->getOrganisation()) { ?>
                    <h3 class="cse side-bar-h3"><?php echo show_input($caseStudy->getTitle()) ?> on DSI</h3>

                    <?php if ($project = $caseStudy->getProject()) { ?>
                        <p><?php echo show_input($project->getShortDescription()) ?></p>
                        <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                           href="<?php echo $urlHandler->project($project) ?>">
                            <div class="login-li long menu-li readmore-li">Visit Project</div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </a>
                    <?php } ?>

                    <?php if ($organisation = $caseStudy->getOrganisation()) { ?>
                        <p><?php echo show_input($organisation->getShortDescription()) ?></p>
                        <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                           href="<?php echo $urlHandler->organisation($organisation) ?>">
                            <div class="login-li long menu-li readmore-li">Visit Organisation</div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <h3 class="related-h3">More case studies</h3>
        <div class="w-row">
            <?php foreach ($caseStudies AS $i => $_caseStudy) { ?>
                <div class="w-col w-col-4">
                    <a class="case-study-ind w-inline-block" data-ix="scaleimage"
                       href="<?php echo $urlHandler->caseStudy($_caseStudy) ?>">
                        <div class="case-study-img-container">
                            <div class="_<?php echo ($i % 3) + 1 ?> case-study-img"
                                 style="background-image: url('<?php echo \DSI\Entity\Image::CASE_STUDY_CARD_BG_URL . $_caseStudy->getCardImage() ?>');"></div>
                        </div>
                        <h3 class="case-study-card-h3">
                            <?php echo show_input($_caseStudy->getTitle()) ?>
                        </h3>
                        <p class="cradp">
                            <?php echo show_input($_caseStudy->getIntroCardText()) ?>
                        </p>
                        <div class="log-in-link read-more w-clearfix" data-ix="log-in-arrow">
                            <div class="login-li menu-li readmore-li">Read more</div>
                            <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
        <div class="signn">
            <a class="large log-in-link sign-up w-clearfix w-inline-block" data-ix="log-in-arrow"
               href="<?php echo $urlHandler->caseStudies() ?>">
                <div class="login-li menu-li">See all case studies</div>
                <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
            </a>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>