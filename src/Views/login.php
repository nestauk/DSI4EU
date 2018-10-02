<?php

use Services\URL;

/** @var $urlHandler URL */
?>
<!DOCTYPE html>
<html data-wf-site="56e2e31a1b1f8f784728a08c" data-wf-page="56fbef6ecf591b312d56f8be">
<head>
    <?php require __DIR__ . '/partialViews/head.php' ?>
</head>
<body ng-app="DSIApp" class="login-body">

<div class="ab-fab log-in-section"
     ng-controller="LoginController"
     data-loginjsonurl="<?php echo $urlHandler->loginJson() ?>"
     data-afterloginurl="<?php echo $urlHandler->myProfile() ?>">

    <div class="form-container" ng-hide="forgotPassword.show">
        <a href="<?php echo $urlHandler->home() ?>">
            <img class="log-in-logo" src="/images/partners/dsi-logo.png">
        </a>
        <?php if (isset($_GET['from']) AND $_GET['from'] == 'organisation') { ?>
            <h2><?php _ehtml('You must be logged in to add an organisation') ?></h2>
        <?php } elseif (isset($_GET['from']) AND $_GET['from'] == 'project') { ?>
            <h2><?php _ehtml('You must be logged in to add a project') ?></h2>
        <?php } else { ?>
            <h2><?php _ehtml('Log in to my account') ?></h2>
        <?php } ?>

        <div class="form-wrapper w-form">
            <form class="login-form-nu" id="email-form" name="email-form" ng-submit="onSubmit()">
                <input class="log-in-form-field w-input" maxlength="256" name="email"
                       placeholder="<?php _ehtml('Enter your email address') ?>" type="email"
                       ng-model="email.value"
                       ng-class="{error: errors.email}">
                <div class="log-in-error" ng-show="errors.email" ng-bind="errors.email"></div>

                <input class="log-in-form-field w-input" maxlength="256" name="password"
                       placeholder="<?php _ehtml('Password') ?>" type="password"
                       ng-model="password.value"
                       ng-class="{error: errors.password}">
                <div class="log-in-error" ng-show="errors.password" ng-bind="errors.password"></div>

                <label>
                    <input type="checkbox" name="rememberMe" ng-model="rememberMe" value="1"/>
                    <?php _ehtml('Remember me') ?>
                    <span style="font-weight: normal;font-size: smaller">(<?php _ehtml('Do not use this option on a shared or public device') ?>
                        )</span>
                </label>

                <button type="submit" class="auto ll log-in-link w-clearfix w-inline-block" data-ix="log-in-arrow"
                        style="display:block;width:auto">
                    <span class="login-li menu-li"
                          ng-bind="loading ? '<?php _ehtml('Login') ?>...' : '<?php _ehtml('Login') ?>'"></span>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </button>
                <a class="log-in-link-note" href="#" style="font-size:11px"
                   ng-click="forgotPassword.show = true"><?php _ehtml('Forgot password') ?></a>
                <a class="log-in-link-note second" style="font-size:11px"
                   href="<?php echo $urlHandler->register() ?>"><?php _ehtml("Don't have an account?") ?>
                </a>
            </form>
        </div>
    </div>

    <div class="form-container" ng-show="forgotPassword.show" ng-cloak="">
        <img class="log-in-logo" src="/images/partners/dsi-logo.png">
        <h2><?php _ehtml('Reset your password') ?></h2>

        <div class="form-wrapper w-form">
            <form class="login-form-nu" id="email-form" name="email-form"
                  ng-submit="forgotPasswordSubmit()"
                  ng-hide="forgotPassword.complete"
                  autocomplete="off">
                <input class="log-in-form-field w-input" maxlength="256" name="email"
                       placeholder="<?php _ehtml('Enter your email address') ?>" type="email"
                       ng-model="email.value"
                       ng-class="{error: forgotPassword.errors.email}">
                <div class="log-in-error" ng-show="forgotPassword.errors.email"
                     ng-bind="forgotPassword.errors.email"></div>

                <div ng-show="forgotPassword.codeSent">
                    <div ng-hide="forgotPassword.codeVerified">
                        <div class="log-in-error success" ng-hide="forgotPassword.codeVerified">
                            <?php _ehtml('The security code has been emailed to you.') ?><br/>
                            <?php _ehtml('Please allow 5-10 minutes for the message to arrive into your inbox.') ?>
                        </div>
                        <input type="text" placeholder="<?php _ehtml('Security code') ?>" name="code"
                               class="log-in-form-field w-input"
                               ng-disabled="forgotPassword.codeVerified"
                               ng-model="forgotPassword.code"
                               ng-class="{error: forgotPassword.errors.code}">
                        <div style="color:red;text-align:center" ng-show="forgotPassword.errors.code"
                             ng-bind="forgotPassword.errors.code"></div>
                    </div>
                    <div ng-show="forgotPassword.codeVerified">
                        <input type="password" placeholder="<?php _ehtml('New Password') ?>"
                               name="password"
                               class="log-in-form-field w-input"
                               ng-model="forgotPassword.password"
                               autocomplete="off"
                               ng-class="{error: forgotPassword.errors.password}">
                        <div style="color:red" ng-show="forgotPassword.errors.password"
                             ng-bind="forgotPassword.errors.password"></div>

                        <input type="password" placeholder="<?php _ehtml('Retype Password') ?>"
                               name="retypePassword"
                               class="log-in-form-field w-input"
                               ng-model="forgotPassword.retypePassword"
                               autocomplete="off"
                               ng-class="{error: forgotPassword.errors.retypePassword}">
                        <div style="color:red" ng-show="forgotPassword.errors.retypePassword"
                             ng-bind="forgotPassword.errors.retypePassword"></div>
                    </div>

                </div>

                <button type="submit" class="auto ll log-in-link w-clearfix w-inline-block" data-ix="log-in-arrow"
                        ng-disabled="forgotPassword.loading" style="display:block">
                    <span class="login-li menu-li" style="font-size:16px;"
                          ng-bind="forgotPassword.loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Reset my password') ?>'"></span>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </button>
                <a class="log-in-link-note" href="#"
                   ng-click="forgotPassword.show = false"><?php _ehtml('Back to login') ?></a>
            </form>
        </div>
    </div>


    <div class="or-login-with">- <?php _ehtml('or log in with') ?> -</div>
    <div class="w-row">
        <div class="w-col w-col-3 w-col-small-3 w-col-tiny-6">
            <a class="log-in-with-link w-inline-block" href="<?php echo URL::loginWithFacebook() ?>">
                <img class="login-with-img" src="<?php echo SITE_RELATIVE_PATH ?>/images/facebook-2.png" width="25">
                <div class="log-in-with-text">Facebook</div>
            </a>
        </div>
        <div class="w-col w-col-3 w-col-small-3 w-col-tiny-6">
            <a class="log-in-with-link w-inline-block" href="<?php echo URL::loginWithTwitter() ?>">
                <img class="login-with-img" src="<?php echo SITE_RELATIVE_PATH ?>/images/twitter.png" width="25">
                <div class="log-in-with-text">Twitter</div>
            </a>
        </div>
        <div class="w-col w-col-3 w-col-small-3 w-col-tiny-6">
            <a class="log-in-with-link w-inline-block" href="<?php echo URL::loginWithGoogle() ?>">
                <img class="login-with-img" src="<?php echo SITE_RELATIVE_PATH ?>/images/google-plus.png" width="25">
                <div class="log-in-with-text">Google +</div>
            </a>
        </div>
        <div class="w-col w-col-3 w-col-small-3 w-col-tiny-6">
            <a class="log-in-with-link w-inline-block" href="<?php echo URL::loginWithGitHub() ?>">
                <img class="login-with-img" src="<?php echo SITE_RELATIVE_PATH ?>/images/github.png" width="25">
                <div class="log-in-with-text">GitHub</div>
            </a>
        </div>
    </div>
</div>

<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/LoginController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/dsi4eu.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>
<!--[if lte IE 9]>
<script src="<?php echo SITE_RELATIVE_PATH ?>/js/lib/placeholders/placeholders.min.js"></script>
<![endif]-->

<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/main.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php include(__DIR__ . '/partialViews/googleAnalytics.html'); ?>

</body>
</html>