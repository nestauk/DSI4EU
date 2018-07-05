<?php
/** @var $loggedInUser \DSI\Entity\User */
/** @var $isHomePage bool */
/** @var $angularModules string[] */
/** @var $sliderCaseStudies \DSI\Entity\CaseStudy[] */
/** @var $homePageCaseStudies \DSI\Entity\CaseStudy[] */
/** @var $organisationsCount int */
/** @var $projectsCount int */
use DSI\Entity\Image;
use Services\URL;

if (!isset($urlHandler))
    $urlHandler = new URL();

?><!DOCTYPE html>
<html data-wf-site="56e2e31a1b1f8f784728a08c" data-wf-page="56fbef6ecf591b312d56f8be">
<head>
    <?php require __DIR__ . '/partialViews/head.php' ?>
</head>
<body id="top" ng-app="DSIApp">

<?php if ($loggedInUser) { ?>
    <?php require __DIR__ . '/partialViews/createProjectAndOrganisation.php' ?>
<?php } ?>

<?php require(__DIR__ . '/partialViews/header.php') ?>

<div class="hero-container" data-ix="hero-header-fold-down">
    <div class="hero">
        <div class="w-row">
            <div class="w-col w-col-7">
                <h1 class="home-hero-h1" data-ix="fadeinuponload-6"><?php _e('Digital social innovation') ?></h1>
                <h3 class="home-hero-h3 main" data-ix="fadeinuponload-7">
                    <?php _e('SHOWCASE YOUR PROJECT, MEET COLLABORATORS AND FIND FUNDING') ?>
                </h3>
            </div>
            <div class="w-col w-col-5">
                <img class="home-hero-img" data-ix="fadeinuponload-8"
                     src="<?php echo SITE_RELATIVE_PATH ?>/images/shadowlight.png">
            </div>
        </div>
    </div>
</div>
<div class="hero-cta" data-ix="fadeinuponload-9">
    <div class="cta-row w-row">
        <div class="w-clearfix w-col w-col-8 w-col-stack">
            <div class="cta-text">
                <div class="home-hero-cta" data-ix="fadeinuponload-10">
                    <?php echo sprintf(
                        __('Join the community of %s organisations and %s projects'),
                        '<span class="sub-bold">' .
                        sprintf(
                            __('%s organisations'),
                            number_format($organisationsCount)
                        )
                        . '</span>',
                        '<span class="sub-bold">' .
                        sprintf(
                            __('%s projects'),
                            number_format($projectsCount)
                        )
                        . '</span>'
                    ) ?>
                </div>
            </div>
        </div>
        <div class="butcol w-col w-col-4 w-col-stack">
            <div class="signn">
                <a class="log-in-link sign-up w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="<?php echo $urlHandler->login() ?>">
                    <div class="login-li menu-li" style="font-size:15px"><?php _e('JOIN NOW') ?></div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
            </div>
        </div>
    </div>
</div>
<div class="top-3">
    <div class="w-row">
        <div class="top-3-col w-col w-col-4" data-ix="fadeinuponload-12">
            <div class="top-3-link" data-ix="underline">
                <h3 class="top3-h3"><?php _e('FUNDING') ?></h3>
                <div class="top3-underline" data-ix="new-interaction-2"></div>
                <p class="top-3-p"><?php _e('Use our funding directory to find opportunities for your project') ?></p>
                <a class="log-in-link read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="<?php echo $urlHandler->funding() ?>">
                    <div class="login-li menu-li readmore-li"><?php _ehtml('Read more') ?></div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
            </div>
        </div>
        <div class="top-3-col w-col w-col-4" data-ix="fadeinuponload-13">
            <div class="top-3-link" data-ix="underline">
                <h3 class="top3-h3"><?php _ehtml('Events') ?></h3>
                <div class="top3-underline" data-ix="new-interaction-2"></div>
                <p class="top-3-p"><?php _e('Explore DSI events happening around Europe') ?></p>
                <a class="log-in-link read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="<?php echo $urlHandler->events() ?>">
                    <div class="login-li menu-li readmore-li"><?php _ehtml('Read more') ?></div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
            </div>
        </div>
        <div class="top-3-col w-col w-col-4" data-ix="fadeinuponload-14">
            <div class="top-3-link" data-ix="underline">
                <h3 class="top3-h3"><?php _ehtml('News & blogs') ?></h3>
                <div class="top3-underline" data-ix="new-interaction-2"></div>
                <p class="top-3-p">
                    <?php _e('Our blog features stories of the people and projects pioneering digital social innovation') ?>
                </p>
                <a class="log-in-link read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="<?php echo $urlHandler->blogPosts() ?>">
                    <div class="login-li menu-li readmore-li"><?php _ehtml('Read more') ?></div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
            </div>
        </div>
    </div>
