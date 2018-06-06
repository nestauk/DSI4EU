<?php

use Services\URL;

/** @var $loggedInUser \DSI\Entity\User */
if (!isset($loggedInUser))
    $loggedInUser = null;

if (!isset($urlHandler))
    $urlHandler = new URL();

?>
</div>
<!-- twitter block -->
<div class="twitter-block">
    <div class="twitter">
        <div class="w-row">
            <div class="w-col w-col-1 w-col-small-1 w-col-tiny-tiny-stack">
                <img class="homepage-twitter-logo" src="/images/twitter-logo-silhouette.png">
            </div>
            <div class="w-col w-col-11 w-col-small-11 w-col-tiny-tiny-stack">
                <p class="twitter-text">
                    <?php _e('To keep in touch with the project and DSI in Europe, follow us on Twitter @DSI4EU and sign up to our newsletter.'); ?>
                </p>
            </div>
        </div>
    </div>
</div>
<!-- end -->
<div class="footer-black">
    <div style="max-width:1290px;margin:0 auto;">
        <div class="footer-row w-row">
            <div class="w-col w-col-4">
                <a class="w-inline-block" href="<?php echo $urlHandler->home() ?>">
                    <img class="footer-logo" src="/images/partners/dsi-transparent.png">
                </a>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3"><?php _ehtml('People') ?></h3>
                <ul class="w-list-unstyled">
                    <?php if (!$loggedInUser) { ?>
                        <li class="footer-link">
                            <a class="footer-link"
                               href="<?php echo $urlHandler->register() ?>"><?php _ehtml('Join DSI4EU') ?></a>
                        </li>
                    <?php } ?>
                    <li class="footer-link">
                        <a class="footer-link"
                           href="<?php echo $urlHandler->termsOfUse() ?>"><?php _ehtml('Terms of use') ?></a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link"
                           href="<?php echo $urlHandler->privacyPolicy() ?>"><?php _ehtml('Privacy policy') ?></a>
                    </li>
                </ul>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3"><?php _ehtml('Projects') ?></h3>
                <ul class="w-list-unstyled">
                    <li class="footer-link">
                        <a class="footer-link"
                           href="<?php echo $urlHandler->projects() ?>"><?php _ehtml('View projects') ?></a>
                    </li>
                </ul>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3"><?php _ehtml('Organisations') ?></h3>
                <ul class="w-list-unstyled">
                    <li class="footer-link">
                        <a class="footer-link"
                           href="<?php echo $urlHandler->organisations() ?>"><?php _ehtml('View organisations') ?></a>
                    </li>
                </ul>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3"><?php _ehtml('Development') ?></h3>
                <ul class="w-list-unstyled">
                    <li class="footer-link">
                        <a class="footer-link"
                           href="<?php echo $urlHandler->updates() ?>"><?php _ehtml('Updates') ?></a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link"
                           href="<?php echo $urlHandler->feedback() ?>"><?php _ehtml('Feedback') ?></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-row-bottom w-row">
            <div class="w-col w-col-6">
                <div>
                    <div class="w-row">
                        <div class="w-clearfix w-col w-col-2 w-col-medium-6 w-col-small-6 w-col-tiny-6">
                            <img class="footer-eu" src="/images/5000100-mono.png">
                        </div>
                        <div class="w-col w-col-10 w-col-medium-6 w-col-small-6 w-col-tiny-6">
                            <div class="footer-small-print">
                                <?php _ehtml('DSI4EU, formally known as DSISCALE, is supported by the European Union and funded under the Horizon 2020 Programme, grant agreement no 780473.') ?>
                            </div>
                        </div>
                    </div>
                    <div class="w-row">
                        <div class="w-clearfix w-col w-col-2 w-col-medium-6 w-col-small-6 w-col-tiny-6">
                            <img class="cc footer-eu" src="/images/88x31.png">
                        </div>
                        <div class="w-col w-col-10 w-col-medium-6 w-col-small-6 w-col-tiny-6">
                            <div class="footer-small-print">
                                <?php echo sprintf(
                                    __('All our work is licensed under a %s, unless it says otherwise.'),
                                    '<span class="footer-link-small">
                                    <a class="footer-link-small"
                                       href="https://creativecommons.org/licenses/by-nc-sa/4.0/">
                                            Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>
                                </span>'
                                ) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-col w-col-6">
                <div class="w-row">
                    <div class="footer-logo-col w-clearfix w-col w-col-3">
                        <a class="footer-partner-link w-inline-block" href="http://nesta.org.uk" target="_blank">
                            <img class="footer-partner-logo" style="height:50px;width:auto"
                                 src="/images/home-logos/nesta.gif">
                        </a>
                    </div>
                    <div class="footer-logo-col w-clearfix w-col w-col-3">
                        <a class="footer-partner-link w-inline-block" href="http://www.betterplace-lab.org/en/"
                           target="_blank">
                            <img class="footer-partner-logo" style="height:50px;width:auto"
                                 src="/images/home-logos/betterplace.png">
                        </a>
                    </div>
                    <div class="footer-logo-col w-clearfix w-col w-col-3">
                        <a class="footer-partner-link w-inline-block" href="http://waag.org" target="_blank">
                            <img class="footer-partner-logo" style="height:50px;width:auto"
                                 src="/images/home-logos/waag.gif">
                        </a>
                    </div>
                    <div class="footer-logo-col w-clearfix w-col w-col-3">
                        <a class="footer-partner-link w-inline-block" href="http://wemake.cc" target="_blank">
                            <img class="footer-partner-logo" style="height:50px;width:auto"
                                 src="/images/home-logos/wemake.gif">
                        </a>
                    </div>
                    <div class="footer-logo-col w-clearfix w-col w-col-4">
                        <a class="footer-partner-link w-inline-block" href="http://fablabbcn.org" target="_blank">
                            <img class="footer-partner-logo" style="height:50px;width:auto"
                                 src="/images/home-logos/fablab.gif">
                        </a>
                    </div>
                    <div class="footer-logo-col w-clearfix w-col w-col-4">
                        <a class="footer-partner-link w-inline-block" href="http://www.barcelonactiva.cat/"
                           target="_blank">
                            <img class="footer-partner-logo" style="height:50px;width:auto"
                                 src="/images/home-logos/barcelona-activa.gif">
                        </a>
                    </div>
                    <div class="footer-logo-col w-clearfix w-col w-col-4">
                        <a class="footer-partner-link w-inline-block" href="http://epf.org.pl/" target="_blank">
                            <img class="footer-partner-logo" style="height:50px;width:auto"
                                 src="/images/home-logos/epantswo.png">
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="centre footer-small-print">
        <?php _ehtml('Nesta is a registered charity') ?>
    </div>
