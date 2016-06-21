<?php
require __DIR__ . '/header.php';
/** @var $user \DSI\Entity\User */
?>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/PersonalDetailsController.js"></script>
<?php /* <script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH?>/js/controllers/UploadImageController.js"></script> */ ?>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/UpdatePasswordController.js"></script>

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

    <div class="change-password-block bg-blur modal-signup" ng-controller="UpdatePasswordController">
        <div data-ix="downbeforeup" class="password-signupform signup-form">
            <div class="modal-header"></div>
            <div data-ix="destroysignup" class="close modal-close">+</div>
            <img src="<?php echo SITE_RELATIVE_PATH ?>/images/dsi-8c1449cf94fe315a853fd9a5d99eaf45.png"
                 class="modal-brand">
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

    <div ng-controller="PersonalDetailsController">

        <div class="w-section page-header">
            <div class="container-wide header">
                <h1 class="page-h1 light">Edit your profile</h1>
            </div>
        </div>

        <div class="container-wide">
            <div class="body-content add-story">
                <div data-ix="showpasswordchange" class="change-password top-right">Change password</div>

                <div class="w-form">
                    <form class="w-clearfix" ng-submit="savePersonalDetails()" ng-disabled="loading">
                        <div class="w-row">
                            <div class="w-col w-col-6">
                                <h2 class="edit-h2">Personal info</h2>
                                <label class="story-label" for="First-name">Name</label>
                                <input class="w-input story-form personal" maxlength="256"
                                       placeholder="Add your first name" type="text" ng-model="user.firstName">
                                <label class="story-label" for="surname">Surname</label>
                                <input class="w-input story-form personal" maxlength="256"
                                       placeholder="Add your surname" type="text" ng-model="user.lastName">
                                <label class="story-label" for="surname-2">Email</label>
                                <input class="w-input story-form personal" maxlength="256"
                                       placeholder="Add your email address" type="text" ng-model="user.email">
                                <label class="story-label" for="Story-wysiwyg">Privacy</label>
                                <div class="w-checkbox">
                                    <label class="w-form-label">
                                        <input class="w-checkbox-input" value="1" type="checkbox"
                                               ng-model="user.showEmail">
                                        Show email publicly
                                    </label>
                                </div>
                                <label class="story-label profile-image">
                                    Your profile image
                                </label>
                                <img class="story-image-upload" ng-src="{{user.profilePic}}">
                                <a class="w-button dsi-button story-image-upload" href="#"
                                   ngf-select="profilePic.upload($file, $invalidFiles)" ng-bind="profilePic.loading ? 'Loading...' : 'Upload image'">
                                    Upload image
                                </a>
                                <div ng-show="profilePic.f.progress > 0 && profilePic.f.progress < 100"
                                     class="">
                                    <div style="font-size:smaller">
                                        <span ng-bind="{{profilePic.name}}"></span>
                                        <span ng-bind="{{profilePic.$error}}"></span>
                                        <span ng-bind="{{profilePic.$errorParam}}"></span>
                                                <span class="progress" ng-show="profilePic.f.progress >= 0">
                                                    <div style="width:{{profilePic.f.progress}}%" ng-bind="profilePic.f.progress + '%'"></div>
                                                </span>
                                    </div>
                                    <div style="color:red" ng-bind="{{profilePic.errorMsg.file}}"></div>
                                </div>

                                <?php /*
                                    <label class="story-label" for="Title">Header background image</label>
                                    <img class="story-image-upload story-image-upload-large"
                                         src="images/brussels-locations.jpg">
                                    <a class="w-button dsi-button story-image-upload" href="#">Upload image</a>
                                    */ ?>
                            </div>
                            <div class="w-col w-col-6">
                                <h2 class="edit-h2">Professional Info</h2>
                                <label class="story-label" for="job-title">Job title</label>
                                <input class="w-input story-form personal" maxlength="256"
                                       placeholder="What do you do?" type="text" ng-model="user.jobTitle">
                                <label class="story-label" for="company">Company</label>
                                <input class="w-input story-form personal" maxlength="256"
                                       placeholder="Where do you do it?" type="text" ng-model="user.company">

                                <h2 class="edit-h2">About you</h2>
                                <label class="story-label" for="city">Which city are you based in?</label>
                                <input class="w-input story-form personal" maxlength="256"
                                       placeholder="Your city" type="text" ng-model="user.cityName">
                                <label class="story-label" for="country">In which country?</label>
                                <input class="w-input story-form personal" maxlength="256"
                                       placeholder="Your country" type="text" ng-model="user.countryName">
                                <label class="story-label" for="bio">Your bio</label>
                                <textarea class="w-input story-form" maxlength="5000" ng-model="user.bio"
                                          placeholder="Say something about yourself (maximum 450 characters)"></textarea>
                            </div>
                        </div>
                        <input class="w-button dsi-button post-story" type="submit" value="Update profile"
                               ng-value="loading ? 'Loading...' : 'Update profile'"
                               ng-disabled="loading">
                        <a href="<?php echo \DSI\Service\URL::myProfile() ?>"
                           class="w-button dsi-button post-story cancel">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

    </div>

<?php require __DIR__ . '/footer.php' ?>