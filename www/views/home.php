<?php
/** @var $loggedInUser \DSI\Entity\User */
/** @var $isHomePage bool */
/** @var $angularModules string[] */
/** @var $pageTitle string[] */
/** @var $sliderCaseStudies \DSI\Entity\CaseStudy[] */
/** @var $homePageCaseStudies \DSI\Entity\CaseStudy[] */
/** @var $organisationsCount int */
/** @var $projectsCount int */
use DSI\Entity\Image;use DSI\Service\URL;

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
        <div class="w-clearfix w-col w-col-9 w-col-stack">
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
        <div class="butcol w-col w-col-3 w-col-stack">
            <div class="signn">
                <a class="log-in-link sign-up w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="<?php echo $urlHandler->login() ?>">
                    <div class="login-li menu-li"><?php _e('JOIN NOW') ?></div>
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
                    <div class="login-li menu-li readmore-li"><?php _ehtml('Read more')?></div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
            </div>
        </div>
        <div class="top-3-col w-col w-col-4" data-ix="fadeinuponload-13">
            <div class="top-3-link" data-ix="underline">
                <h3 class="top3-h3"><?php _ehtml('Events')?></h3>
                <div class="top3-underline" data-ix="new-interaction-2"></div>
                <p class="top-3-p"><?php _e('Explore DSI events happening around Europe') ?></p>
                <a class="log-in-link read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="<?php echo $urlHandler->events() ?>">
                    <div class="login-li menu-li readmore-li"><?php _ehtml('Read more')?></div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
            </div>
        </div>
        <div class="top-3-col w-col w-col-4" data-ix="fadeinuponload-14">
            <div class="top-3-link" data-ix="underline">
                <h3 class="top3-h3"><?php _ehtml('News & blogs')?></h3>
                <div class="top3-underline" data-ix="new-interaction-2"></div>
                <p class="top-3-p">
                    <?php _e('Our blog features stories of the people and projects pioneering digital social innovation') ?>
                </p>
                <a class="log-in-link read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="<?php echo $urlHandler->blogPosts() ?>">
                    <div class="login-li menu-li readmore-li"><?php _ehtml('Read more')?></div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
            </div>
        </div>
    </div>
</div>
<div class="stats-bg">
    <div class="content">
        <h2 class="centered h2-large" data-ix="fadeinuponload-2">
            <?php _e('EXPLORE EUROPE’S GROWING NETWORK OF DIGITAL SOCIAL INNOVATION') ?>
        </h2>
        <div class="stat-text w-row">
            <div class="w-col w-col-5">
                <div class="number-of-orgs"
                     data-ix="fadeinuponload-3"><?php echo number_format($organisationsCount) ?></div>
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
        <h3 class="centered title"><?php _ehtml('Case Studies')?></h3>
        <div class="sub-header-centre"><?php _e('IN NEED OF INSPIRATION?') ?></div>
        <p class="centered">
            <?php _ehtml('Short stories introducing digital social innovations which we love')?>
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
                            <div class="login-li menu-li readmore-li"><?php _ehtml('Read more')?></div>
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
                <div class="login-li menu-li"><?php _ehtml('See all case studies')?></div>
                <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
            </a>
        </div>
    </div>
</div>

