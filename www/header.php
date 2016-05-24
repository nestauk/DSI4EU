<?php
/** @var $loggedInUser \DSI\Entity\User */
/** @var $isHomePage bool */
?>
<!DOCTYPE html>
<!-- Last Published: Tue Apr 26 2016 15:33:26 GMT+0000 (UTC) -->
<html data-wf-site="56e2e31a1b1f8f784728a08c" data-wf-page="56fbef6ecf591b312d56f8be">
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <meta property="og:title" content="Home">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="Webflow">
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_RELATIVE_PATH ?>/css/normalize.css">
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_RELATIVE_PATH ?>/css/components.css">
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_RELATIVE_PATH ?>/css/dsi4eu.css">
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_RELATIVE_PATH ?>/css/custom.css">

    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Open Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic"]
            }
        });
    </script>
    <script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH ?>/js/modernizr.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="https://daks2k3a4ib2z.cloudfront.net/img/favicon.ico">
    <link rel="apple-touch-icon" href="https://daks2k3a4ib2z.cloudfront.net/img/webclip.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js"></script>

    <?php /** jQuery */ ?>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

    <?php /** Select2 */ ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.full.min.js"></script>

    <script>
        var SITE_RELATIVE_PATH = '<?php echo SITE_RELATIVE_PATH?>';
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

        input[type=text] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
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
    </style>

    <script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH ?>/js/angular.min.js"></script>

    <?php /** ngFileUpload */ ?>
    <script src="<?php echo SITE_RELATIVE_PATH ?>/js/lib/ng-file-upload-bower/ng-file-upload-shim.min.js"></script>
    <!-- for no html5 browsers support -->
    <script src="<?php echo SITE_RELATIVE_PATH ?>/js/lib/ng-file-upload-bower/ng-file-upload.min.js"></script>

    <script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH ?>/js/DSIApp.js"></script>
