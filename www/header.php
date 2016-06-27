<?php
/** @var $loggedInUser \DSI\Entity\User */
/** @var $isHomePage bool */
/** @var $angularModules string[] */
use \DSI\Service\URL;
use \DSI\Service\Sysctl;

?>
<!DOCTYPE html>
<html data-wf-site="56e2e31a1b1f8f784728a08c" data-wf-page="56fbef6ecf591b312d56f8be">
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <meta property="og:title" content="Home">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="Webflow">
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_RELATIVE_PATH ?>/lib/ionicons/css/ionicons.min.css?v=<?php echo Sysctl::$version ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_RELATIVE_PATH ?>/css/normalize.css?v=<?php echo Sysctl::$version ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_RELATIVE_PATH ?>/css/components.css?v=<?php echo Sysctl::$version ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_RELATIVE_PATH ?>/css/dsi4eu.css?v=<?php echo Sysctl::$version ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_RELATIVE_PATH ?>/css/custom.css?v=<?php echo Sysctl::$version ?>">

    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Open Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic"]
            }
        });
    </script>
    <script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH ?>/js/modernizr.js?v=<?php echo Sysctl::$version ?>"></script>

    <link rel="shortcut icon" type="image/x-icon" href="<?php echo SITE_RELATIVE_PATH ?>/images/ico-small.png">
    <link rel="apple-touch-icon" href="<?php echo SITE_RELATIVE_PATH ?>/images/ico-large.png">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js"></script>

    <?php /** jQuery */ ?>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

    <?php /** SweetAlert */ ?>
    <script src="<?php echo SITE_RELATIVE_PATH ?>/lib/sweetalert-master/dist/sweetalert.min.js?v=<?php echo Sysctl::$version ?>"></script>
    <link rel="stylesheet" type="text/css"
          href="<?php echo SITE_RELATIVE_PATH ?>/lib/sweetalert-master/dist/sweetalert.css?v=<?php echo Sysctl::$version ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_RELATIVE_PATH ?>/css/sweet.css?v=<?php echo Sysctl::$version ?>">

    <?php /** Select2 */ ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.full.min.js"></script>

    <script>
        var SITE_RELATIVE_PATH = '<?php echo SITE_RELATIVE_PATH?>';
        var angularDependencies = [];
        var angularAppName = 'DSIApp';
    </script>

    <style>
        .bg-blur {
            -webkit-backdrop-filter: saturate(180%) blur(5px);
            backdrop-filter: saturate(180%) blur(5px);
        }

        .blur-20 {
            -webkit-backdrop-filter: saturate(180%) blur(20px);
            backdrop-filter: saturate(180%) blur(20px);
        }

        .el-blur {
            -webkit-filter: blur(25px);
            -moz-filter: blur(25px);
            -o-filter: blur(25px);
            -ms-filter: blur(25px);
            filter: blur(25px);
        }

        .login-field.error {
            height: 50px;
            border-style: none none solid;
            border-width: 1px;
            border-color: #000 #000 #FF3030;
            background-color: #fff;
            text-align: center;
        }

        .w-input::-webkit-input-placeholder { /* WebKit browsers */
            color: #4CADDE;
        }

        .w-input:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
            color: #4CADDE;
        }

        .w-input::-moz-placeholder { /* Mozilla Firefox 19+ */
            color: #4CADDE;
        }

        .w-input:-ms-input-placeholder { /* Internet Explorer 10+ */
            color: #4CADDE;
        }

        /* keep modals centered */
        .modal-container {
            display: table;
            height: 100%;
            position: absolute;
            overflow: hidden;
            width: 100%;
        }

        .modal-helper {
            #position: absolute;
            #top: 50%;
            display: table-cell;
            vertical-align: middle;
        }

        .modal-content {
            #position: relative;
            #top: -50%;
            margin: 0 auto;
            width: 600px;
        }
    </style>

    <script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH ?>/js/angular.min.js?v=<?php echo Sysctl::$version ?>"></script>

    <?php if (isset($angularModules['fileUpload'])) { ?>
        <?php /** ngFileUpload */ ?>
        <script src="<?php echo SITE_RELATIVE_PATH ?>/js/lib/ng-file-upload-bower/ng-file-upload-shim.min.js?v=<?php echo Sysctl::$version ?>"></script>
        <!-- for no html5 browsers support -->
        <script src="<?php echo SITE_RELATIVE_PATH ?>/js/lib/ng-file-upload-bower/ng-file-upload.min.js?v=<?php echo Sysctl::$version ?>"></script>
        <script>angularDependencies.push('ngFileUpload');</script>
    <?php } ?>

    <?php if (isset($angularModules['animate'])) { ?>
        <script type="text/javascript"
                src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-animate.js"></script>
        <script>angularDependencies.push('ngAnimate');</script>
    <?php } ?>

    <?php if (isset($angularModules['pagination'])) { ?>
        <link rel="stylesheet" type="text/css"
              href="<?php echo SITE_RELATIVE_PATH ?>/lib/bootstrap-pagination/bootstrap-pagination.css?v=<?php echo Sysctl::$version ?>">
        <script type="text/javascript"
                src="<?php echo SITE_RELATIVE_PATH ?>/lib/bootstrap-pagination/ui-bootstrap-tpls-0.2.0.js?v=<?php echo Sysctl::$version ?>"></script>
        <script>angularDependencies.push('ui.bootstrap');</script>
    <?php } ?>

    <script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH ?>/js/DSIApp.js?v=<?php echo Sysctl::$version ?>"></script>
