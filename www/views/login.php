<?php
use \DSI\Service\URL;

?>
<?php require __DIR__ . '/header.php' ?>

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
                            <a class="forgotten-password" href="<?php echo $urlHandler->login()?>">
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

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/LoginController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>