<?php
use DSI\Service\URL;

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
            <img class="log-in-logo" src="<?php echo SITE_RELATIVE_PATH ?>/images/dark_1.svg">
        </a>
        <?php if (isset($_GET['from']) AND $_GET['from'] == 'organisation') { ?>
            <h2>You must be logged in to add an organisation</h2>
        <?php } elseif (isset($_GET['from']) AND $_GET['from'] == 'project') { ?>
            <h2>You must be logged in to add a project</h2>
        <?php } else { ?>
            <h2>Log in to my account</h2>
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

                <button type="submit" class="auto ll log-in-link w-clearfix w-inline-block" data-ix="log-in-arrow"
                        style="width:180px;display:block">
                    <span class="login-li menu-li"
                          ng-bind="loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('login') ?>'"></span>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </button>
                <a class="log-in-link-note" href="#" ng-click="forgotPassword.show = true">Forgot password</a>
                <a class="log-in-link-note second" href="<?php echo $urlHandler->register() ?>">Don't have an
                    account?</a>
            </form>
        </div>
    </div>

    <div class="form-container" ng-show="forgotPassword.show" ng-cloak="">
        <img class="log-in-logo" src="<?php echo SITE_RELATIVE_PATH ?>/images/dark_1.svg">
        <h2>Reset your password</h2>

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
                        ng-disabled="forgotPassword.loading" style="width:300px;display:block">
                    <span class="login-li menu-li"
                          ng-bind="forgotPassword.loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Reset my password') ?>'"></span>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </button>
                <a class="log-in-link-note" href="#" ng-click="forgotPassword.show = false">Back to login</a>
            </form>
        </div>
    </div>


    <div class="or-login-with">- or log in with -</div>
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

<?php /*

<br/><br/>
<div class="page-content"
     ng-controller="LoginController"
     data-loginjsonurl="<?php echo $urlHandler->loginJson() ?>"
     data-afterloginurl="<?php echo $urlHandler->myProfile() ?>">
    <div class="w-row">
        <div class="content-left w-col w-col-12">
            <p class="intro"
               ng-bind="forgotPassword.show ? '<?php _ehtml('Reset password') ?>' : '<?php _ehtml('Log in') ?>'">
                <?php _ehtml('Log in') ?>
            </p>
            <div class="w-form">
                <div ng-hide="forgotPassword.show">
                    <form id="email-form-3" name="email-form-3" ng-submit="onSubmit()">
                        <input class="w-input modal-input log-in"
                               id="Enter-your-email-address" maxlength="256" name="Enter-your-email-address"
                               placeholder="<?php _ehtml('Enter your email address') ?>" type="email"
                               ng-model="email.value"
                               ng-class="{error: errors.email}">
                        <div style="color:red;text-align:center" ng-show="errors.email"
                             ng-bind="errors.email"></div>
                        <input class="w-input modal-input" data-name="password" id="password-6" maxlength="256"
                               name="password" placeholder="<?php _ehtml('Password') ?>" type="password"
                               ng-model="password.value"
                               ng-class="{error: errors.password}">
                        <div style="color:red;text-align:center" ng-show="errors.password"
                             ng-bind="errors.password"></div>

                        <input class="w-button dsi-button creat-button"
                               type="submit" ng-hide="loggedin"
                               value="<?php _ehtml('Log in') ?>"
                               ng-value="loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Log in') ?>'"
                               ng-disabled="loading">
                        <input class="w-button dsi-button creat-button"
                               ng-show="loggedin" type="button"
                               style="width:auto"
                               value="<?php _ehtml('Welcome back to Digital Social!') ?>">

                        <br/><br/>
                        <a class="forgotten-password" href="<?php echo $urlHandler->register() ?>">
                            <?php _ehtml('Register') ?></a>
                        <a class="forgotten-password" href="#" ng-click="forgotPassword.show = true">
                            <?php _ehtml('Forgotten password?') ?></a>

                        <br/>
                        <div class="w-row">
                            <div class="w-col w-col-3">
                                <a href="<?php echo URL::loginWithFacebook() ?>"
                                   class="register-social w-clearfix w-inline-block">
                                    <img class="register-social-image"
                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/facebook-logo.png">
                                    <div class="register-social-text">Facebook</div>
                                </a>
                            </div>
                            <div class="w-col w-col-3">
                                <a href="<?php echo URL::loginWithTwitter() ?>"
                                   class="register-social w-clearfix w-inline-block">
                                    <img class="register-social-image"
                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/twitter-logo-silhouette.png">
                                    <div class="register-social-text">Twitter</div>
                                </a>
                            </div>
                            <div class="w-col w-col-3">
                                <a href="<?php echo URL::loginWithGitHub() ?>"
                                   class="register-social w-clearfix w-inline-block">
                                    <img class="register-social-image"
                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/social.png">
                                    <div class="register-social-text">Github</div>
                                </a>
                            </div>
                            <div class="w-col w-col-3">
                                <a href="<?php echo URL::loginWithGoogle() ?>"
                                   class="register-social w-clearfix w-inline-block">
                                    <img class="register-social-image"
                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/google-plus-logo.png">
                                    <div class="register-social-text">Google</div>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div ng-show="forgotPassword.show" class="w-tab-screen" style="text-align:center">
                    <form ng-hide="forgotPassword.complete"
                          ng-submit="forgotPasswordSubmit()"
                          autocomplete="off">
                        <input type="email" placeholder="<?php _ehtml('Enter your email address') ?>"
                               name="email"
                               class="w-input modal-input"
                               ng-model="email.value"
                               ng-class="{error: forgotPassword.errors.email}">
                        <div style="color:red;text-align:center" ng-show="forgotPassword.errors.email"
                             ng-bind="forgotPassword.errors.email"></div>
                        <div ng-show="forgotPassword.codeSent">
                            <div ng-hide="forgotPassword.codeVerified">
                                <i ng-hide="forgotPassword.codeVerified">
                                    <?php _ehtml('The security code has been emailed to you.') ?>
                                </i>
                                <input type="text" placeholder="<?php _ehtml('Security code') ?>" name="code"
                                       class="w-input modal-input"
                                       ng-disabled="forgotPassword.codeVerified"
                                       ng-model="forgotPassword.code"
                                       ng-class="{error: forgotPassword.errors.code}">
                                <div style="color:red;text-align:center" ng-show="forgotPassword.errors.code"
                                     ng-bind="forgotPassword.errors.code"></div>
                            </div>
                            <div ng-show="forgotPassword.codeVerified">
                                <input type="password" placeholder="<?php _ehtml('New Password') ?>"
                                       name="password"
                                       class="w-input modal-input"
                                       ng-model="forgotPassword.password"
                                       autocomplete="off"
                                       ng-class="{error: forgotPassword.errors.password}">
                                <div style="color:red" ng-show="forgotPassword.errors.password"
                                     ng-bind="forgotPassword.errors.password"></div>

                                <input type="password" placeholder="<?php _ehtml('Retype Password') ?>"
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
                                   value="<?php _ehtml('Reset my password') ?>"
                                   ng-value="forgotPassword.loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Reset my password') ?>'">

                            <br/><br/><br/>
                            <a href="#" ng-show="forgotPassword.show" class="forgotten-password"
                               ng-click="forgotPassword = {}"><?php _ehtml('Back to login') ?></a>
                        </div>
                    </form>
                    <div ng-show="forgotPassword.complete" style="font-size:18px;line-height:24px;padding-top:70px">
                        <?php _ehtml('Your password has changed.') ?><br/>
                        <a href="#" ng-click="forgotPassword = {}">
                            Click here
                        </a> to login using your new password.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo '-->'; ?>
*/ ?>

<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/LoginController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/dsi4eu.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>
<!--[if lte IE 9]>
<script src="<?php echo SITE_RELATIVE_PATH ?>/js/lib/placeholders/placeholders.min.js"></script>
<![endif]-->

<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/script.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php include(__DIR__ . '/partialViews/googleAnalytics.html'); ?>

</body>
</html>