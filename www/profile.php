<?php
require __DIR__ . '/header.php';
/** @var $userID int */
/** @var $isOwner bool */
/** @var $user \DSI\Entity\User */
/** @var $projects \DSI\Entity\Project[] */
/** @var $organisations \DSI\Entity\Organisation[] */
?>
    <script type="text/javascript">
        profileUserID = '<?php echo $userID?>';
    </script>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/UserController.js"></script>

    <div ng-controller="UserController as ctrl" id="UserController">

        <div class="w-section project-section">
            <div class="container-wide">
                <div class="w-row project-info">
                    <div id="textScroll" class="w-col w-col-6 w-col-stack">
                        <div id="text">
                            <div class="project-detail">
                                <div class="profile-header-card">
                                    <img src="<?php echo SITE_RELATIVE_PATH ?>/images/pin.png" class="card-pin">
                                    <div class="card-city">
                                        <?php echo $user->getCityName() ?>,
                                        <?php echo $user->getCountryName() ?>
                                    </div>
                                    <div class="profile-bg-img el-blur" style="background:#666"></div>
                                    <div data-ix="show-edit-light" class="header-card-overlay">
                                        <h1 class="profile-card-h1">
                                            <span><?php echo $user->getFirstName() ?></span>
                                            <span><?php echo $user->getLastName() ?></span>
                                        </h1>
                                        <div class="profile-card-job-title"><?php echo $user->getJobTitle() ?></div>
                                        <?php if ($isOwner) { ?>
                                            <a href="<?php echo \DSI\Service\URL::editProfile() ?>" class="edit-white">
                                                <img width="25"
                                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-compose-outline-white.png"/>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="profile-essential-info">
                                    <div data-ix="edit-image" class="profile-image-large profile-image-upload"
                                         style="background-image: url('<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $user->getProfilePicOrDefault() ?>')"
                                         ng-style="{'background-image':'url(<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/' + user.profilePic + ')'}">
                                    </div>
                                </div>
                            </div>
                            <div data-ix="show-edit-dark" class="info-card">
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
                                        <div class="add-item-block" ng-click="showAddSkill = !showAddSkill">
                                            <div class="add-item">+</div>
                                        </div>
                                        <div class="w-form" style="float:left;margin-top:-17px"
                                             ng-show="showAddSkill">
                                            <form id="email-form" name="email-form" data-name="Email Form"
                                                  class="w-clearfix add-skill-section">
                                                <select data-tags="true"
                                                        data-placeholder="Type your skill"
                                                        id="Add-skill" name="Add-skill"
                                                        class="w-input add-skill"
                                                        multiple
                                                        style="width:200px">
                                                    <option></option>
                                                </select>
                                                <?php /*
                                                <input type="submit" value="Add" data-wait="Please wait..."
                                                       class="w-button add-skill-btn">
                                                */ ?>
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
                                        <div class="add-item-block" ng-click="showAddLanguage = !showAddLanguage">
                                            <div class="add-item">+</div>
                                        </div>

                                        <div class="w-form" style="float:left;margin-top:-17px"
                                             ng-show="showAddLanguage">
                                            <form id="email-form" name="email-form" data-name="Email Form"
                                                  class="w-clearfix add-skill-section">
                                                <select data-placeholder="Select your language"
                                                        id="Add-language" name="Add-language"
                                                        class="w-input add-language"
                                                        multiple
                                                        style="width:200px">
                                                    <option></option>
                                                </select>
                                                <?php /*
                                                <input type="submit" value="Add"
                                                       class="w-button add-skill-btn">
                                                */ ?>
                                            </form>
                                        </div>
                                    <?php } ?>
                                </div>
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
                                <?php if ($isOwner) { ?>
                                    <div class="join-project">
                                        <a href="#" class="w-button btn btn-join"
                                           ng-click="editPanel = 'joinProject'"
                                           data-ix="show-profile-update">
                                            Join project +
                                        </a>
                                    </div>
                                <?php } ?>
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
                            <?php if ($isOwner) { ?>
                                <div class="join-project">
                                    <a href="#" class="w-button btn btn-join"
                                       ng-click="editPanel = 'joinOrganisation'"
                                       data-ix="show-profile-update">
                                        Join organisation +
                                    </a>
                                </div>
                            <?php } ?>
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

                    <div style="padding:90px 0">
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
                        <div ng-show="editPanel == 'joinProject'">
                            <h2 class="modal-h2">Join project</h2>
                            <div class="w-form login-form" style="margin-top:0">
                                <form ng-submit="joinProject.submit()" id="joinProjectForm">
                                    <select style="width:100%" data-placeholder="Select a project"
                                            ng-model="joinProject.data.project">
                                        <option value=""></option>
                                        <?php foreach ($projects AS $project) { ?>
                                            <option value="<?php echo $project->getId() ?>">
                                                <?php echo show_input($project->getName()) ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                    <br/><br/>

                                    <div style="color:red" ng-show="joinProject.errors.project"
                                         ng-bind="joinProject.errors.project"></div>
                                    <div style="color:red" ng-show="joinProject.errors.member"
                                         ng-bind="joinProject.errors.member"></div>

                                    <div style="color:green;" ng-show="joinProject.success">
                                        Your request to join the project has been successfully send.
                                    </div>

                                    <div class="cancel-save">
                                        <div class="w-row">
                                            <div class="w-col w-col-6">
                                                <a href="#" data-ix="close-profile-update"
                                                   class="w-button dsi-button cors cancel">Close</a>
                                            </div>
                                            <div class="w-col w-col-6">
                                                <input type="submit" class="w-button dsi-button cors"
                                                       ng-value="joinProject.loading ? 'Saving...' : 'Join'"
                                                       ng-disabled="joinProject.loading"/>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div ng-show="editPanel == 'joinOrganisation'">
                            <h2 class="modal-h2">Join Organisation</h2>
                            <div class="w-form login-form" style="margin-top:0">
                                <form ng-submit="joinOrganisation.submit()" id="joinOrganisationForm">
                                    <select style="width:100%" data-placeholder="Select an organisation"
                                            ng-model="joinOrganisation.data.organisation">
                                        <option value=""></option>
                                        <?php foreach ($organisations AS $organisation) { ?>
                                            <option value="<?php echo $organisation->getId() ?>">
                                                <?php echo show_input($organisation->getName()) ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                    <br/><br/>

                                    <div style="color:red" ng-show="joinOrganisation.errors.organisation"
                                         ng-bind="joinOrganisation.errors.organisation"></div>
                                    <div style="color:red" ng-show="joinOrganisation.errors.member"
                                         ng-bind="joinOrganisation.errors.member"></div>

                                    <div style="color:green;" ng-show="joinOrganisation.success">
                                        Your request to join the organisation has been successfully send.
                                    </div>

                                    <div class="cancel-save">
                                        <div class="w-row">
                                            <div class="w-col w-col-6">
                                                <a href="#" data-ix="close-profile-update"
                                                   class="w-button dsi-button cors cancel">Close</a>
                                            </div>
                                            <div class="w-col w-col-6">
                                                <input type="submit" class="w-button dsi-button cors"
                                                       ng-value="joinOrganisation.loading ? 'Saving...' : 'Join'"
                                                       ng-disabled="joinOrganisation.loading"/>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <script type="text/javascript">
            (function () {
                var joinProjectForm = $('#joinProjectForm');
                $('select', joinProjectForm).select2();
                $('.select2-selection', joinProjectForm).on('keyup', function (e) {
                    if (e.keyCode === 13) {
                        angular.element('#UserController').scope().joinProject.submit();
                        angular.element('#UserController').scope().$apply();
                    }
                });
            }());

            (function () {
                var joinOrganisationForm = $('#joinOrganisationForm');
                $('select', joinOrganisationForm).select2();
                $('.select2-selection', joinOrganisationForm).on('keyup', function (e) {
                    if (e.keyCode === 13) {
                        angular.element('#UserController').scope().joinOrganisation.submit();
                        angular.element('#UserController').scope().$apply();
                    }
                });
            }());
        </script>
    </div>
<?php require __DIR__ . '/footer.php' ?>