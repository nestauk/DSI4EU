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
<?php if (!isset($_SESSION['user'])) { ?>

<?php require __DIR__ . '/partialViews/loginModal.php' ?>

    <div class="modal-signup bg-blur">
        <div data-ix="downbeforeup" class="signup-form">
            <div data-duration-in="300" data-duration-out="100" data-easing="ease-in-out"
                 class="w-tabs modal-push-buttons">
                <div class="w-tab-content tabs-content">
                    <div data-w-tab="Tab 1" class="w-tab-pane w--tab-active" ng-controller="LoginController">
                        <div class="w-form login-form">
                            <div ng-hide="forgotPassword.show" class="w-tab-screen">
                                <form id="email-form" name="email-form" ng-submit="onSubmit()">
                                    <input type="email" placeholder="Enter your email address"
                                           name="email"
                                           data-name="Email 4" autofocus="autofocus"
                                           class="w-input login-field"
                                           ng-model="email.value"
                                           ng-class="{error: errors.email}">
                                    <div style="color:red" ng-show="errors.email" ng-bind="errors.email"></div>
                                    <input type="password" placeholder="Password"
                                           name="Password" class="w-input login-field"
                                           ng-model="password.value"
                                           ng-class="{error: errors.password}">
                                    <div style="color:red" ng-show="errors.password" ng-bind="errors.password"></div>

                                    <div ng-hide="loggedin">
                                        <input ng-hide="loading" type="submit" value="Login" data-wait="Please wait..."
                                               class="w-button login-button">
                                        <button ng-show="loading" type="button" class="w-button login-button register">
                                            Loading...
                                        </button>
                                        <a href="#" class="forgotten-password" ng-click="forgotPassword.show = true">Forgotten
                                            password?</a>
                                    </div>
                                    <button ng-show="loggedin" type="button" class="w-button login-button register">
                                        Welcome back to Digital Social!
                                    </button>

                                    <div class="w-row social-badges">
                                        <div class="w-col w-col-3 w-col-small-3 w-col-tiny-3">
                                            <a href="<?php echo URL::loginWithGitHub() ?>"
                                               class="w-inline-block social-login">
                                                <img width="100%" height="100%"
                                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/social-1_square-github.svg"
                                                     class="social-badge">
                                            </a>
                                        </div>
                                        <div class="w-col w-col-3 w-col-small-3 w-col-tiny-3">
                                            <a href="<?php echo URL::loginWithFacebook() ?>"
                                               class="w-inline-block social-login">
                                                <img width="100%" height="100%"
                                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/social-1_square-facebook.svg"
                                                     class="social-badge">
                                            </a>
                                        </div>
                                        <div class="w-col w-col-3 w-col-small-3 w-col-tiny-3">
                                            <a href="<?php echo URL::loginWithGoogle() ?>"
                                               class="w-inline-block social-login">
                                                <img width="100%" height="100%"
                                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/social-1_square-google-plus.svg"
                                                     class="social-badge">
                                            </a>
                                        </div>
                                        <div class="w-col w-col-3 w-col-small-3 w-col-tiny-3">
                                            <a href="<?php echo URL::loginWithTwitter() ?>"
                                               class="w-inline-block social-login">
                                                <img width="100%" height="100%"
                                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/social-1_square-twitter.svg"
                                                     class="social-badge">
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div ng-show="forgotPassword.show" class="w-tab-screen">
                                <form ng-hide="forgotPassword.complete"
                                      ng-submit="forgotPasswordSubmit()"
                                      autocomplete="off">
                                    <input type="email" placeholder="Enter your email address"
                                           name="email"
                                           class="w-input login-field"
                                           ng-model="email.value"
                                           ng-class="{error: forgotPassword.errors.email}">
                                    <div style="color:red" ng-show="forgotPassword.errors.email"
                                         ng-bind="forgotPassword.errors.email"></div>
                                    <div ng-show="forgotPassword.codeSent">
                                        <div ng-hide="forgotPassword.codeVerified">
                                            <i ng-hide="forgotPassword.codeVerified">The security code has been emailed
                                                to you.</i>
                                            <input type="text" placeholder="Security code"
                                                   name="code"
                                                   class="w-input login-field"
                                                   ng-disabled="forgotPassword.codeVerified"
                                                   ng-model="forgotPassword.code"
                                                   ng-class="{error: forgotPassword.errors.code}">
                                            <div style="color:red" ng-show="forgotPassword.errors.code"
                                                 ng-bind="forgotPassword.errors.code"></div>
                                        </div>
                                        <div ng-show="forgotPassword.codeVerified">
                                            <input type="password" placeholder="New Password"
                                                   name="password"
                                                   class="w-input login-field"
                                                   ng-model="forgotPassword.password"
                                                   autocomplete="off"
                                                   ng-class="{error: forgotPassword.errors.password}">
                                            <div style="color:red" ng-show="forgotPassword.errors.password"
                                                 ng-bind="forgotPassword.errors.password"></div>

                                            <input type="password" placeholder="Retype Password"
                                                   name="retypePassword"
                                                   class="w-input login-field"
                                                   ng-model="forgotPassword.retypePassword"
                                                   autocomplete="off"
                                                   ng-class="{error: forgotPassword.errors.retypePassword}">
                                            <div style="color:red" ng-show="forgotPassword.errors.retypePassword"
                                                 ng-bind="forgotPassword.errors.retypePassword"></div>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <input type="submit" class="w-button login-button"
                                               ng-disabled="forgotPassword.loading"
                                               ng-value="forgotPassword.loading ? 'Loading...' : 'Reset my password'">

                                        <a href="#" class="forgotten-password"
                                           ng-click="forgotPassword = {}">
                                            Back to login
                                        </a>
                                    </div>

                                </form>
                                <div ng-show="forgotPassword.complete"
                                     style="font-size:18px;line-height:24px;padding-top:70px">
                                    Your password has changed.<br/>
                                    <a href="#"
                                       ng-click="forgotPassword = {}">
                                        Click here
                                    </a>
                                    to login using your new password.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/CreateProjectOrganisationController.js?v=<?php echo Sysctl::$version ?>"></script>

    <div ng-controller="CreateProjectOrganisationController">
        <div class="create-project-modal modal">
            <div class="modal-container">
                <div class="modal-helper">
                    <div class="modal-content">
                        <h2 class="centered modal-h2">Create project</h2>
                        <div class="w-form">
                            <form id="email-form-3" name="email-form-3" ng-submit="createProject()">
                                <div style="color:red;text-align:center" ng-show="project.errors.name"
                                     ng-bind="project.errors.name"></div>
                                <input class="w-input modal-input" id="name-3" maxlength="256"
                                       name="name" placeholder="Enter the name of your project" type="text"
                                       ng-model="project.name" ng-class="{error: project.errors.name}">
                                <input class="w-button dsi-button creat-button" type="submit"
                                       value="Create +"
                                       ng-value="project.loading ? 'Loading...' : 'Create +'"
                                       ng-disabled="project.loading">
                            </form>
                        </div>
                        <div class="cancel" data-ix="close-nu-modal">Cancel</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="create-organisation-modal modal">
            <div class="modal-container">
                <div class="modal-helper">
                    <div class="modal-content">
                        <h2 class="centered modal-h2">Create organisation</h2>
                        <div class="w-form">
                            <form id="email-form-3" name="email-form-3" ng-submit="createOrganisation()">
                                <div style="color:red;text-align:center" ng-show="organisation.errors.name"
                                     ng-bind="organisation.errors.name"></div>
                                <input class="w-input modal-input" id="name-3" maxlength="256"
                                       name="name" placeholder="Enter the name of your organisation" type="text"
                                       ng-model="organisation.name"
                                       ng-class="{error: organisation.errors.name}">
                                <input class="w-button dsi-button creat-button" data-wait="Please wait..." type="submit"
                                       value="Create +"
                                       ng-value="organisation.loading ? 'Loading...' : 'Create +'"
                                       ng-disabled="organisation.loading">
                            </form>
                        </div>
                        <div class="cancel" data-ix="close-nu-modal">Cancel</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

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
                        <img width="15" src="<?php echo SITE_RELATIVE_PATH ?>/images/white-settings.png"
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