</div>

<?php /*
<div class="footer">
    <div class="container-wide">
        <div class="newsletter-block" data-ix="fadeinuponload-3">
            <div class="w-row">
                <div class="w-col w-col-6">
                    <h2 class="newsletter-signup-h2">Stay up to date with the DSI4EU newsletter</h2>
                </div>
                <div class="w-col w-col-6">
                    <div class="w-form">
                        <form class="w-clearfix">
                            <input class="newsletter-signup-input w-input" data-name="Email" id="email" maxlength="256"
                                   name="email" placeholder="Enter your email address" required="required" type="email">
                            <input class="newsletter-signup-submit w-button" data-wait="Please wait..." type="submit"
                                   value="Submit">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-columns w-row">
            <div class="w-col w-col-4">
                <div class="footer-logo">
                    <img class="footer-brand" src="/images/all white.svg">
                    <div class="beta-badge">Beta</div>
                </div>
            </div>

            <div class="w-col w-col-2">
                <h3 class="footer-h3"><?php _ehtml('People') ?></h3>
                <ul class="w-list-unstyled">
                    <?php /*
                    <li class="footer-link">
                        <a class="footer-link" href="#">Who uses DSI4EU?</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Help centre</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Report abuse</a>
                    </li>
                    * / ?>
                    <?php if ($loggedInUser) { ?>
                        <li class="footer-link">
                            <a href="<?php echo $urlHandler->dashboard() ?>"
                               class="footer-link"><?php _ehtml('Your dashboard') ?></a>
                        </li>
                    <?php } else { ?>
                        <li class="footer-link">
                            <a class="footer-link" href="#" data-ix="showsignup"><?php _ehtml('Join DSI4EU') ?></a>
                        </li>
                    <?php } ?>
                    <li class="footer-link">
                        <a class="footer-link" href="<?php echo $urlHandler->termsOfUse() ?>">
                            <?php _ehtml('Terms of use') ?></a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="<?php echo $urlHandler->privacyPolicy() ?>">
                            <?php _ehtml('Privacy policy') ?></a>
                    </li>
                </ul>
            </div>

            <div class="w-col w-col-2">
                <h3 class="footer-h3"><?php _ehtml('Projects') ?></h3>
                <ul class="w-list-unstyled">
                    <?php if ($loggedInUser) { ?>
                        <li class="footer-link">
                            <a class="footer-link" href="#" data-ix="create-project-modal">
                                <?php _ehtml('Add a project') ?></a>
                        </li>
                    <?php } ?>
                    <?php /*
                    <li class="footer-link">
                        <a class="footer-link" href="#">Report a&nbsp;project</a>
                    </li>
                    * / ?>
                    <li class="footer-link">
                        <a class="footer-link" href="<?php echo $urlHandler->projects() ?>">
                            <?php _ehtml('View projects') ?></a>
                    </li>
                </ul>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3"><?php _ehtml('Organisations') ?></h3>
                <ul class="w-list-unstyled">
                    <?php if ($loggedInUser) { ?>
                        <li class="footer-link">
                            <a class="footer-link" href="#" data-ix="create-organisation-modal">
                                <?php _ehtml('Add an organisation') ?></a>
                        </li>
                    <?php } ?>
                    <?php /*
                    <li class="footer-link">
                        <a class="footer-link" href="#">Report an organisation</a>
                    </li>
                    * / ?>
                    <li class="footer-link">
                        <a class="footer-link" href="<?php echo $urlHandler->organisations() ?>">
                            <?php _ehtml('View organisations') ?></a>
                    </li>
                </ul>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3"><?php _ehtml('Development') ?></h3>
                <ul class="w-list-unstyled">
                    <li class="footer-link">
                        <a class="footer-link" href="<?php echo $urlHandler->updates() ?>">
                            <?php _ehtml('Updates') ?></a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="<?php echo $urlHandler->feedback() ?>">
                            <?php _ehtml('Feedback') ?></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-partner-row w-row">
            <div class="w-clearfix w-col w-col-6">
                <div class="footer-small-print">
                    <?php _ehtml('Nesta is a registered charity') ?>
                </div>
                <img class="footer-cc-img" src="/images/88x31.png">
                <div class="footer-small-print">All our work is licensed under a&nbsp;
                    <a target="_blank" class="footer-link-small"
                       href="https://creativecommons.org/licenses/by-nc-sa/4.0/">
                        Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License
                    </a>, unless it says otherwise.
                </div>
            </div>
            <div class="w-clearfix w-col w-col-6">
                <a class="footer-partner-link w-inline-block" href="http://www.supsi.ch/" target="_blank">
                    <img class="footer-logo" src="/images/logo_SUPSI.png" width="125">
                </a>
                <a class="footer-partner-link w-inline-block" href="http://waag.org/en" target="_blank">
                    <img class="footer-logo"
                         src="/images/waag-f1d052f43133268eaf2e13090a0b4bf1.png"
                         width="125">
                </a>
                <a class="footer-partner-link w-inline-block" href="http://www.nesta.org.uk/" target="_blank">
                    <img class="footer-logo"
                         src="/images/nesta-6a9b5fe999e8323b379ccc0d8e70290f.png"
                         width="125">
                </a>
            </div>
        </div>
    </div>
</div>

<?php /*
<div class="footer w-section">
    <div class="container-wide">
        <div class="footer-columns w-row">
            <div class="w-col w-col-4">
                <img class="footer-brand" src="/images/all white.svg">
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3">People</h3>
                <ul class="w-list-unstyled">
                    <li class="footer-link">
                        <a class="footer-link" href="home-dashboard.html">Your dashboard</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Who uses DSI4EU?</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Join DSI4EU</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Help centre</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Report abuse</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Terms of service</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Privacy policy</a>
                    </li>
                </ul>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3">Projects</h3>
                <ul class="w-list-unstyled">
                    <li class="footer-link">
                        <a class="footer-link" href="#">Add a project to DSI4EU</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Report a&nbsp;project</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">View project listing</a>
                    </li>
                </ul>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3">Organisations</h3>
                <ul class="w-list-unstyled">
                    <li class="footer-link">
                        <a class="footer-link" href="#">Add an organisation</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Report an organisation</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">View partner organisations</a>
                    </li>
                </ul>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3">Development</h3>
                <a class="footer-link" href="feedback.html">Feedback</a>
            </div>
        </div>
        <div class="footer-partner-row w-row">
            <div class="w-col w-col-6"></div>
            <div class="w-clearfix w-col w-col-6">
                <a class="footer-partner-link w-inline-block" href="#">
                    <img src="/images/logo_SUPSI.png" width="125">
                </a>
                <a class="footer-partner-link w-inline-block" href="#">
                    <img src="/images/waag-f1d052f43133268eaf2e13090a0b4bf1.png"
                         width="125">
                </a>
                <a class="footer-partner-link w-inline-block" href="#">
                    <img src="/images/nesta-6a9b5fe999e8323b379ccc0d8e70290f.png"
                         width="125">
                </a>
            </div>
        </div>
    </div>
</div>
 */ ?>

