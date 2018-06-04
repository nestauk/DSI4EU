<?php

use Services\URL;

/** @var $urlHandler Services\URL */
?>
<!DOCTYPE html>
<html data-wf-site="56e2e31a1b1f8f784728a08c" data-wf-page="56fbef6ecf591b312d56f8be">
<head>
    <?php require __DIR__ . '/partialViews/head.php' ?>
</head>
<body ng-app="DSIApp" class="login-body">

<div class="register-controller ab-fab log-in-section" ng-controller="RegisterController">
    <div class="w-row">
        <div class="content-left w-col w-col-12">
            <a href="<?php echo $urlHandler->home() ?>">
                <img class="log-in-logo" src="<?php echo SITE_RELATIVE_PATH ?>/images/dark_1.svg">
            </a>
            <h2><?php _ehtml('Register') ?></h2>

            <div class="form-wrapper w-form">
                <form id="email-form" method="post"
                      action="">
                    <input id="email-5" type="email" placeholder="<?php _ehtml('Enter your email address') ?>"
                           name="email"
                           data-name="Email 5" autofocus="autofocus"
                           class="log-in-form-field w-input <?php if (isset($errors['email'])) echo 'error' ?>"
                           ng-class="{error: errors.email}"
                           value="<?php echo show_input($_POST['email']) ?>">
                    <div class="log-in-error" ng-show="errors.email" ng-bind="errors.email"></div>
                    <?php if (isset($errors['email'])) { ?>
                        <div style="color:red;text-align:center">
                            <?php echo show_input($errors['email']) ?>
                        </div>
                    <?php } ?>

                    <input id="Password-5" type="password" placeholder="<?php _ehtml('Password') ?>"
                           name="password" data-name="Password 5" class="log-in-form-field w-input"
                           ng-class="{error: errors.password}">
                    <div class="log-in-error" ng-show="errors.password"
                         ng-bind="errors.password"></div>
                    <?php if (isset($errors['password'])) { ?>
                        <div class="log-in-error">
                            <?php echo show_input($errors['password']) ?>
                        </div>
                    <?php } ?>

                    <div style="width:300px;margin:auto;padding-top:30px">
                        <div class="g-recaptcha" data-sitekey="6Ldc3QgUAAAAANlNhW7nwIUOI1TR3Uzsw8BTtO5D"></div>
                    </div>

                    <?php if (isset($errors['captcha'])) { ?>
                        <div class="log-in-error">
                            <?php echo show_input($errors['captcha']) ?>
                        </div>
                    <?php } ?>

                    <div class="check-box">
                        <label>
                            <input type="checkbox" name="accept-terms" <?= $_POST['accept-terms'] ? 'checked' : '' ?>>
                            I accept that all data provided, with the exception of my email address, will be available
                            publicly on digitalsocial.eu
                        </label>
                        <?php if (isset($errors['accept-terms'])) { ?>
                            <div class="log-in-error">
                                <?php echo show_input($errors['accept-terms']) ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="check-box">
                        We would like to email you updates about the DSI programme including regular newsletters,
                        project updates updates, new research and publications, invitations to events and the
                        occasional request to take part in research and surveys.
                        <br><br>

                        <label>
                            <input type="checkbox"
                                   name="email-subscription" <?= $_POST['email-subscription'] ? 'checked' : '' ?>>
                            I would like to subscribe to the DSI mailing list
                        </label>

                        <br>
                        You can unsubscribe by clicking the link in our emails, or emailing info@nesta.org.uk. We
                        promise to keep your details safe and secure. We won’t share your details outside of Nesta
                        without your permission. Find out more about how we use personal information in our
                        <a target="_blank" href="<?= $urlHandler->privacyPolicy() ?>">Privacy Policy</a>.
                    </div>

                    <div class="modal-footer">
                        <div ng-hide="registered">
                            <button type="submit" class="auto ll log-in-link w-clearfix w-inline-block"
                                    data-ix="log-in-arrow" name="register"
                                    style="width:250px;display:block">
                                <span class="login-li menu-li"><?php _ehtml('Register') ?></span>
                                <img class="login-arrow"
                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <br/><br/>
            <div class="or-login-with">- <?php _ehtml('or log in with') ?> -</div>
            <div class="w-row">
                <div class="w-col w-col-3 w-col-small-3 w-col-tiny-6">
                    <a class="log-in-with-link w-inline-block" href="<?php echo URL::loginWithFacebook() ?>">
                        <img class="login-with-img" src="<?php echo SITE_RELATIVE_PATH ?>/images/facebook-2.png"
                             width="25">
                        <div class="log-in-with-text">Facebook</div>
                    </a>
                </div>
                <div class="w-col w-col-3 w-col-small-3 w-col-tiny-6">
                    <a class="log-in-with-link w-inline-block" href="<?php echo URL::loginWithTwitter() ?>">
                        <img class="login-with-img" src="<?php echo SITE_RELATIVE_PATH ?>/images/twitter.png"
                             width="25">
                        <div class="log-in-with-text">Twitter</div>
                    </a>
                </div>
                <div class="w-col w-col-3 w-col-small-3 w-col-tiny-6">
                    <a class="log-in-with-link w-inline-block" href="<?php echo URL::loginWithGoogle() ?>">
                        <img class="login-with-img" src="<?php echo SITE_RELATIVE_PATH ?>/images/google-plus.png"
                             width="25">
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
    </div>
</div>

<script src='https://www.google.com/recaptcha/api.js'></script>

<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/LoginController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>


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