<div class="newsletter-signup">
    <div class="mailchimp-container">
        <div class="w-row">
            <div class="w-col w-col-4">
                <div class="newsletter-title"><?php _ehtml('Newsletter')?></div>
                <div class="news-p"><?php _ehtml('Sign up to stay up to date with DSI4EU')?></div>
            </div>
            <div class="w-col w-col-8">
                <div class="w-form">
                    <div id="mc_embed_signup">
                        <form
                            action="//digitalsocial.us14.list-manage.com/subscribe/post?u=668c39c8408fd7322d7b61d39&amp;id=c2085cdb78"
                            method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                            class="validate" target="_blank" novalidate>
                            <div id="mc_embed_signup_scroll">
                                <div class="mc-field-group">
                                    <input type="text" value="" name="FNAME" class="signup-in w-input" id="mce-FNAME"
                                           placeholder="<?php _ehtml('First name')?>">
                                </div>
                                <div class="mc-field-group">
                                    <input type="text" value="" name="LNAME" class="signup-in w-input" id="mce-LNAME"
                                           placeholder="<?php _ehtml('Last name')?>">
                                </div>
                                <div class="mc-field-group">
                                    <input type="text" value="" name="EMAIL" class="signup-in w-input email"
                                           id="mce-EMAIL"
                                           placeholder="<?php _ehtml('Email address')?>">
                                </div>
                                <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                <div style="position: absolute; left: -5000px;" aria-hidden="true">
                                    <input type="text"
                                           name="b_668c39c8408fd7322d7b61d39_c2085cdb78"
                                           tabindex="-1"
                                           value="">
                                </div>
                                <div style="display: inline;">
                                    <input type="submit" value="<?php _ehtml('Subscribe')?>" name="subscribe"
                                           id="mc-embedded-subscribe"
                                           class="button footer-signup w-button">
                                </div>

                                <div id="mce-responses">
                                    <div class="response" id="mce-error-response"></div>
                                    <div class="response" id="mce-success-response"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Begin MailChimp Signup Form -->
<link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
<style type="text/css">
    /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
      We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */

    #mc_embed_signup .mc-field-group {
        clear: none;
        display: inline;
        width: auto;
    }

    #mc_embed_signup .mc-field-group input {
        width: auto;
        display: inline;
    }

    /* Homepage mailchimp styles */
    #mc_embed_signup .mc-field-group input {
        width: 21%;
        display: inline-block;
        border: 0px solid;
        border-bottom: 1px solid #fff;
        border-radius: 0;
        -moz-border-radius: 0;
        margin-top: -5px;
    }

    #mc_embed_signup .mc-field-group input:active {
        border-bottom: #1dc9a0;
    }

    #mc_embed_signup .button {
        clear: both;
        background-color: #1dc9a0;
        border: 1px solid #1dc9a0;
        border-radius: 25px;
        transition: all 0s ease-in-out 0s;
        color: #000;
        cursor: pointer;
        display: inline-block;
        font-size: 16px;
        font-weight: 600;
        height: 45px;
        line-height: 32px;
        margin: -8px 5px 10px 0;
        padding: 0px 13px;
        text-align: center;
        text-decoration: none;
        vertical-align: top;
        white-space: nowrap;
        width: 170px;
    }

    #mc_embed_signup .button:hover {
        background-color: #2A2E3A;
        color: #1dc9a0;
    }

    #mc_embed_signup #mc-embedded-subscribe-form div.mce_inline_error {
        display: inline-block;
        margin: 2px 0 1em 0;
        padding: 5px 10px;
        background-color: rgb(42, 46, 56);
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        font-size: 14px;
        font-weight: normal;
        z-index: 1;
        color: #e85c41;
        position: absolute;
        width: auto;
        top: -106px;
        left: -14px;
    }

    #mc_embed_signup #mce-success-response {
        color: #1dc9a0;
        display: none;
        position: absolute;
        background: #2a2e38;
        padding: 20px;
        border-radius: 10px;
        top: -134px;
    }

    @media screen and (max-width: 960px) {
        #mc_embed_signup .mc-field-group input {
            width: 100%;
            margin-top: 24px;
            margin-bottom: 10px;
            display: block;
        }

        #mc_embed_signup .button {
            margin-top: 5px;
            width: 100%;
        }

        .newsletter-signup{
            height:auto;
        }
    }

    /* end */

</style>

<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
<script type='text/javascript'>(function ($) {
        window.fnames = new Array();
        window.ftypes = new Array();
        fnames[0] = 'EMAIL';
        ftypes[0] = 'email';
        fnames[1] = 'FNAME';
        ftypes[1] = 'text';
        fnames[2] = 'LNAME';
        ftypes[2] = 'text';
    }(jQuery));
    var $mcj = jQuery.noConflict(true);</script>
<!--End mc_embed_signup-->

<?php require __DIR__ . '/footer.php' ?>