<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/LoginController.js?v=<?php echo \DSI\Service\Sysctl::$version ?>"></script>

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