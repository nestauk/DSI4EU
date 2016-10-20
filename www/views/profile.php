<?php
require __DIR__ . '/header.php';
/** @var $userID int */
/** @var $isOwner bool */
/** @var $loggedInUser \DSI\Entity\User */
/** @var $user \DSI\Entity\User */
/** @var $projects \DSI\Entity\Project[] */
/** @var $organisations \DSI\Entity\Organisation[] */
/** @var $userLinks string[] */
/** @var $urlHandler \DSI\Service\URL */
?>
    <script type="text/javascript">
        profileUserID = '<?php echo $userID?>';
    </script>

    <div ng-controller="UserController as ctrl" id="UserController">
        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8">
                    <img class="large-profile-img"
                         src="<?php echo \DSI\Entity\Image::PROFILE_PIC_URL . $user->getProfilePicOrDefault() ?>">
                    <h1 class="content-h1 profile-h1"><?php echo show_input($user->getFullName()) ?></h1>
                    <div class="profile-job-title">
                        <?php echo show_input($user->getJobTitle()) ?>
                        <?php if ($user->getJobTitle() AND $user->getCompany()) echo ' at ' ?>
                        <?php echo show_input($user->getCompany()) ?>
                    </div>
                    <p class="intro"><?php echo nl2br(show_input($user->getBio())) ?></p>
                    <h3>Location</h3>
                    <p>
                        <?php echo show_input($user->getCityName()) ?>
                        <?php if ($user->getCityName() != '' AND $user->getCountryName() != '') echo ', '; ?>
                        <?php echo show_input($user->getCountryName()) ?>
                    </p>
                </div>
                <div class="sidebar w-col w-col-4">
                    <?php if ($isOwner) { ?>
                        <?php require __DIR__ . '/partialViews/profile-menu.php' ?>
                    <?php } elseif ($loggedInUser->isSysAdmin()) { ?>
                        <h1 class="content-h1 side-bar-space-h1">Actions</h1>
                        <a class="sidebar-link" href="<?php echo $urlHandler->editUserProfile($user) ?>">
                            <span class="green">-&nbsp;</span>Edit profile</a>
                        <?php /* <a class="sidebar-link" href="<?php echo $urlHandler->logout() ?>">
                            <span class="green">- Sign out</span></a> */ ?>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="content-directory">
            <div class="container-wide">
                <div class="profile-info w-row">
                    <div class="w-col w-col-4 w-col-stack">
                        <div class="info-card">
                            <?php if ($userLinks) { ?>
                                <h3 class="card-h info-h">Contact me</h3>
                                <ul class="w-list-unstyled">
                                    <?php /*
                                    <li class="profile-contact-link">
                                        <a class="link profile-contact-link" href="#">Daniel.pettifer@nesta.org.uk</a>
                                    </li>
                                    */ ?>
                                    <?php foreach ($userLinks AS $link) { ?>
                                        <li class="profile-contact-link">
                                            <a <?php if (!$user->isCommunityAdmin() AND !$user->isEditorialAdmin()) echo 'rel="nofollow"' ?>
                                                class="link profile-contact-link" href="<?php echo $link ?>">
                                                <?php echo show_input($link) ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                            <div ng-show="skills" ng-cloak>
                                <h3 class="card-h info-h">My skills:</h3>
                                <div class="tags-block">
                                    <div class="tag" ng-repeat="skill in skills">
                                        <div ng-bind="skill"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-col w-col-4 w-col-stack">
                        <div class="info-card">
                            <h3 class="card-h info-h">Projects i'm involved with</h3>
                            <div class="list-items">
                                <a class="partner-link w-inline-block" href="{{project.url}}"
                                   ng-repeat="project in user.projects" ng-cloak>
                                    <div class="list-item w-clearfix">
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
                                    <a class="btn btn-join w-button" <?php /* data-ix="show-join-project" */ ?>
                                       href="<?php echo $urlHandler->editUserProfile($loggedInUser) ?>#step3">
                                        Add new project +
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="w-col w-col-4 w-col-stack">
                        <div class="info-card">
                            <h3 class="card-h3">Organisations i'm involved with</h3>
                            <div class="list-items">
                                <a class="partner-link w-inline-block" href="{{organisation.url}}"
                                   ng-repeat="organisation in user.organisations" ng-cloak>
                                    <div class="list-item w-clearfix">
                                        <div class="partner-title" ng-bind="organisation.name"></div>
                                        <div class="no-of-projects">
                                            <span ng-bind="organisation.membersCount"></span>
                                            <span
                                                ng-bind="organisation.membersCount == 1 ? 'Project' : 'Projects'"></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php if ($isOwner) { ?>
                                <div class="join-project">
                                    <a class="btn btn-join w-button" <?php /* data-ix="show-join-organisation" */ ?>
                                       href="<?php echo $urlHandler->editUserProfile($loggedInUser) ?>#step3">
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

            <div class="join-project-modal modal">
                <div class="modal-container">
                    <div class="modal-helper">
                        <div class="modal-content">
                            <h1 class="centered" style="padding-top:125px">
                                Join project
                            </h1>
                            <div class="w-form">
                                <form ng-submit="joinProject.submit()" id="joinProjectForm" style="text-align:center">
                                    <select style="width:60%" data-placeholder="Select a project"
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

                                    <input class="btn btn-join w-button" type="submit"
                                           style="position:static;min-width:200px"
                                           ng-value="joinProject.loading ? 'Saving...' : 'Join'"
                                           ng-disabled="joinProject.loading">
                                </form>
                            </div>
                            <div class="cancel">
                                <a href="#" data-ix="close-join-project"><?php _ehtml('Cancel') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="join-organisation-modal modal">
                <div class="modal-container">
                    <div class="modal-helper">
                        <div class="modal-content">
                            <h1 class="centered" style="padding-top:125px">
                                Join Organisation
                            </h1>
                            <div class="w-form">
                                <form ng-submit="joinOrganisation.submit()" id="joinOrganisationForm"
                                      style="text-align:center">
                                    <select style="width:60%" data-placeholder="Select an organisation"
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

                                    <input class="btn btn-join w-button" type="submit"
                                           style="position:static;min-width:200px"
                                           ng-value="joinOrganisation.loading ? 'Saving...' : 'Join'"
                                           ng-disabled="joinOrganisation.loading">
                                </form>
                            </div>
                            <div class="cancel">
                                <a href="#" data-ix="close-join-organisation"><?php _ehtml('Cancel') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <script type="text/javascript"
                src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/UserController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

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