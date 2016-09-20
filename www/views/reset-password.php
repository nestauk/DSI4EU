<?php
require __DIR__ . '/header.php';
/** @var $users \DSI\Entity\User[] */
/** @var $owner \DSI\Entity\User */
/** @var $loggedInUser \DSI\Entity\User */
/** @var $organisation \DSI\Entity\Organisation */

if (!isset($urlHandler))
    $urlHandler = new \DSI\Service\URL();
?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

    <div class="creator page-header">
        <div class="container-wide header">
            <h1 class="light page-h1">Set your password</h1>
        </div>
    </div>
    <div class="creator section-white" style="margin-top:0" ng-controller="ResetPasswordController">
        <div class="container-wide">
            <div class="add-story body-content">
                <div class="w-tabs" data-easing="linear">
                    <div class="w-tab-content">
                        <div class="step-window w-tab-pane w--tab-active" style="padding-top:0">
                            <div class="w-row">
                                <div class="creator-col w-col w-col-4"></div>
                                <div class="creator-col creator-col-right w-col w-col-8">
                                    <div class="w-form">
                                        <div class="w-row">
                                            <div class="w-col w-col-6 w-col-stack">
                                                <div class="padding-right-50">
                                                    <form ng-submit="submit()" autocomplete="off">
                                                        <input type="email" class="w-input modal-input"
                                                               placeholder="<?php _ehtml('Enter your email address') ?>"
                                                               ng-model="data.email" ng-class="{error: errors.email}">
                                                        <div style="color:red;text-align:center" ng-show="errors.email"
                                                             ng-bind="errors.email"></div>

                                                        <input type="text" class="w-input modal-input"
                                                               placeholder="<?php _ehtml('Security code') ?>"
                                                               ng-model="data.code" ng-class="{error: errors.code}">
                                                        <div style="color:red;text-align:center" ng-show="errors.code"
                                                             ng-bind="errors.code"></div>

                                                        <input type="password" class="w-input modal-input"
                                                               placeholder="<?php _ehtml('New Password') ?>"
                                                               ng-model="data.password" autocomplete="off"
                                                               ng-class="{error: errors.password}">
                                                        <div style="color:red" ng-show="errors.password"
                                                             ng-bind="errors.password"></div>

                                                        <input type="password" class="w-input modal-input"
                                                               placeholder="<?php _ehtml('Retype Password') ?>"
                                                               ng-model="data.retypePassword" autocomplete="off"
                                                               ng-class="{error: errors.retypePassword}">
                                                        <div style="color:red" ng-show="errors.retypePassword"
                                                             ng-bind="errors.retypePassword"></div>

                                                        <br/>
                                                        <button type="submit"
                                                                class="tab-button-2 tab-button-next w-button"
                                                                ng-bind="loading ? 'Loading...' : 'Save password'"
                                                                ng-disabled="loading">Save password
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="w-col w-col-6 w-col-stack"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ResetPasswordController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>