<?php
/** @var $updates \DSI\Controller\DashboardController_Update[] */
/** @var $loggedInUser \DSI\Entity\User */
/** @var $projectsMember \DSI\Entity\ProjectMember[] */
/** @var $organisationsMember \DSI\Entity\OrganisationMember[] */

use Services\URL;

if (!isset($urlHandler))
    $urlHandler = new URL();

require __DIR__ . '/header.php';
?>
    <div ng-controller="DashboardController"
         data-dashboardjsonurl="<?php echo $urlHandler->dashboard('json') ?>">

        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1"><?php _ehtml('My dashboard') ?></h1>
                    <p class="intro"><?php _e('Stay up to date on news, projects and organisations, and people') ?></p>
                    <?php /* <p class="header-intro-descr">Dashboard explanation text</p> */ ?>
                </div>
                <div class="sidebar w-col w-col-4 w-col-stack">
                    <?php require __DIR__ . '/partialViews/profile-menu.php' ?>
                    <a class="sidebar-link remove" href="#" ng-click="terminateAccount()">
                        <span class="green">- </span><?php _e('Terminate account') ?></a>
                </div>
            </div>
        </div>

        <div class="content-directory">
            <div class="archive container-wide">
                <div class="dashboard-widgets w-row">
                    <div class="w-col w-col-4 w-col-stack notification-col">
                        <div class="dashboard-widget">
                            <h3 class="card-h3"><?php _ehtml('Notifications') ?></h3>
                            <div class="card-p notification-stat" ng-cloak ng-hide="notificationsCount()">
                                <?php _ehtml("You don't have any notifications at the moment.") ?>
                            </div>
                            <div class="card-p notification-stat" ng-cloak ng-show="notificationsCount()">
                                <?php echo sprintf(
                                    __('You currently have %s notification(s)'),
                                    '<strong ng-bind="notificationsCount()"></strong>'
                                ) ?>
                            </div>
                            <ul class="w-list-unstyled notification-list" ng-cloak>
                                <li class="notification-li" ng-repeat="invitation in notifications.projectInvitations">
                                    <div class="w-clearfix card-notification notification-interaction-actions">
                                        <div class="notification-profile-image"></div>
                                        <div class="notification-detail">
                                            <?php echo sprintf(
                                                __('You have been added to the %s project'),
                                                '<strong ng-bind="invitation.name"></strong>'
                                            ) ?>
                                        </div>
                                        <div class="notification-interaction">
                                            <a class="w-button dsi-button notification-accept" href="#"
                                               ng-click="approveProjectInvitation(invitation)"><?php _ehtml('Accept') ?></a>
                                            <a class="w-button dsi-button notification-decline" href="#"
                                               ng-click="declineProjectInvitation(invitation)"><?php _ehtml('Decline') ?></a>
                                            <a class="w-button dsi-button notification-view"
                                               href="{{invitation.url}}"><?php _ehtml('View') ?></a>
                                        </div>
                                    </div>
                                </li>
                                <li class="notification-li"
                                    ng-repeat="invitation in notifications.organisationInvitations">
                                    <div class="w-clearfix card-notification notification-interaction-actions">
                                        <div class="notification-profile-image"></div>
                                        <div class="notification-detail">
                                            <?php echo sprintf(
                                                __('You have been invited to join %s {organisation}'),
                                                '<strong ng-bind="invitation.name"></strong>'
                                            ) ?>
                                        </div>
                                        <div class="notification-interaction">
                                            <a class="w-button dsi-button notification-accept" href="#"
                                               ng-click="approveOrganisationInvitation(invitation)"><?php _ehtml('Accept') ?></a>
                                            <a class="w-button dsi-button notification-decline" href="#"
                                               ng-click="declineOrganisationInvitation(invitation)"><?php _ehtml('Decline') ?></a>
                                            <a class="w-button dsi-button notification-view"
                                               href="{{invitation.url}}"><?php _ehtml('View') ?></a>
                                        </div>
                                    </div>
                                </li>
                                <li class="notification-li" ng-repeat="invitation in notifications.projectRequests">
                                    <div class="w-clearfix card-notification notification-interaction-actions">
                                        <div class="notification-profile-image"></div>
                                        <div class="notification-detail">
                                            <?php echo sprintf(
                                                __('%s requested to join %s'),
                                                '<a style="font-weight:bold" href="{{invitation.user.url}}" ng-bind="invitation.user.name"></a>',
                                                '<a style="font-weight:bold" href="{{invitation.project.url}}" ng-bind="invitation.project.name"></a>'
                                            ) ?>
                                        </div>
                                        <div class="notification-interaction">
                                            <a class="w-button dsi-button notification-accept" href="#"
                                               ng-click="approveProjectRequest(invitation)"><?php _ehtml('Accept') ?></a>
                                            <a class="w-button dsi-button notification-decline" href="#"
                                               ng-click="declineProjectRequest(invitation)"><?php _ehtml('Decline') ?></a>
                                        </div>
                                    </div>
                                </li>
                                <li class="notification-li"
                                    ng-repeat="invitation in notifications.organisationRequests">
                                    <div class="w-clearfix card-notification notification-interaction-actions">
                                        <div class="notification-profile-image"></div>
                                        <div class="notification-detail">
                                            <?php echo sprintf(
                                                __('%s requested to join %s'),
                                                '<a style="font-weight:bold" href="{{invitation.user.url}}" ng-bind="invitation.user.name"></a>',
                                                '<a style="font-weight:bold" href="{{invitation.organisation.url}}" ng-bind="invitation.organisation.name"></a>'
                                            ) ?>
                                        </div>
                                        <div class="notification-interaction">
                                            <a class="w-button dsi-button notification-accept" href="#"
                                               ng-click="approveOrganisationRequest(invitation)"><?php _ehtml('Accept') ?></a>
                                            <a class="w-button dsi-button notification-decline" href="#"
                                               ng-click="declineOrganisationRequest(invitation)"><?php _ehtml('Decline') ?></a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <?php if ($loggedInUser->isCommunityAdmin()) { ?>
                            <div class="dashboard-widget">
                                <h3 class="card-h3"><?php _ehtml('Admin') ?></h3>
                                <div class="card-p notification-stat">
                                    <?php /*
                                    <a class="sidebar-link" href="<?php echo $urlHandler->home() ?>">
                                        <span class="green">-&nbsp;</span>Manage tags
                                    </a>
                                    */ ?>
                                    <a class="sidebar-link" href="<?php echo $urlHandler->addFunding() ?>">
                                        <span class="green">-&nbsp;</span>Add funding
                                    </a>
                                    <a class="sidebar-link" href="<?php echo $urlHandler->addCaseStudy() ?>">
                                        <span class="green">-&nbsp;</span>Add case study
                                    </a>
                                    <a class="sidebar-link" href="<?php echo $urlHandler->addEvent() ?>">
                                        <span class="green">-&nbsp;</span>Add event
                                    </a>
                                    <a class="sidebar-link" href="<?php echo $urlHandler->messageCommunityAdmins() ?>">
                                        <span class="green">-&nbsp;</span>Message all community admins
                                    </a>
                                    <a class="sidebar-link" href="<?php echo $urlHandler->manageTags() ?>">
                                        <span class="green">-&nbsp;</span>See all tags
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="w-col w-col-4 w-col-stack">
                        <div class="dashboard-widget">
                            <h3 class="card-h3"><?php _ehtml('Projects & Organisations') ?></h3>

                            <?php if (count($projectsMember) == 0) { ?>
                                <div class="card-p notification-stat">
                                    <?php _ehtml('You are not participating in any projects at the moment.') ?>
                                    <?php _ehtml('You can create a new project, or request to join an existing project.') ?>
                                </div>
                                <div class="w-row create-or-join">
                                    <div class="w-col w-col-6">
                                        <a class="w-button dsi-button create ix-create-project-modal" href="#">
                                            <?php _ehtml('Create +') ?></a>
                                    </div>
                                    <div class="w-col w-col-6">
                                        <a class="w-button dsi-button dash-join"
                                           href="<?php echo $urlHandler->projects() ?>"><?php _ehtml('Join') ?></a>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="card-p notification-stat">
                                    <?php if (count($projectsMember) == 1) {
                                        _ehtml('You are participating in one project.');
                                    } else {
                                        echo sprintf(
                                            __('You are participating in %s projects.'),
                                            '<strong>' . count($projectsMember) . '</strong>'
                                        );
                                    } ?>
                                </div>
                                <ul class="w-list-unstyled">
                                    <?php foreach ($projectsMember AS $projectMember) { ?>
                                        <?php $project = $projectMember->getProject() ?>
                                        <li>
                                            <div class="w-clearfix card-notification card-project"
                                                 data-ix="notification-interaction">
                                                <div class="notification-profile-image"></div>
                                                <div class="notification-detail project">
                                                    <strong><?php echo show_input($project->getName()) ?></strong>
                                                </div>
                                                <div class="notification-interaction">
                                                    <?php /* <a class="w-button dsi-button notification-decline" href="#">Delete</a> */ ?>
                                                    <a class="w-button dsi-button notification-view notification-project"
                                                       href="<?php echo $urlHandler->project($project) ?>">
                                                        <?php _ehtml('View') ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>

                            <?php if (count($organisationsMember) == 0) { ?>
                                <div class="card-p notification-stat">
                                    <?php _ehtml('You are not associated with any organisations at the moment.') ?>
                                    <?php _ehtml('You can either create a new organisation, or request to become a member of an existing organisation.') ?>
                                </div>
                                <div class="w-row create-or-join">
                                    <div class="w-col w-col-6">
                                        <a class="w-button dsi-button create ix-create-organisation-modal" href="#"
                                           data-w-tab="Tab 2"><?php _ehtml('Create +') ?></a>
                                    </div>
                                    <div class="w-col w-col-6">
                                        <a class="w-button dsi-button dash-join"
                                           href="<?php echo $urlHandler->organisations() ?>"><?php _ehtml('Join') ?></a>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="card-p notification-stat">
                                    <?php if (count($organisationsMember) == 1) {
                                        _ehtml('You are a member of one organisation');
                                    } else {
                                        echo sprintf(
                                            __('You are a member of %s organisations'),
                                            '<strong>' . count($organisationsMember) . '</strong>'
                                        );
                                    } ?>
                                </div>
                                <ul class="w-list-unstyled">
                                    <?php foreach ($organisationsMember AS $organisationMember) { ?>
                                        <?php $organisation = $organisationMember->getOrganisation() ?>
                                        <li>
                                            <div class="w-clearfix card-notification card-project"
                                                 data-ix="notification-interaction">
                                                <div class="notification-profile-image"></div>
                                                <div class="notification-detail project">
                                                    <strong><?php echo show_input($organisation->getName()) ?></strong>
                                                </div>
                                                <div class="notification-interaction">
                                                    <?php /* <a class="w-button dsi-button notification-decline" href="#">Delete</a> */ ?>
                                                    <a class="w-button dsi-button notification-view notification-project"
                                                       href="<?php echo $urlHandler->organisation($organisation) ?>">
                                                        <?php _ehtml('View') ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="w-col w-col-4 w-col-stack">
                        <div class="dashboard-widget">
                            <h3 class="card-h3"><?php _ehtml('Latest updates') ?></h3>
                            <ul class="w-list-unstyled">
                                <?php foreach ($updates AS $update) { ?>
                                    <li>
                                        <div class="w-clearfix card-notification latest-post"
                                             data-ix="notification-interaction">
                                            <div class="notification-profile-image post-latest"></div>
                                            <div class="notification-detail post-latest-card">
                                                <strong>
                                                    <?php echo show_input($update->title) ?>
                                                </strong>
                                            </div>
                                            <div class="latest-post-p">
                                                <?php echo show_input($update->content) ?>
                                            </div>
                                            <div class="notification-interaction">
                                                <a class="w-button dsi-button notification-decline stop-following"
                                                   href="<?php echo $update->link ?>" style="color:#18233f">
                                                    <?php _ehtml('Read') ?>
                                                </a>
                                            </div>
                                            <div class="post-card-date"><?php echo $update->getPublishDate() ?></div>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        var translate = new Translate();
        <?php foreach([
            'Are you sure you want to terminate your account?',
            'An email will be sent to you to confirm your request.',
            'Yes',
        ] AS $translate) { ?>
        translate.set('<?php echo show_input($translate)?>', '<?php _ehtml($translate)?>');
        <?php } ?>
    </script>

    <script type="text/javascript"
            src="/js/controllers/DashboardController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <script type="text/javascript">
        jQuery(function ($) {
            $('.notification-list').on('mouseenter', '.notification-interaction-actions', function () {
                $('.notification-interaction', $(this)).css('opacity', 1);
            }).on('mouseleave', '.notification-interaction-actions', function () {
                $('.notification-interaction', $(this)).css('opacity', 0);
            })
        })
    </script>

<?php require __DIR__ . '/footer.php' ?>
