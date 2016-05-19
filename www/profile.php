<?php
require __DIR__ . '/header.php';
/** @var $userID int */
/** @var $isOwner bool */
/** @var $user \DSI\Entity\User */
?>
    <script type="text/javascript">
        profileUserID = '<?php echo $userID?>';
    </script>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/UserController.js"></script>

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

    <div ng-controller="UserController as ctrl">

        <div class="w-section project-section">
            <div class="container-wide">
                <div class="w-row project-info">
                    <div id="textScroll" class="w-col w-col-6 w-col-stack">
                        <div id="text">
                            <div class="project-detail">
                                <div class="profile-header-card">
                                    <img src="<?php echo SITE_RELATIVE_PATH ?>/images/pin.png" class="card-pin">
                                    <div class="card-city"
                                         ng-bind="user.location"><?php echo $user->getLocation() ?></div>
                                    <div class="profile-bg-img el-blur"></div>
                                    <div class="header-card-overlay">
                                        <h1 class="profile-card-h1">
                                            <span ng-bind="user.firstName"><?php echo $user->getFirstName() ?></span>
                                            <span ng-bind="user.lastName"><?php echo $user->getLastName() ?></span>
                                        </h1>
                                        <div class="profile-card-job-title"
                                             ng-bind="user.jobTitle"><?php echo $user->getJobTitle() ?></div>
                                        <?php if ($isOwner) { ?>
                                            <img width="25" ng-click="editPanel = 'basicDetails'"
                                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-compose-outline-white.png"
                                                 data-ix="show-profile-update" class="edit-white">
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="profile-essential-info">
                                    <div data-ix="edit-image" class="profile-image-large"
                                         style="background-image: url('<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $user->getProfilePicOrDefault() ?>')"
                                         ng-style="{'background-image':'url(<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/' + user.profilePic + ')'}"
                                         class="profile-image-upload">
                                        <?php if ($isOwner) { ?>
                                            <div data-ix="update-image-closed" class="edit-image">
                                                <div class="edit-image-text"
                                                     ngf-select="uploadFiles($file, $invalidFiles)" accept="image/*"
                                                     class="update-profile-image">
                                                    Update image
                                                </div>
                                            </div>
                                            <div ng-show="f.progress > 0 && f.progress < 100"
                                                 class="update-profile-image">
                                                <div style="font-size:smaller">
                                                    <span ng-bind="{{errFile.name}}"></span>
                                                    <span ng-bind="{{errFile.$error}}"></span>
                                                    <span ng-bind="{{errFile.$errorParam}}"></span>

                                                <span class="progress" ng-show="f.progress >= 0">
                                                    <div style="width:{{f.progress}}%" ng-bind="f.progress + '%'"></div>
                                                </span>
                                                </div>
                                                <div style="color:red" ng-bind="{{errorMsg.file}}"></div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="info-card">
                                <h3 class="info-h card-h">About me</h3>
                                <p class="project-summary" ng-bind="user.bio" style="white-space: pre-line">
                                    <?php echo nl2br(show_input($user->getBio())) ?>
                                </p>
                                <h3 class="info-h card-h">My skills:</h3>
                                <div class="w-clearfix tags-block">
                                    <div class="skill" ng-repeat="skill in skills">
                                        <?php if ($isOwner) { ?>
                                            <div class="delete" ng-click="removeSkill(skill)">-</div>
                                        <?php } ?>
                                        <div ng-bind="skill"></div>
                                    </div>
                                    <?php if ($isOwner) { ?>
                                        <div class="add-item-block" ng-click="addSkill = !addSkill">
                                            <div class="add-item">+</div>
                                        </div>
                                        <div class="w-form" style="float:left;margin-top:-17px"
                                             ng-show="addSkill">
                                            <form id="email-form" name="email-form" data-name="Email Form"
                                                  class="w-clearfix add-skill-section" ng-submit="addSkills()">
                                                <select data-tags="true"
                                                        data-placeholder="Type your skill"
                                                        id="Add-skill" name="Add-skill"
                                                        class="w-input add-skill"
                                                        style="width:200px">
                                                    <option></option>
                                                </select>
                                                <input type="submit" value="Add" data-wait="Please wait..."
                                                       class="w-button add-skill-btn">
                                            </form>
                                        </div>
                                    <?php } ?>
                                </div>
                                <h3 class="info-h card-h">My Languages:</h3>
                                <div class="w-clearfix tags-block">
                                    <div class="skill" ng-repeat="lang in languages">
                                        <?php if ($isOwner) { ?>
                                            <div class="delete" ng-click="removeLanguage(lang)">-</div>
                                        <?php } ?>
                                        <div ng-bind="lang"></div>
                                    </div>
                                    <?php if ($isOwner) { ?>
                                        <div class="add-item-block" ng-click="addLanguage = !addLanguage">
                                            <div class="add-item">+</div>
                                        </div>

                                        <div class="w-form" style="float:left;margin-top:-17px"
                                             ng-show="addLanguage">
                                            <form id="email-form" name="email-form" data-name="Email Form"
                                                  class="w-clearfix add-skill-section"
                                                  ng-submit="addLanguages()">
                                                <select data-placeholder="Select your language"
                                                        id="Add-language" name="Add-language"
                                                        class="w-input add-language"
                                                        style="width:200px">
                                                    <option></option>
                                                </select>
                                                <input type="submit" value="Add"
                                                       class="w-button add-skill-btn">
                                            </form>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php if ($isOwner) { ?>
                                    <img width="25" ng-click="editPanel = 'bio'"
                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-compose-outline.png"
                                         data-ix="show-profile-update" class="edit-dark">
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div id="postsScroll" class="w-col w-col-6 w-col-stack">
                        <div id="posts">
                            <div class="info-card">
                                <h3 class="info-h card-h">Projects i'm involved with</h3>
                                <div class="list-items">
                                    <a href="#" class="w-inline-block partner-link"
                                       ng-href="{{project.url}}"
                                       ng-repeat="project in user.projects">
                                        <div class="w-clearfix list-item">
                                            <div class="partner-title" ng-bind="project.name"></div>
                                            <div class="no-of-projects">
                                                <span ng-bind="project.membersCount"></span>
                                                Contributor<span ng-bind="project.membersCount == 1 ? '' : 's'"></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="join-project">
                                    <a href="#" class="w-button btn btn-join">Add new project +</a>
                                </div>
                            </div>
                        </div>
                        <div class="info-card">
                            <h3 class="info-h card-h">Organisations i'm involved with</h3>
                            <div class="list-items">
                                <a ng-href="{{organisation.url}}" href="#" class="w-inline-block partner-link"
                                   ng-repeat="organisation in user.organisations">
                                    <div class="w-clearfix list-item">
                                        <div class="partner-title" ng-bind="organisation.name"></div>
                                        <div class="no-of-projects">
                                            <span ng-bind="organisation.membersCount"></span>
                                            Project<span ng-bind="organisation.membersCount == 1 ? '' : 's'"></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="join-project">
                                <a href="#" class="w-button btn btn-join">Join organisation +</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($isOwner) { ?>
            <div class="profile-update bg-blur">
                <div class="update-modal">
                    <div class="modal-header"></div>
                    <div data-ix="close-profile-update" class="close modal-close">+</div>
                    <img width="160" src="<?php echo SITE_RELATIVE_PATH ?>/images/logo-white.svg" class="modal-brand">

                    <div style="padding-top:90px">
                        <div ng-show="editPanel == 'basicDetails'">
                            <h2 class="modal-h2">Update personal info</h2>

                            <div class="w-form login-form" style="margin-top:0">
                                <form ng-submit="saveBasicDetails()">
                                    <input type="text" placeholder="First Name" class="w-input login-field"
                                           ng-class="{error: userEdit.errors.firstName}" ng-model="userEdit.firstName">
                                    <div style="color:red" ng-show="userEdit.errors.firstName"
                                         ng-bind="userEdit.errors.firstName"></div>

                                    <input type="text" placeholder="Last Name" class="w-input login-field"
                                           ng-model="userEdit.lastName" ng-class="{error: userEdit.errors.lastName}">
                                    <div style="color:red" ng-show="userEdit.errors.lastName"
                                         ng-bind="userEdit.errors.lastName"></div>

                                    <input type="text" placeholder="Job Title" class="w-input login-field"
                                           ng-model="userEdit.jobTitle" ng-class="{error: userEdit.errors.jobTitle}">
                                    <div style="color:red" ng-show="userEdit.errors.jobTitle"
                                         ng-bind="userEdit.errors.jobTitle"></div>

                                    <input type="text" placeholder="Location" class="w-input login-field"
                                           ng-model="userEdit.location" ng-class="{error: userEdit.errors.location}">
                                    <div style="color:red" ng-show="userEdit.errors.location"
                                         ng-bind="userEdit.errors.location"></div>

                                    <div class="cancel-save">
                                        <div class="w-row">
                                            <div class="w-col w-col-6">
                                                <a href="#" data-ix="close-profile-update"
                                                   class="w-button dsi-button cors cancel">Close</a>
                                            </div>
                                            <div class="w-col w-col-6">
                                                <input type="submit" class="w-button dsi-button cors"
                                                       ng-value="userEdit.loading ? 'Saving...' : 'Save'"
                                                       ng-disabled="userEdit.loading"/>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div ng-show="editPanel == 'bio'">
                            <h2 class="modal-h2">Update biography</h2>
                            <div class="w-form login-form" style="margin-top:0">
                                <form ng-submit="saveBio()">
                                <textarea class="readjustTextarea w-input profile-text"
                                          style="width:100%;border: 1px solid #cccccc;"
                                          placeholder="Add a short biography" ng-model="userEdit.bio"></textarea>

                                    <div style="color:red" ng-show="userEdit.errors.bio"
                                         ng-bind="userEdit.errors.bio"></div>

                                    <div class="cancel-save">
                                        <div class="w-row">
                                            <div class="w-col w-col-6">
                                                <a href="#" data-ix="close-profile-update"
                                                   class="w-button dsi-button cors cancel">Close</a>
                                            </div>
                                            <div class="w-col w-col-6">
                                                <input type="submit" class="w-button dsi-button cors"
                                                       ng-value="userEdit.loading ? 'Saving...' : 'Save'"
                                                       ng-disabled="userEdit.loading"/>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <br/><br/><br/>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>
<?php require __DIR__ . '/footer.php' ?>