<script type="text/javascript"
        src="/js/controllers/SearchController.js?<?= \Services\Assets::version() ?>"></script>

<div class="search-block" ng-controller="SearchController"
     data-langpath="<?php echo \DSI\Service\Translate::getCurrentLangPath() ?>">
    <div class="close-search" data-ix="close-search-block">+</div>
    <h1 class="search-h1"><?php _ehtml('Search') ?></h1>
    <div class="w-form">
        <form autocomplete="off">
            <input class="main-search w-input" data-name="search" id="search" maxlength="256" name="search"
                   autocomplete="off"
                   ng-model="search.entry"
                   ng-focus="search.focused = true"
                   ng-blur="search.focused = false"
                   placeholder="<?php _ehtml('Search news, projects, organisations and case studies') ?>" type="text">
            <a class="cancel-search" ng-click="search.entry = ''" data-ix="close-search-block"
               href="#"><?php _ehtml('Cancel') ?></a>
        </form>
    </div>
    <div ng-show="search.entry.length >= 3">
        <div class="main-search-results w-row">
            <div class="search-col w-col w-col-3">
                <h2 class="full-menu-h2"><?php _ehtml('News & blogs') ?></h2>
                <a ng-repeat="post in search.blogPosts" class="full-menu-link" href="{{post.url}}">
                    {{post.name}}
                </a>
                <div ng-show="search.blogPosts.length == 0"><?php _ehtml('No blog posts found') ?></div>
            </div>
            <div class="search-col w-col w-col-3">
                <h2 class="full-menu-h2"><?php _ehtml('Projects') ?></h2>
                <a ng-repeat="project in search.projects" class="full-menu-link" href="{{project.url}}">
                    {{project.name}}
                </a>
                <a class="full-menu-link view-all" ng-show="search.projects.length > 0"
                   href="<?php echo $urlHandler->projects() ?>?q={{search.entry}}">
                    <?php _ehtml('View all project results') ?>
                </a>
                <div ng-show="search.projects.length == 0"><?php _ehtml('No projects found') ?></div>
            </div>
            <div class="search-col w-col w-col-3">
                <h2 class="full-menu-h2"><?php _ehtml('Organisations') ?></h2>
                <a ng-repeat="organisation in search.organisations"
                   class="full-menu-link" href="{{organisation.url}}">
                    {{organisation.name}}
                </a>
                <a class="full-menu-link view-all" ng-show="search.organisations.length > 0"
                   href="<?php echo $urlHandler->organisations() ?>?q={{search.entry}}">
                    <?php _ehtml('View all organisation results') ?>
                </a>
                <div ng-show="search.organisations.length == 0"><?php _ehtml('No organisations found') ?></div>
            </div>
            <div class="search-col w-col w-col-3">
                <h2 class="full-menu-h2"><?php _ehtml('Case Studies') ?></h2>
                <a ng-repeat="caseStudy in search.caseStudies" class="full-menu-link" href="{{caseStudy.url}}">
                    {{caseStudy.name}}
                </a>
                <div ng-show="search.caseStudies.length == 0"><?php _ehtml('No case studies found') ?></div>
            </div>
        </div>
        <div class="signn">
            <a class="large log-in-link search-results sign-up w-clearfix w-inline-block" data-ix="log-in-arrow"
               href="<?php echo $urlHandler->search() ?>{{search.entry}}">
                <div class="login-li menu-li"><?php _ehtml('See all results') ?></div>
                <img class="login-arrow" src="/images/ios7-arrow-thin-right.png">
            </a>
        </div>
    </div>
