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

<div class="nav-main w-nav" data-animation="default" data-collapse="medium" data-duration="400">
    <div class="container-wide nav-container w-clearfix">
        <a class="w-nav-brand" href="<?php echo URL::home() ?>">
            <img class="brand" src="<?php echo SITE_RELATIVE_PATH ?>/images/dark.svg" width="160">
        </a>
        <nav class="m-nav-open w-nav-menu" role="navigation">
            <?php if ($loggedInUser) { ?>
                <a class="nav w-nav-link" href="<?php echo URL::dashboard() ?>">Dashboard</a>
            <?php } ?>
            <a class="nav w-nav-link" href="<?php echo URL::caseStudies() ?>">Case Studies</a>
            <a class="nav w-nav-link" href="<?php echo URL::blogPosts() ?>">Blog</a>
            <a class="nav w-nav-link" href="<?php echo URL::projects() ?>">Projects</a>
            <a class="nav w-nav-link" href="<?php echo URL::organisations() ?>">Organisations</a>
            <?php if (isset($loggedInUser) AND $loggedInUser) { ?>
                <div class="w-dropdown" data-delay="0">
                    <div class="log-in nav w-dropdown-toggle">
                        <div>Create</div>
                    </div>
                    <nav class="create-drop-down w-dropdown-list">
                        <a class="drop-down-link w-dropdown-link" data-ix="create-project-modal" href="#">
                            Create a new project</a>
                        <a class="drop-down-link w-dropdown-link" data-ix="create-organisation-modal" href="#">
                            Create an organisation</a>
                        <div class="arror-up"></div>
                    </nav>
                </div>
            <?php } else { ?>
                <a class="log-in log-in-alt nav w-nav-link" data-ix="open-login-modal">Login</a>
                <a class="log-in nav w-nav-link" data-ix="showsignup">Sign up</a>
            <?php } ?>
        </nav>
        <div class="menu-open w-nav-button">
            <div class="m-menu-btn w-icon-nav-menu"></div>
        </div>
    </div>
</div>

<div class="massive-hero" data-ix="reveal-menu">
    <div class="massive-hero-container">
        <h1 class="massive-hero-h1" data-ix="fadeinup">Digital social <br>innovation for Europe</h1>
        <h2 class="massive-hero-h2" data-ix="fadeinup-2">A community of people and projects who use the internet for
            social good</h2>
        <a class="massive-hero-twitter-link" data-ix="fadeinup-3"
           href="https://twitter.com/search?q=%23dsi4eu&amp;src=typd" target="_blank">#DSI4EU</a>
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
                            <a class="massive-hero-detail-link" href="<?php echo URL::caseStudy($caseStudy) ?>">Read
                                more</a>
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
                    <h2 class="big-h2">DSI is BIG &amp; Active</h2>
                </div>
            </div>
            <div class="intro-col-right w-col w-col-6">
                <div class="map-light" data-ix="fadeinup-4">
                    <div class="massiv-hero-stats">So far
                        <strong><?php echo number_format($organisationCount) ?></strong> Organisations
                        <br>have collaborated on <strong><?php echo number_format($projectCount) ?></strong> projects
                    </div>
                    <a class="what-text-button" href="<?php echo URL::projects() ?>">View projects</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="massive-intro-section">
    <div class="container-wide">
        <h3 class="what-h3" data-ix="fadeinuponload">Are you working on or interested in digital social innovation?<br>Yes?
            Then join the network!</h3>
        <p class="what-p" data-ix="fadeinuponload-2">We are building a network of digital social innovation in Europe.
            You can create a profile as an individual, an organisation or a project. Joining allows you to showcase your
            projects and networks, look for funding and find collaborators for future projects. Setting up a profile is
            quick and easy!</p>
    </div>
</div>

<div>
    <div class="container-wide">
        <div class="w-row">
            <div class="massive-who-column w-col w-col-4">
                <div class="massive-who-card" data-ix="fadeinuponload">
                    <h3 class="massive-h3">People</h3>
                    <p class="small what-p">People use DSI4EU as a way to learn about digital social innovation, get
                        involved with projects, share their skills and find funding.</p>
                    <a class="bottom what-text-button" href="#">Join DSI4EU</a>
                </div>
            </div>
            <div class="massive-who-column w-col w-col-4">
                <div class="alt massive-who-card" data-ix="fadeinuponload-2">
                    <h3 class="massive-h3">Projects</h3>
                    <p class="small what-p">Projects use DSI4EU to map their collaborators, showcase their work and
                        demonstrate their impact.</p>
                    <a class="bottom what-text-button" href="<?php echo URL::projects() ?>">Projects</a>
                </div>
            </div>
            <div class="massive-who-column w-col w-col-4">
                <div class="massive-who-card" data-ix="fadeinuponload-3">
                    <h3 class="massive-h3">Organisations</h3>
                    <p class="small what-p">Organisations use the network to map their projects, find new collaborators
                        and funding opportunities. Funding organisations also use it to find projects and people to
                        fund.</p>
                    <a class="bottom what-text-button" href="<?php echo URL::organisations() ?>">Organisations</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="massive-showcase">
    <div class="container-wide">
        <div class="case-studies-box" data-ix="fadeinuponload">
            <h3 class="light what-h3">Case studies</h3>
            <p class="small what-p">These case studies are featured examples of DSI projects. They show the range of
                ways people are making a social impact using open hardware, open knowledge, open data and open
                networks.</p>
            <a class="what-text-button" href="<?php echo URL::caseStudies() ?>">See all case studies</a>
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
                                <a class="case-study-card-read-more" href="<?php echo URL::caseStudy($caseStudy) ?>">
                                    See the case study
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