</head>
<body ng-app="DSIApp">
<?php if (!isset($_SESSION['user'])) { ?>
    <div class="modal-signup bg-blur">
        <div data-ix="downbeforeup" class="signup-form">
            <div class="modal-header"></div>
            <div data-ix="destroysignup" class="close modal-close">+</div>
            <img width="160" src="<?php echo SITE_RELATIVE_PATH ?>/images/logo-white.svg" class="modal-brand">
            <div data-duration-in="300" data-duration-out="100" data-easing="ease-in-out" class="w-tabs">
                <div class="w-tab-menu tabs-menu">
                    <a data-w-tab="Tab 1" class="w-tab-link w--current w-inline-block tab">
                        <div>Login</div>
                    </a>
                    <a data-w-tab="Tab 2" class="w-tab-link w-inline-block tab">
                        <div>Register</div>
                    </a>
                </div>
                <div class="w-tab-content tabs-content">
                    <div data-w-tab="Tab 1" class="w-tab-pane w--tab-active" ng-controller="LoginController">
                        <div class="w-form login-form">
                            <div ng-hide="forgotPassword.show" class="w-tab-screen">
                                <form id="email-form" name="email-form" data-name="Email Form" ng-submit="onSubmit()">
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
                                            <a href="<?php echo SITE_RELATIVE_PATH ?>/github-login"
                                               class="w-inline-block social-login">
                                                <img width="100%" height="100%"
                                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/social-1_square-github.svg"
                                                     class="social-badge">
                                            </a>
                                        </div>
                                        <div class="w-col w-col-3 w-col-small-3 w-col-tiny-3">
                                            <a href="<?php echo SITE_RELATIVE_PATH ?>/facebook-login"
                                               class="w-inline-block social-login">
                                                <img width="100%" height="100%"
                                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/social-1_square-facebook.svg"
                                                     class="social-badge">
                                            </a>
                                        </div>
                                        <div class="w-col w-col-3 w-col-small-3 w-col-tiny-3">
                                            <a href="<?php echo SITE_RELATIVE_PATH ?>/google-login"
                                               class="w-inline-block social-login">
                                                <img width="100%" height="100%"
                                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/social-1_square-google-plus.svg"
                                                     class="social-badge">
                                            </a>
                                        </div>
                                        <div class="w-col w-col-3 w-col-small-3 w-col-tiny-3">
                                            <a href="<?php echo SITE_RELATIVE_PATH ?>/twitter-login"
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
                    <div data-w-tab="Tab 2" class="w-tab-pane" ng-controller="RegisterController">
                        <div class="w-form login-form">
                            <form id="email-form" name="email-form" data-name="Email Form" ng-submit="onSubmit()">
                                <input id="email-5" type="email" placeholder="Enter your email address" name="email-5"
                                       data-name="Email 5" autofocus="autofocus"
                                       class="w-input login-field"
                                       ng-model="email.value"
                                       ng-class="{error: errors.email}">
                                <div style="color:red" ng-show="errors.email" ng-bind="errors.email"></div>
                                <input id="Password-5" type="password" placeholder="Password" name="Password-5"
                                       data-name="Password 5" class="w-input login-field"
                                       ng-model="password.value"
                                       ng-class="{error: errors.password}">
                                <div style="color:red" ng-show="errors.password" ng-bind="errors.password"></div>

                                <div class="modal-footer">
                                    <div ng-hide="registered">
                                        <input type="submit"
                                               ng-disabled="loading"
                                               ng-value="loading ? 'Loading...' : 'Register'"
                                               class="w-button login-button register">
                                    </div>
                                    <button ng-show="registered" type="button" class="w-button login-button register">
                                        Welcome to Digital Social!
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/CreateProjectOrganisationController.js"></script>
    <div class="modal-signup bg-blur">
        <div data-ix="downbeforeup" class="signup-form">
            <div class="modal-header"></div>
            <div data-ix="destroysignup" class="close modal-close">+</div>
            <img width="160" src="<?php echo SITE_RELATIVE_PATH ?>/images/logo-white.svg" class="modal-brand">
            <div data-duration-in="300" data-duration-out="100" data-easing="ease-in-out" class="w-tabs">
                <div class="w-tab-menu tabs-menu">
                    <a data-w-tab="Tab 1" class="w-tab-link w--current w-inline-block tab">
                        <div>Project</div>
                    </a>
                    <a data-w-tab="Tab 2" class="w-tab-link w-inline-block tab">
                        <div>Organisation</div>
                    </a>
                </div>
                <div class="w-tab-content tabs-content" ng-controller="CreateProjectOrganisationController">
                    <div data-w-tab="Tab 1" class="w-tab-pane w--tab-active">
                        <div class="w-form login-form">
                            <form ng-submit="createProject()">
                                <input type="text" placeholder="Enter project name" name="project"
                                       autofocus="autofocus"
                                       class="w-input login-field"
                                       ng-model="project.name"
                                       ng-class="{error: project.errors.name}">
                                <div style="color:red" ng-show="project.errors.name"
                                     ng-bind="project.errors.name"></div>
                                <input ng-hide="project.loading" type="submit" value="Create Project"
                                       class="w-button login-button">
                                <button ng-show="project.loading" type="button" class="w-button login-button register">
                                    Loading...
                                </button>
                            </form>
                        </div>
                    </div>
                    <div data-w-tab="Tab 2" class="w-tab-pane">
                        <div class="w-form login-form">
                            <form ng-submit="createOrganisation()">
                                <input type="text" placeholder="Enter organisation name" name="organisation"
                                       autofocus="autofocus"
                                       class="w-input login-field"
                                       ng-model="organisation.name"
                                       ng-class="{error: organisation.errors.name}">
                                <div style="color:red" ng-show="organisation.errors.name"
                                     ng-bind="organisation.errors.name"></div>
                                <input ng-hide="organisation.loading" type="submit" value="Create Organisation"
                                       class="w-button login-button register">
                                <button ng-show="organisation.loading" type="button"
                                        class="w-button login-button register">
                                    Loading...
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


<div data-collapse="medium" data-animation="default" data-duration="400" class="w-nav nav-main">
    <div class="w-clearfix container-wide nav-container">
        <a href="<?php echo SITE_RELATIVE_PATH ?>/" class="w-nav-brand">
            <?php if (isset($isHomePage)) { ?>
                <img width="160" src="<?php echo SITE_RELATIVE_PATH ?>/images/logo-white.svg" class="brand">
            <?php } else { ?>
                <img width="160" src="<?php echo SITE_RELATIVE_PATH ?>/images/logo-white-black-text.svg" class="brand">
            <?php } ?>
        </a>
        <nav role="navigation" class="w-nav-menu m-nav-open">
            <a href="<?php echo SITE_RELATIVE_PATH ?>/stories" class="w-nav-link nav">Stories</a>
            <a href="<?php echo SITE_RELATIVE_PATH ?>/projects" class="w-nav-link nav">Projects</a>
            <a href="<?php echo SITE_RELATIVE_PATH ?>/organisations" class="w-nav-link nav">Organisations</a>
            <a href="<?php echo SITE_RELATIVE_PATH ?>/my-profile" class="w-nav-link nav">Profile</a>
            <?php if (!isset($_SESSION['user'])) { ?>
                <a href="#" data-ix="showsignup" class="w-nav-link nav log-in">Log In</a>
            <?php } else { ?>
                <a href="#" data-ix="showsignup" class="w-nav-link nav log-in">Create +</a>
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
                               class="popover-link">View
                                profile</a>
                            <a href="<?php echo SITE_RELATIVE_PATH ?>/personal-details" data-ix="popoverfadeout"
                               class="popover-link">Edit Profile</a>
                            <a href="<?php echo SITE_RELATIVE_PATH ?>/logout" class="popover-link">Sign
                                out</a>
                        </div>
                        <img width="15" src="<?php echo SITE_RELATIVE_PATH ?>/images/white-settings.png"
                             data-ix="showpopover"
                             class="vert-nav">
                        <a href="<?php echo SITE_RELATIVE_PATH ?>/my-profile"
                           class="w-inline-block w-clearfix link-to-profile">
                            <div class="profile-img"
                                 style="background-image: url('<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $loggedInUser->getProfilePicOrDefault() ?>');">

                            </div>
                            <h3 class="profile-name"><?php echo $loggedInUser->getFirstName() ?></h3>
                            <h3 class="profile-name profile-organisation"><?php echo $loggedInUser->getLastName() ?></h3>
                        </a>
                    <?php } ?>
                </div>
                <div class="w-col w-col-7 w-col-small-7">
                    <div class="w-form">
                        <form id="email-form" name="email-form" data-name="Email Form" class="w-clearfix">
                            <input id="Search" type="text" placeholder="Search DSI4EU" name="Search" data-name="Search"
                                   class="w-input search-field quicksearch">
                        </form>
                        <div class="w-form-done">
                            <p>Thank you! Your submission has been received!</p>
                        </div>
                        <div class="w-form-fail">
                            <p>Oops! Something went wrong while submitting the form</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="w-section body">