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
<?php } ?>

<?php require(__DIR__ . '/partialViews/header.php') ?>

<div class="hero-container" data-ix="hero-header-fold-down">
    <div class="hero">
        <div class="w-row">
            <div class="w-col w-col-7">
                <h1 class="home-hero-h1" data-ix="fadeinuponload-6">Digital Social Innovation</h1>
                <h3 class="home-hero-h3 main" data-ix="fadeinuponload-7">Showcase your project, find collaborators and
                    find funding</h3>
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
        <div class="w-clearfix w-col w-col-9 w-col-stack">
            <div class="cta-text">
                <div class="home-hero-cta" data-ix="fadeinuponload-10">Join the community of <span class="sub-bold">1,345 organisations</span>
                    and <span class="sub-bold">731 projects</span>&nbsp;USING DIGITAL TECHNOLOGIES TO TACKLE SOCIAL
                    PROBLEMS
                </div>
            </div>
        </div>
        <div class="butcol w-col w-col-3 w-col-stack">
            <div class="signn">
                <a class="log-in-link sign-up w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="<?php echo $urlHandler->login() ?>">
                    <div class="login-li menu-li">Join now</div>
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
                <h3 class="top3-h3">Funding</h3>
                <div class="top3-underline" data-ix="new-interaction-2"></div>
                <p class="top-3-p">Use our funding directory to find opportunities for your project</p>
                <a class="log-in-link read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="<?php echo $urlHandler->funding() ?>">
                    <div class="login-li menu-li readmore-li">Read more</div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
            </div>
        </div>
        <div class="top-3-col w-col w-col-4" data-ix="fadeinuponload-13">
            <div class="top-3-link" data-ix="underline">
                <h3 class="top3-h3">Events</h3>
                <div class="top3-underline" data-ix="new-interaction-2"></div>
                <p class="top-3-p">Explore DSI events happening around Europe</p>
                <a class="log-in-link read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="<?php echo $urlHandler->events() ?>">
                    <div class="login-li menu-li readmore-li">Read more</div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
            </div>
        </div>
        <div class="top-3-col w-col w-col-4" data-ix="fadeinuponload-14">
            <div class="top-3-link" data-ix="underline">
                <h3 class="top3-h3">News &amp; Blogs</h3>
                <div class="top3-underline" data-ix="new-interaction-2"></div>
                <p class="top-3-p">Our blog features stories of the people and projects pioneering digital social
                    innovation</p>
                <a class="log-in-link read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="<?php echo $urlHandler->blogPosts() ?>">
                    <div class="login-li menu-li readmore-li">Read more</div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
            </div>
        </div>
    </div>
</div>
<div class="stats-bg">
    <div class="content">
        <h2 class="centered h2-large" data-ix="fadeinuponload-2">EXPLORE EUROPE’S GROWING NETWORK OF DIGITAL SOCIAL
            INNOVATION</h2>
        <div class="stat-text w-row">
            <div class="w-col w-col-5">
                <div class="number-of-orgs"
                     data-ix="fadeinuponload-3"><?php echo number_format($organisationCount) ?></div>
                <a class="organisations-2" data-ix="fadeinuponload-4" href="<?php echo $urlHandler->organisations() ?>">Organisations</a>
            </div>
            <div class="w-col w-col-2">
                <div class="have-collab" data-ix="fadeinuponload-5">have collaborated on</div>
            </div>
            <div class="w-col w-col-5">
                <div class="number-of-orgs pro"
                     data-ix="fadeinuponload-6"><?php echo number_format($projectsCount) ?></div>
                <a class="organisations-2" data-ix="fadeinuponload-7" href="<?php echo $urlHandler->projects() ?>">Projects</a>
            </div>
        </div>
    </div>
</div>
<div class="home-page-events">
    <div class="content-block cs">
        <h3 class="centered title">Case studies</h3>
        <div class="sub-header-centre">In need of inspiration?</div>
        <p class="centered">Our case studies tell the stories of the people and projects pioneering digital social
            innovation</p>
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
                            <div class="login-li menu-li readmore-li">Read more</div>
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
                <div class="login-li menu-li">See all case studies</div>
                <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
            </a>
        </div>
    </div>
</div>

<!-- -->

<?php require __DIR__ . '/footer.php' ?>