</head>
<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/SearchController.js?v=<?php echo Sysctl::$version ?>"></script>
<body ng-app="DSIApp" ng-controller="SearchController" id="top">
<?php if (!isset($_SESSION['user'])) { ?>
    <script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/LoginController.js?v=<?php echo Sysctl::$version ?>"></script>

    <div class="login-modal modal" ng-controller="LoginController">
        <div class="modal-container">
            <div class="modal-helper">
                <div class="modal-content">
                    <h2 class="centered modal-h2 log-in" ng-bind="forgotPassword.show ? 'Reset password' : 'Log in'">Log
                        in</h2>
                    <div class="w-form">
                        <a href="#" class="login-modal-back" ng-show="forgotPassword.show"
                           ng-click="forgotPassword = {}">Back to login</a>

                        <div ng-hide="forgotPassword.show">
                            <form id="email-form-3" name="email-form-3" ng-submit="onSubmit()">
                                <input class="w-input modal-input log-in"
                                       id="Enter-your-email-address" maxlength="256" name="Enter-your-email-address"
                                       placeholder="Enter your email address" type="email"
                                       ng-model="email.value"
                                       ng-class="{error: errors.email}">
                                <div style="color:red;text-align:center" ng-show="errors.email"
                                     ng-bind="errors.email"></div>
                                <input class="w-input modal-input" data-name="password" id="password-6" maxlength="256"
                                       name="password" placeholder="Password" type="password"
                                       ng-model="password.value"
                                       ng-class="{error: errors.password}">
                                <div style="color:red;text-align:center" ng-show="errors.password"
                                     ng-bind="errors.password"></div>
                                <br/>
                                <a class="forgotten-password" href="#" ng-click="forgotPassword.show = true">Forgotten
                                    password?</a>

                                    <input class="w-button dsi-button creat-button"
                                           type="submit" ng-hide="loggedin"
                                           value="Log in"
                                           ng-value="loading ? 'Loading...' : 'Log in'"
                                           ng-disabled="loading">
                                    <input class="w-button dsi-button creat-button"
                                           ng-show="loggedin" type="button"
                                           style="width:auto"
                                           value="Welcome back to Digital Social!">
                            </form>
                        </div>
                        <div ng-show="forgotPassword.show" class="w-tab-screen" style="text-align:center">
                            <form ng-hide="forgotPassword.complete"
                                  ng-submit="forgotPasswordSubmit()"
                                  autocomplete="off">
                                <input type="email" placeholder="Enter your email address"
                                       name="email"
                                       class="w-input modal-input"
                                       ng-model="email.value"
                                       ng-class="{error: forgotPassword.errors.email}">
                                <div style="color:red;text-align:center" ng-show="forgotPassword.errors.email"
                                     ng-bind="forgotPassword.errors.email"></div>
                                <div ng-show="forgotPassword.codeSent">
                                    <div ng-hide="forgotPassword.codeVerified">
                                        <i ng-hide="forgotPassword.codeVerified">
                                            The security code has been emailed to you.
                                        </i>
                                        <input type="text" placeholder="Security code"
                                               name="code"
                                               class="w-input modal-input"
                                               ng-disabled="forgotPassword.codeVerified"
                                               ng-model="forgotPassword.code"
                                               ng-class="{error: forgotPassword.errors.code}">
                                        <div style="color:red;text-align:center" ng-show="forgotPassword.errors.code"
                                             ng-bind="forgotPassword.errors.code"></div>
                                    </div>
                                    <div ng-show="forgotPassword.codeVerified">
                                        <input type="password" placeholder="New Password"
                                               name="password"
                                               class="w-input modal-input"
                                               ng-model="forgotPassword.password"
                                               autocomplete="off"
                                               ng-class="{error: forgotPassword.errors.password}">
                                        <div style="color:red" ng-show="forgotPassword.errors.password"
                                             ng-bind="forgotPassword.errors.password"></div>

                                        <input type="password" placeholder="Retype Password"
                                               name="retypePassword"
                                               class="w-input modal-input"
                                               ng-model="forgotPassword.retypePassword"
                                               autocomplete="off"
                                               ng-class="{error: forgotPassword.errors.retypePassword}">
                                        <div style="color:red" ng-show="forgotPassword.errors.retypePassword"
                                             ng-bind="forgotPassword.errors.retypePassword"></div>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <input type="submit" class="w-button dsi-button creat-button" style="width:auto"
                                           ng-disabled="forgotPassword.loading"
                                           ng-value="forgotPassword.loading ? 'Loading...' : 'Reset my password'">
                                </div>

                            </form>
                            <div ng-show="forgotPassword.complete"
                                 style="font-size:18px;line-height:24px;padding-top:70px">
                                Your password has changed.<br/>
                                <a href="#" ng-click="forgotPassword = {}">
                                    Click here
                                </a> to login using your new password.
                            </div>
                        </div>
                    </div>
                    <div class="cancel" ng-hide="loggedin">
                        <a href="#" data-ix="close-nu-modal">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

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