<?php
use \DSI\Service\URL;

?>
<?php require __DIR__ . '/header.php' ?>

    <br/><br/>
    <div class="page-content" ng-controller="RegisterController">
        <div class="w-row">
            <div class="content-left w-col w-col-12">
                <p class="intro">
                    <?php _ehtml('Register') ?>
                </p>

                <div class="w-form">
                    <form id="email-form" method="post"
                          action="" <?php /*name="email-form" ng-submit="onSubmit()" */ ?>>
                        <input id="email-5" type="email" placeholder="<?php _ehtml('Enter your email address') ?>"
                               name="email"
                               data-name="Email 5" autofocus="autofocus"
                               class="w-input modal-input log-in <?php if (isset($errors['email'])) echo 'error' ?>"
                               ng-class="{error: errors.email}"
                               value="<?php echo show_input($_POST['email']) ?>">
                        <div style="color:red;text-align:center" ng-show="errors.email"
                             ng-bind="errors.email"></div>
                        <?php if (isset($errors['email'])) { ?>
                            <div style="color:red;text-align:center">
                                <?php echo show_input($errors['email']) ?>
                            </div>
                        <?php } ?>

                        <input id="Password-5" type="password" placeholder="<?php _ehtml('Password') ?>"
                               name="password"
                               data-name="Password 5" class="w-input modal-input log-in"
                               ng-class="{error: errors.password}">
                        <div style="color:red;text-align:center" ng-show="errors.password"
                             ng-bind="errors.password"></div>
                        <?php if (isset($errors['password'])) { ?>
                            <div style="color:red;text-align:center">
                                <?php echo show_input($errors['password']) ?>
                            </div>
                        <?php } ?>

                        <div style="width:300px;margin:auto;padding-top:30px">
                            <div class="g-recaptcha" data-sitekey="6Ldc3QgUAAAAANlNhW7nwIUOI1TR3Uzsw8BTtO5D"></div>
                        </div>
                        <?php if (isset($errors['captcha'])) { ?>
                            <div style="color:red;text-align:center">
                                <?php echo show_input($errors['captcha']) ?>
                            </div>
                        <?php } ?>

                        <div class="modal-footer">
                            <div ng-hide="registered">
                                <input type="submit" style="width:250px"
                                       ng-disabled="loading"
                                       name="register"
                                       ng-value="loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Register') ?>'"
                                       class="w-button dsi-button creat-button">
                            </div>
                            <?php /*
                            <input type="button" style="width:250px"
                                   ng-show="registered"
                                   value="<?php _ehtml('Welcome to Digital Social!') ?>"
                                   class="w-button dsi-button creat-button">
                            */ ?>
                        </div>
                    </form>
                </div>

                <br/><br/>
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
            </div>
        </div>
    </div>

    <script src='https://www.google.com/recaptcha/api.js'></script>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/LoginController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>