</div>
<div class="stats-bg">
    <div class="content">
        <h2 class="centered h2-large" data-ix="fadeinuponload-2">
            <?php _e("EXPLORE EUROPE’S GROWING NETWORK OF DIGITAL SOCIAL INNOVATION") ?>
        </h2>

        <?php require __DIR__ . '/partialViews/index-' . \DSI\Service\Translate::getCurrentLang() . '.php'; ?>
    </div>
</div>

<div class="datavis stats-bg" data-ix="show-data-vis" id="datavis"
     onclick="window.open('<?= \DSI\Service\DataVis::getUrl() ?>', '_blank');">
    <div class="content">
        <div class="row w-row">
            <div class="column-2 w-clearfix w-col w-col-6 w-col-stack">
                <h3 class="data-h3">Data visualisation</h3>
                <h2 class="data h2-large" data-ix="fadeinuponload-2">
                    Explore Europe’s network of digital social innovation
                </h2>
                <p class="data-p">
                    There are <?= $organisationsCount ?> organisations and <?= $projectsCount ?> projects working on DSI
                    across Europe.
                </p>
                <p class="data-p">
                    With our interactive data visualisation, you can explore the organisations and projects working on
                    DSI across Europe. Check it out now to understand what’s going on across the continent and how you
                    fit into it!
                </p>
                <a class="large log-in-link sign-up w-clearfix w-inline-block" data-ix="log-in-arrow" href="#">
                    <div class="data-button login-li menu-li">Explore Europe’s DSI network</div>
                    <img class="login-arrow" src="<?= SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
            </div>
            <div class="column w-col w-col-6 w-col-stack">
                <div class="div-block-3">
                    <div class="map-point mp3" data-ix="map-point-expand"></div>
                    <div class="map-point mp5" data-ix="map-point-expand"></div>
                    <div class="map-point mp13" data-ix="map-point-expand"></div>
                    <div class="map-point mp12" data-ix="map-point-expand"></div>
                    <div class="map-point" data-ix="map-point-expand"></div>
                    <div class="map-point mp9" data-ix="map-point-expand-3"></div>
                    <div class="map-point mp8" data-ix="map-point-expand-3"></div>
                    <div class="map-point mp7" data-ix="map-point-expand-3"></div>
                    <div class="map-point mp4" data-ix="map-point-expand-2"></div>
                    <div class="map-point mp11" data-ix="map-point-expand-2"></div>
                    <div class="map-point mp14" data-ix="map-point-expand-2"></div>
                    <div class="map-point mp10" data-ix="map-point-expand-2"></div>
                    <div class="map-point mp6" data-ix="map-point-expand-2"></div>
                    <div class="map-point mp2" data-ix="map-point-expand-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="home-page-events">
    <div class="content-block cs">
        <h3 class="centered title"><?php _ehtml('Case Studies') ?></h3>
        <div class="sub-header-centre"><?php _e('IN NEED OF INSPIRATION?') ?></div>
        <p class="centered">
            <?php _ehtml('Short stories introducing digital social innovations which we love') ?>
        </p>
        <div class="w-row">
            <?php foreach ($homePageCaseStudies AS $i => $caseStudy) { ?>
                <div class="w-col w-col-4">
                    <a class="case-study-ind w-inline-block" data-ix="scaleimage"
                       href="<?php echo $urlHandler->caseStudy($caseStudy) ?>">
                        <div class="case-study-img-container">
                            <div class="_<?php echo $i % 3 + 1 ?> case-study-img"
                                 style="background-image: url('<?php echo Image::CASE_STUDY_CARD_BG_URL . $caseStudy->getCardImage() ?>');"></div>
                        </div>
                        <h3 class="case-study-card-h3"><?php echo show_input($caseStudy->getTitle()) ?></h3>
                        <p class="cradp"><?php echo show_input($caseStudy->getIntroCardText()) ?></p>
                        <div class="log-in-link read-more w-clearfix" data-ix="log-in-arrow">
                            <div class="login-li menu-li readmore-li"><?php _ehtml('Read more') ?></div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
        <div class="signn">
            <a class="large log-in-link sign-up w-clearfix w-inline-block" data-ix="log-in-arrow"
               href="<?php echo $urlHandler->caseStudies() ?>">
                <div class="login-li menu-li"><?php _ehtml('See all case studies') ?></div>
                <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/footer.php' ?>