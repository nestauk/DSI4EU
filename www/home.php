<?php
/** @var $loggedInUser \DSI\Entity\User */
/** @var $isHomePage bool */
/** @var $angularModules string[] */
/** @var $pageTitle string[] */
/** @var $sliderCaseStudies \DSI\Entity\CaseStudy[] */
/** @var $homePageCaseStudies \DSI\Entity\CaseStudy[] */
/** @var $organisationCount int */
/** @var $projectCount int */
use \DSI\Service\URL;
use \DSI\Entity\Image;

$projectsCount = (new \DSI\Repository\ProjectRepositoryInAPC())->countAll();
$organisationsCount = (new \DSI\Repository\OrganisationRepositoryInAPC())->countAll();

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
<?php } else { ?>
    <?php require __DIR__ . '/partialViews/loginModal.php' ?>
<?php } ?>

<?php require(__DIR__ . '/partialViews/header.php') ?>

<div class="massive-hero" data-ix="reveal-menu">
    <div class="massive-hero-container">
        <h1 class="massive-hero-h1" data-ix="fadeinup"><?php _ehtml('Digital social innovation for Europe') ?></h1>
        <h2 class="massive-hero-h2" data-ix="fadeinup-2">
            <?php _ehtml('A community of people and projects who use the internet for social good') ?>
        </h2>
        <a class="massive-hero-twitter-link" data-ix="fadeinup-3"
           href="https://twitter.com/dsi4eu" target="_blank">@DSI4EU</a>
    </div>
    <div class="massive-hero-slider w-slider" data-animation="outin" data-autoplay="1" data-delay="8000"
         data-duration="800" data-infinite="1">
        <div class="massive-hero-slide-mask w-slider-mask">
            <?php foreach ($sliderCaseStudies AS $caseStudy) { ?>
                <div class="massive-hero-slide w-slide wikihouse"
                     style="background-image: linear-gradient(180deg, rgba(0, 0, 0, .65), rgba(0, 0, 0, .65)), url('<?php echo Image::CASE_STUDY_HEADER_URL . $caseStudy->getHeaderImage() ?>');">
                    <div class="container-wide massive-hero-slide-container">
                        <div class="slide-info" data-ix="slide-info">
                            <h2 class="massive-hero-slide-h2"><?php echo show_input($caseStudy->getTitle()) ?></h2>
                            <p class="massive-hero-slide-detail"><?php echo show_input($caseStudy->getIntroCardText()) ?></p>
                            <a class="massive-hero-detail-link" href="<?php echo $urlHandler->caseStudy($caseStudy) ?>">
                                <?php _ehtml('Read more') ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="w-slider-arrow-left">
            <div class="icon-left w-icon-slider-left"></div>
        </div>
        <div class="w-slider-arrow-right">
            <div class="icon-right w-icon-slider-right"></div>
        </div>
        <div class="slide-nav-small w-round w-slider-nav"></div>
    </div>
</div>


<div class="massive-intro-section">
    <div class="container-wide">
        <div class="intro-columns w-row">
            <div class="intro-col-left w-col w-col-6">
                <div class="what-is-dsi" data-ix="fadeinup-3">
                    <h2 class="big-h2"><?php _ehtml('DSI is BIG & Active') ?></h2>
                </div>
            </div>
            <div class="intro-col-right w-col w-col-6">
                <div class="map-light" data-ix="fadeinup-4">
                    <div class="massiv-hero-stats">So far
                        <?php echo sprintf(
                            __('%s Organisations have collaborated on %s projects'),
                            '<strong>' . number_format($organisationCount) . '</strong>',
                            '<strong>' . number_format($projectCount) . '</strong>'
                        ) ?>
                    </div>
                    <a class="what-text-button" href="<?php echo $urlHandler->projects() ?>">
                        <?php _ehtml('View projects') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="massive-intro-section">
    <div class="container-wide">
        <h3 class="what-h3" data-ix="fadeinuponload">
            <?php _ehtml('Are you working on or interested in digital social innovation?') ?><br/>
            <?php _ehtml('Yes? Then join the network!') ?>
        </h3>
        <p class="what-p" data-ix="fadeinuponload-2">
            <?php _ehtml('We are building a network of digital social innovation in Europe.') ?>
        </p>
    </div>
</div>

<div>
    <div class="container-wide">
        <div class="w-row">
            <div class="massive-who-column w-col w-col-4">
                <div class="massive-who-card" data-ix="fadeinuponload">
                    <h3 class="massive-h3"><?php _ehtml('People') ?></h3>
                    <p class="small what-p">
                        <?php _ehtml('People use DSI4EU as a way to learn about digital social innovation') ?>
                    </p>
                    <a class="bottom what-text-button" href="#"><?php _ehtml('Join DSI4EU') ?></a>
                </div>
            </div>
            <div class="massive-who-column w-col w-col-4">
                <div class="alt massive-who-card" data-ix="fadeinuponload-2">
                    <h3 class="massive-h3"><?php _ehtml('Projects') ?></h3>
                    <p class="small what-p">
                        <?php _ehtml('Projects use DSI4EU to map their collaborators') ?>
                    </p>
                    <a href="<?php echo $urlHandler->projects() ?>"
                       class="bottom what-text-button"><?php _ehtml('Projects') ?></a>
                </div>
            </div>
            <div class="massive-who-column w-col w-col-4">
                <div class="massive-who-card" data-ix="fadeinuponload-3">
                    <h3 class="massive-h3"><?php _ehtml('Organisations') ?></h3>
                    <p class="small what-p">
                        <?php _ehtml('Organisations use the network to map their projects') ?>
                    </p>
                    <a class="bottom what-text-button"
                       href="<?php echo $urlHandler->organisations() ?>"><?php _ehtml('Organisations') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="massive-showcase">
    <div class="container-wide">
        <div class="case-studies-box" data-ix="fadeinuponload">
            <h3 class="light what-h3"><?php _ehtml('Case studies') ?></h3>
            <p class="small what-p">
                <?php _ehtml('These case studies are featured examples of DSI projects') ?>
            </p>
            <a class="what-text-button" href="<?php echo $urlHandler->caseStudies() ?>">
                <?php _ehtml('See all case studies') ?></a>
        </div>
        <div class="case-studies-row w-row">
            <?php foreach ($homePageCaseStudies AS $i => $caseStudy) { ?>
                <div class="case-study-col-<?php echo $i + 1 ?> w-col w-col-4">
                    <div class="onloadone" data-ix="fadeinuponload-<?php echo $i % 3 + 2 ?>">
                        <div class="case-study-card" data-ix="case-study-card-overlay"
                             style="background-image: url('<?php echo Image::CASE_STUDY_CARD_BG_URL . $caseStudy->getCardImage() ?>');">
                            <div class="case-study-card-overlay"
                                 style="background-color: <?php echo show_input($caseStudy->getCardColour()) ?>;"></div>
                            <div class="case-study-card-info">
                                <img class="case-study-card-logo"
                                     src="<?php echo Image::CASE_STUDY_LOGO_URL . $caseStudy->getLogo() ?>"
                                     width="75">
                                <div class="case-study-card-p">
                                    <?php echo show_input($caseStudy->getIntroCardText()) ?>
                                </div>
                                <a href="<?php echo $urlHandler->caseStudy($caseStudy) ?>"
                                   class="case-study-card-read-more"><?php _ehtml('See the case study') ?>
                                </a>
                            </div>
                            <div class="case-study-card-label w-clearfix">
                                <div class="case-study-card-name"><?php echo show_input($caseStudy->getTitle()) ?></div>
                                <div
                                    class="case-study-card-name country"><?php echo show_input($caseStudy->getCountryName()) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="w-section">
    <div class="email-signup">
        <div class="container-wide newsletter-signup"></div>
    </div>
</div>
<div>

<?php require __DIR__ . '/footer.php' ?>