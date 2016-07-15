<?php
/** @var $loggedInUser \DSI\Entity\User */
/** @var $isHomePage bool */
/** @var $angularModules string[] */
/** @var $pageTitle string[] */
use \DSI\Service\URL;
use \DSI\Service\Sysctl;

?>
<!DOCTYPE html>
<html data-wf-site="56e2e31a1b1f8f784728a08c" data-wf-page="56fbef6ecf591b312d56f8be">
<head>
    <?php require __DIR__ . '/partialViews/head.php' ?>
</head>
<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/SearchController.js?v=<?php echo Sysctl::$version ?>"></script>
<body ng-app="DSIApp" ng-controller="SearchController" id="top">

<div class="alt nav-main w-nav white-menu" data-animation="default" data-collapse="medium" data-duration="400">
    <div class="container-wide menu w-clearfix">
        <a class="w-nav-brand" href="<?php echo URL::home() ?>">
            <img class="logo-dark" src="<?php echo SITE_RELATIVE_PATH ?>/images/dark.svg">
        </a>
        <nav class="w-nav-menu" role="navigation">
            <a class="alt nav w-nav-link" href="<?php echo URL::exploreDSI() ?>">Explore DSI</a>
            <a class="alt nav w-nav-link" href="<?php echo URL::caseStudies() ?>">Case Studies</a>
            <a class="alt nav w-nav-link" href="<?php echo URL::stories() ?>">Blog</a>
            <a class="alt nav w-nav-link" href="<?php echo URL::projects() ?>">Projects</a>
            <a class="alt nav w-nav-link" href="<?php echo URL::organisations() ?>">Organisations</a>
            <?php if (isset($loggedInUser) AND $loggedInUser) { ?>
                <div class="w-dropdown" data-delay="0">
                    <div class="log-in nav w-dropdown-toggle">
                        <div>Create&nbsp;</div>
                    </div>
                    <nav class="create-drop-down w-dropdown-list">
                        <a class="drop-down-link w-dropdown-link" data-ix="create-project-modal" href="#">
                            Create a new project</a>
                        <a class="drop-down-link w-dropdown-link" data-ix="create-organisation-modal" href="#">
                            Create an organisation
                        </a>
                        <div class="arror-up"></div>
                    </nav>
                </div>
            <?php } else { ?>
                <a class="alt log-in nav w-nav-link white-alt" data-ix="open-login-modal" href="#">Login</a>
                <a class="alt log-in log-in-alt nav w-nav-link" href="#">Signup</a>
            <?php } ?>
        </nav>
        <div class="w-nav-button">
            <div class="w-icon-nav-menu"></div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partialViews/loginModal.php' ?>

<!--
<div data-collapse="medium" data-animation="default" data-duration="400" class="w-nav nav-main">
    <div class="w-clearfix container-wide nav-container">
        <a href="<?php echo SITE_RELATIVE_PATH ?>/" class="w-nav-brand">
            <img width="160" src="<?php echo SITE_RELATIVE_PATH ?>/images/all white.svg" class="brand">
        </a>
        <nav role="navigation" class="w-nav-menu m-nav-open <?php echo isset($isHomePage) ? 'homePageColours' : '' ?>">
            <a href="<?php echo URL::exploreDSI() ?>" class="w-nav-link nav">Explore DSI</a>
            <a href="<?php echo URL::stories() ?>" class="w-nav-link nav">Stories</a>
            <a href="<?php echo URL::projects() ?>" class="w-nav-link nav">Projects</a>
            <a href="<?php echo URL::organisations() ?>" class="w-nav-link nav">Organisations</a>
            <?php if (!isset($_SESSION['user'])) { ?>
                <a href="#" data-ix="open-login-modal" class="w-nav-link nav log-in">Log In</a>
            <?php } else { ?>
                <div class="w-dropdown" data-delay="0">
                    <div class="w-dropdown-toggle nav log-in">
                        <div>Create +</div>
                    </div>
                    <nav class="w-dropdown-list create-drop-down">
                        <a class="w-dropdown-link drop-down-link" data-ix="create-project-modal" href="#">Create a new
                            project</a>
                        <a class="w-dropdown-link drop-down-link" data-ix="create-organisation-modal" href="#">Create an
                            organisation</a>
                        <div class="arror-up"></div>
                    </nav>
                </div>
            <?php } ?>
        </nav>
        <div class="w-nav-button">
            <div class="w-icon-nav-menu m-menu-btn"></div>
        </div>
    </div>
</div>
-->

<?php if (!isset($hideSearch) OR $hideSearch !== true) { ?>
    <div class="search bg-blur">
        <div class="dark-bg-overlay"></div>
        <div class="container-wide search-container">
            <div class="w-row top-row-personal">
                <div class="w-col w-col-5 w-col-small-5 w-clearfix">
                    <?php if (isset($loggedInUser)) { ?>
                        <div class="profile-popover bg-blur">
                            <a href="<?php echo SITE_RELATIVE_PATH ?>/my-profile" data-ix="popoverfadeout"
                               class="popover-link">
                                View profile
                            </a>
                            <a href="<?php echo URL::editProfile() ?>" data-ix="popoverfadeout"
                               class="popover-link">
                                Edit Profile
                            </a>
                            <a href="<?php echo SITE_RELATIVE_PATH ?>/logout" class="popover-link">
                                Sign out
                            </a>
                        </div>
                        <img width="15" src="<?php echo SITE_RELATIVE_PATH ?>/images/vertical-nav.png"
                             data-ix="showpopover"
                             class="vert-nav">
                        <a href="<?php echo SITE_RELATIVE_PATH ?>/my-profile"
                           class="w-inline-block w-clearfix link-to-profile">
                            <div class="profile-img"
                                 style="background-image: url('<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $loggedInUser->getProfilePicOrDefault() ?>');">

                            </div>
                            <h3 class="profile-name">
                                <?php echo $loggedInUser->getFirstName() ?>
                                <?php echo $loggedInUser->getLastName() ?>
                            </h3>
                            <h3 class="profile-name profile-organisation"><?php echo $loggedInUser->getJobTitle() ?></h3>
                        </a>
                    <?php } ?>
                </div>
                <div class="w-col w-col-7 w-col-small-7">
                    <div class="w-form">
                        <form class="w-clearfix search-input" id="email-form">
                            <input class="w-input search-field quicksearch" id="Search"
                                   maxlength="256" name="Search" placeholder="Search digitalsocial.eu" type="text"
                                   ng-model="search.entry" ng-focus="search.focused = true"
                                   ng-blur="search.focused = false">
                            <div ng-cloak ng-show="search.entry.length > 0" class="search-clear"
                                 ng-click="search.entry = ''">clear
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="w-section body">