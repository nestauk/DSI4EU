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

        <div class="w-section page-header">
            <div class="container-wide header">
                <h1 class="page-h1 light">
                    <?php echo show_input($user->getFirstName()) ?>
                    <?php echo show_input($user->getLastName()) ?>
                </h1>
                <div class="position">
                    <?php echo show_input($user->getJobTitle()) ?> at
                    <?php echo show_input($user->getCompany()) ?>
                </div>
                <img class="large-profile-img"
                     src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $user->getProfilePicOrDefault() ?>">
                <?php if ($isOwner) { ?>
                    <a class="w-button dsi-button profile-edit" href="<?php echo \DSI\Service\URL::editProfile() ?>">
                        Edit profile</a>
                <?php } ?>
            </div>
        </div>

        <?php require(__DIR__ . '/partialViews/search-results.php'); ?>

        <div class="w-section project-section">
            <div class="container-wide">
                <div class="w-row profile-info">
                    <div class="w-col w-col-4 w-col-stack">
                        <div class="info-card">
                            <h3 class="info-h card-h">About me</h3>
                            <p class="project-summary" ng-bind="user.bio" style="white-space: pre-line">
                                <?php echo nl2br(show_input($user->getBio())) ?>
                            </p>
                            <?php if ($user->getCityName() OR $user->getCountryName()) { ?>
                                <h3 class="info-h card-h">Location</h3>
                                <p class="project-summary">
                                    <?php echo $user->getCityName() ?>,
                                    <?php echo $user->getCountryName() ?>
                                </p>
                            <?php } ?>

                            <h3 class="info-h card-h">My skills:</h3>
                            <div class="w-clearfix tags-block">
                                <div class="skill" ng-repeat="skill in skills" ng-cloak>
                                    <?php if ($isOwner) { ?>
                                        <div class="delete" ng-click="removeSkill(skill)">-</div>
                                    <?php } ?>
                                    <div ng-bind="skill"></div>
                                </div>
                            </div>
                            <?php if ($isOwner) { ?>
                                <div class="w-clearfix add-new">
                                    <a class="w-button dsi-button add-new-item" href="#"
                                       ng-click="showAddSkill = !showAddSkill"
                                       ng-bind="showAddSkill ? 'Close' : 'Add new skill +'">Add new skill +</a>
                                </div>

                                <div class="add-new-input" ng-show="showAddSkill">
                                    <div class="w-form">
                                        <form class="w-clearfix" id="email-form-2"
                                              name="email-form-2">
                                            <select data-tags="true"
                                                    data-placeholder="Add your skill"
                                                    id="Add-skill" name="Add-skill"
                                                    class="w-input add-skill"
                                                    multiple
                                                    style="width:200px">
                                                <option></option>
                                            </select>
                                            <input class="w-button add-new-input-button" data-wait="Please wait..."
                                                   type="submit" value="Add +">
                                        </form>
                                    </div>
                                </div>
                            <?php } ?>

                            <h3 class="info-h card-h">My Languages:</h3>
                            <div class="w-clearfix tags-block">
                                <div class="skill" ng-repeat="lang in languages" ng-cloak>
                                    <?php if ($isOwner) { ?>
                                        <div class="delete" ng-click="removeLanguage(lang)">-</div>
                                    <?php } ?>
                                    <div ng-bind="lang"></div>
                                </div>
                            </div>
                            <?php if ($isOwner) { ?>
                                <div class="w-clearfix add-new" ng-click="showAddLanguage = !showAddLanguage">
                                    <a class="w-button dsi-button add-new-item" href="#"
                                       ng-bind="showAddLanguage ? 'Close' : 'Add new language +'">Add new language +</a>
                                </div>

                                <div class="add-new-input" ng-show="showAddLanguage">
                                    <div class="w-form">
                                        <form class="w-clearfix" id="email-form-2"
                                              name="email-form-2">
                                            <select data-placeholder="Select your language"
                                                    id="Add-language" name="Add-language"
                                                    class="w-input add-language"
                                                    multiple
                                                    style="width:200px">
                                                <option></option>
                                            </select>
                                            <input class="w-button add-new-input-button" data-wait="Please wait..."
                                                   type="submit" value="Add +">
                                        </form>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="w-col w-col-4 w-col-stack">
                        <div class="info-card">
                            <h3 class="info-h card-h">Projects i'm involved with</h3>
                            <div class="list-items">
                                <a class="w-inline-block partner-link" href="#"
                                   ng-href="{{project.url}}"
                                   ng-repeat="project in user.projects" ng-cloak>
                                    <div class="w-clearfix list-item">
                                        <div class="partner-title" ng-bind="project.name"></div>
                                        <div class="no-of-projects">
                                            <span ng-bind="project.membersCount"></span>
                                            <span
                                                ng-bind="project.membersCount == 1 ? 'Contributor' : 'Contributors'"></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php if ($isOwner) { ?>
                                <div class="join-project">
                                    <a class="w-button btn btn-join" href="#"
                                       ng-click="editPanel = 'joinProject'"
                                       data-ix="show-profile-update">Add new project +</a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="w-col w-col-4 w-col-stack">
                        <div class="info-card">
                            <h3 class="info-h card-h">Organisations i'm involved with</h3>
                            <div class="list-items">
                                <a ng-href="{{organisation.url}}" href="#" class="w-inline-block partner-link"
                                   ng-repeat="organisation in user.organisations" ng-cloak>
                                    <div class="w-clearfix list-item">
                                        <div class="partner-title" ng-bind="organisation.name"></div>
                                        <div class="no-of-projects">
                                            <span ng-bind="organisation.membersCount"></span>
                                            <span ng-bind="organisation.membersCount == 1 ? 'Project' : 'Projects'"></span>
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