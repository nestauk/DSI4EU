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
                    <form id="email-form" name="email-form" ng-submit="onSubmit()">
                        <input id="email-5" type="email" placeholder="<?php _ehtml('Enter your email address') ?>"
                               name="email-5"
                               data-name="Email 5" autofocus="autofocus"
                               class="w-input modal-input log-in"
                               ng-model="email.value"
                               ng-class="{error: errors.email}">
                        <div style="color:red;text-align:center" ng-show="errors.email"
                             ng-bind="errors.email"></div>
                        <input id="Password-5" type="password" placeholder="<?php _ehtml('Password') ?>"
                               name="Password-5"
                               data-name="Password 5" class="w-input modal-input log-in"
                               ng-model="password.value"
                               ng-class="{error: errors.password}">
                        <div style="color:red;text-align:center" ng-show="errors.password"
                             ng-bind="errors.password"></div>

                        <div class="modal-footer">
                            <div ng-hide="registered">
                                <input type="submit" style="width:250px"
                                       ng-disabled="loading"
                                       ng-value="loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Register') ?>'"
                                       class="w-button dsi-button creat-button">
                            </div>
                            <input type="button" style="width:250px"
                                   ng-show="registered"
                                   value="<?php _ehtml('Welcome to Digital Social!') ?>"
                                   class="w-button dsi-button creat-button">
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

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/LoginController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>