</div>

<?php if ($loggedInUser AND !defined('HIDE_NOTIFICATIONS')) { ?>
    <div ng-controller="NotificationController" data-url="<?= $urlHandler->notifications() ?>">
        <div class="toast-notification" data-ix="toast-in" ng-show="notifications > 0">
            <a class="link-block w-clearfix w-inline-block" href="<?= $urlHandler->dashboard() ?>">
                <img class="image" height="75" width="75"
                     src="<?php echo \DSI\Entity\Image::PROFILE_PIC_URL . $loggedInUser->getProfilePicOrDefault() ?>">
                <div class="text-block-2"><span class="text-span">Digitalsocial.eu</span> dashboard</div>
                <div class="text-block-3">
                    <span ng-show="notifications == 1"><?php _ehtml('You have 1 new notification') ?></span>
                    <span ng-show="notifications > 1"><?php echo show_input(sprintf(__('You have %s new notifications'), '{{notifications}}')) ?></span>
                </div>
            </a>
        </div>
    </div>
    <script type="text/javascript"
            src="/js/controllers/NotificationController.js?<?= \Services\Assets::version() ?>"></script>
<?php } ?>

<div class="cookies" id="cookies">
    <div class="container-wide">
        <div class="w-row">
            <div class="w-col w-col-6">
                <h3 class="cookie-h3">
                    <?php _ehtml('We use cookies') ?>
                    <!-- We use cookies to help us improve this site and your experience. Continue to use this site if you're
                    happy with this or find out more -->
                </h3>
            </div>
            <div class="w-clearfix w-col w-col-6">
                <a class="cookie-button w-button"
                   href="<?= $urlHandler->cookiesPolicy() ?>"><?php _ehtml('Find out more') ?></a>
                <!-- <a class="cookie-button w-button js-cookie-accept" href="#"><?php _ehtml('Continue') ?></a> -->
                <a class="cookie-button w-button js-cookie-accept" href="#"><?php _ehtml('Accept') ?></a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"
        src="/js/dsi-languages.js?<?= \Services\Assets::version() ?>"></script>
<!--[if lte IE 9]>
<script src="/js/lib/placeholders/placeholders.min.js"></script>
<![endif]-->

<script>
    // Create Project / Organisation Dropdown
    (function () {
        var nav = $('nav.w-dropdown-list.create-drop-down');
        $('a', nav).on('click', function () {
            nav.removeClass('w--open');
        });
    }());
</script>

<script type="text/javascript"
        src="/main.js?<?= \Services\Assets::version() ?>"></script>

<?php include(__DIR__ . '/partialViews/googleAnalytics.html'); ?>

</body>
</html>