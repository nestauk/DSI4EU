<?php
    use DSI\Service\URL;
?>
<?php if (!isset($_SESSION['user'])) { ?>

    <?php require __DIR__ . '/loginModal.php' ?>

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