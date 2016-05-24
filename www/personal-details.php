<?php
require __DIR__ . '/header.php';
/** @var $user \DSI\Entity\User */
?>
    <script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH?>/js/controllers/PersonalDetailsController.js"></script>
    <?php /* <script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH?>/js/controllers/UploadImageController.js"></script> */?>
    <script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH?>/js/controllers/UpdatePasswordController.js"></script>
    <div>

        <div class="change-password-block bg-blur modal-signup" ng-controller="UpdatePasswordController">
            <div data-ix="downbeforeup" class="password-signupform signup-form">
                <div class="modal-header"></div>
                <div data-ix="destroysignup" class="close modal-close">+</div>
                <img src="<?php echo SITE_RELATIVE_PATH?>/images/dsi-8c1449cf94fe315a853fd9a5d99eaf45.png" class="modal-brand">
                <h3 class="change-password-h">Change password</h3>
                <div class="w-form login-form">
                    <form id="email-form" name="email-form" data-name="Email Form"
                          ng-submit="savePassword()">
                        <input id="new-password" type="password" placeholder="Enter your new password"
                               name="new-password" data-name="new password"
                               class="w-input login-field"
                               ng-class="{error: errors.password}"
                               ng-model="password">
                        <div style="color:red" ng-bind="errors.password"></div>
                        <input id="confirm-password" type="password" placeholder="Confirm password"
                               name="confirm-password" data-name="confirm password"
                               class="w-input login-field"
                               ng-class="{error: errors.retypePassword}"
                               ng-model="retypePassword">
                        <div style="color:red" ng-bind="errors.retypePassword"></div>
                        <input ng-hide="loading" type="submit"
                               value="{{saved ? 'Your password has been saved' : 'Update password'}}"
                               class="w-button login-button">
                        <button ng-show="loading" class="w-button login-button" style="background-color:#EC9A38">
                            Please wait...
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="w-container body-container">
            <div class="body-content">
                <div>
                    <style>
                        .thumb {
                            width: 24px;
                            height: 24px;
                            float: none;
                            position: relative;
                            top: 7px;
                        }

                        form .progress {
                            line-height: 15px;
                        }

                        .progress {
                            display: inline-block;
                            width: 100px;
                            border: 3px groove #CCC;
                        }

                        .progress div {
                            font-size: smaller;
                            background: orange;
                            width: 0;
                        }
                    </style>


                    <h2 class="login-h2">Personal details</h2>
                    <img
                        src="<?php echo SITE_RELATIVE_PATH?>/images/users/profile/<?php echo $user->getProfilePicOrDefault() ?>"
                        ng-src="<?php echo SITE_RELATIVE_PATH?>/images/users/profile/{{user.profilePic}}"
                        class="profile-image-upload">

                    <?php /*
                    <div class="update-profile-image">
                        <div style="font:smaller">
                            <span ng-bind="{{errFile.name}}"></span>
                            <span ng-bind="{{errFile.$error}}"></span>
                            <span ng-bind="{{errFile.$errorParam}}"></span>

                            <span class="progress" ng-show="f.progress >= 0">
                                <div style="width:{{f.progress}}%" ng-bind="f.progress + '%'"></div>
                            </span>
                        </div>
                        <div style="color:red" ng-bind="{{errorMsg.file}}"></div>
                    </div>
                    */?>
                </div>

                <div ng-controller="PersonalDetailsController">
                    <div class="w-form login-form fullpage">
                        <form id="email-form" name="email-form" data-name="Email Form"
                              ng-submit="savePersonalDetails()">
                            <input id="First-name" type="text" placeholder="First name"
                                   autofocus="autofocus" name="First-name" data-name="First name"
                                   class="w-input login-field" ng-model="user.firstName"
                                   ng-class="{error: errors.firstName}"
                                   value="<?php echo show_input($user->getFirstName()) ?>">
                            <div ng-show="errors.firstName" style="color:red" ng-bind="{{errors.firstName}}"></div>
                            <input id="Last-name" type="text" placeholder="Last name"
                                   autofocus="autofocus"
                                   name="Last-name" data-name="Last name"
                                   class="w-input login-field" ng-model="user.lastName"
                                   ng-class="{error: errors.lastName}"
                                   value="<?php echo show_input($user->getLastName()) ?>">
                            <div ng-show="errors.lastName" style="color:red" ng-bind="{{errors.lastName}}"></div>
                            <input id="email-3" type="email" placeholder="Enter your email address"
                                   name="email-3"
                                   data-name="Email 3" autofocus="autofocus"
                                   class="w-input login-field" ng-model="user.email"
                                   ng-class="{error: errors.email}"
                                   value="<?php echo show_input($user->getEmail()) ?>">
                            <div ng-show="errors.email" style="color:red" ng-bind="{{errors.email}}"></div>
                            <input id="Location" type="text" placeholder="Location" name="Location"
                                   data-name="Location"
                                   class="w-input login-field" ng-model="user.location"
                                   ng-class="{error: errors.location}"
                                   value="<?php echo show_input($user->getLocation()) ?>">
                            <input ng-hide="loading" type="submit"
                                   value="{{saved ? 'Your details have been saved' : 'Save and continue'}}"
                                   class="w-button login-button">
                            <button ng-show="loading" class="w-button login-button" style="background-color:#EC9A38">
                                Please wait...
                            </button>
                        </form>
                        <div class="w-form-fail">
                            <p>Oops! Something went wrong while submitting the form</p>
                        </div>
                    </div>
                </div>
                <div data-ix="showpasswordchange" class="change-password top-right">Change password</div>
            </div>
        </div>

    </div>
<?php require __DIR__ . '/footer.php' ?>