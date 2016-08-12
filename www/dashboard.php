<?php
/** @var $latestStories \DSI\Entity\Story[] */
/** @var $projectsMember \DSI\Entity\ProjectMember[] */
/** @var $organisationsMember \DSI\Entity\OrganisationMember[] */

use \DSI\Service\URL;

if(!isset($urlHandler))
    $urlHandler = new URL();

require __DIR__ . '/header.php';
?>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/DashboardController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>
    <div ng-controller="DashboardController">

        <div class="w-section page-header">
            <div class="container-wide header">
                <h1 class="page-h1 light">My dashboard</h1>
            </div>
        </div>

        <div class="container-wide archive">
            <div class="w-row dashboard-widgets">
                <div class="w-col w-col-4 w-col-stack notification-col">
                    <div class="dashboard-widget">
                        <h3 class="card-h3">Notifications</h3>
                        <div class="card-p notification-stat" ng-cloak
                             ng-hide="projectInvitations.length + organisationInvitations.length">
                            You don't have any notifications at the moment
                        </div>
                        <div class="card-p notification-stat" ng-cloak
                             ng-show="projectInvitations.length + organisationInvitations.length">
                            You currently have
                            <strong ng-bind="projectInvitations.length + organisationInvitations.length"></strong>
                            notification(s)
                        </div>
                        <ul class="w-list-unstyled notification-list" ng-cloak>
                            <li class="notification-li" ng-repeat="invitation in projectInvitations">
                                <div class="w-clearfix card-notification notification-interaction-actions">
                                    <div class="notification-profile-image"></div>
                                    <div class="notification-detail">
                                        You have been added to the <strong ng-bind="invitation.name"></strong> project
                                    </div>
                                    <div class="notification-interaction">
                                        <a class="w-button dsi-button notification-accept" href="#"
                                           ng-click="approveProjectInvitation(invitation)">Accept</a>
                                        <a class="w-button dsi-button notification-decline" href="#"
                                           ng-click="declineProjectInvitation(invitation)">Decline</a>
                                        <a class="w-button dsi-button notification-view"
                                           href="{{invitation.url}}">View</a>
                                    </div>
                                </div>
                            </li>
                            <li class="notification-li" ng-repeat="invitation in organisationInvitations">
                                <div class="w-clearfix card-notification notification-interaction-actions">
                                    <div class="notification-profile-image"></div>
                                    <div class="notification-detail">
                                        You have been invited to join <strong ng-bind="invitation.name"></strong>
                                    </div>
                                    <div class="notification-interaction">
                                        <a class="w-button dsi-button notification-accept" href="#"
                                           ng-click="approveOrganisationInvitation(invitation)">Accept</a>
                                        <a class="w-button dsi-button notification-decline" href="#"
                                           ng-click="declineOrganisationInvitation(invitation)">Decline</a>
                                        <a class="w-button dsi-button notification-view"
                                           href="{{invitation.url}}">View</a>
                                    </div>
                                </div>
                            </li>
                            <?php /*
                            <li>
                                <div class="w-clearfix card-notification" data-ix="notification-interaction">
                                    <div class="notification-profile-image"></div>
                                    <div class="notification-detail"><strong>Daniel Pettifer</strong>&nbsp;has removed
                                        you
                                        from <strong>DSI4EU</strong>
                                    </div>
                                    <div class="notification-interaction">
                                        <a class="w-button dsi-button notification-decline" href="#">Dismiss</a>
                                    </div>
                                </div>
                            </li>
                            */ ?>
                        </ul>
                    </div>
                </div>
                <div class="w-col w-col-4 w-col-stack">
                    <div class="dashboard-widget">
                        <h3 class="card-h3">Projects &amp; Organisations</h3>

                        <?php if (count($projectsMember) == 0) { ?>
                            <div class="card-p notification-stat">
                                You are not participating in any projects at the moment.
                                You can create a new project, or request to join an exisiting project
                            </div>
                            <div class="w-row create-or-join">
                                <div class="w-col w-col-6">
                                    <a class="w-button dsi-button create" href="#" data-ix="create-project-modal">Create
                                        +</a>
                                </div>
                                <div class="w-col w-col-6">
                                    <a class="w-button dsi-button dash-join"
                                       href="<?php echo $urlHandler->projects() ?>">Join</a>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="card-p notification-stat">
                                You are participating in <strong><?php echo count($projectsMember) ?> </strong>
                                <?php echo count($projectsMember) == 1 ? 'project' : 'projects' ?>
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
                                                    View
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } ?>

                        <?php if (count($organisationsMember) == 0) { ?>
                            <div class="card-p notification-stat">
                                You are not associated with any organisations at the moment.
                                You can either create a new organisation, or request to become a member of an existing
                                organisation
                            </div>
                            <div class="w-row create-or-join">
                                <div class="w-col w-col-6">
                                    <a class="w-button dsi-button create" href="#" data-ix="create-organisation-modal"
                                       data-w-tab="Tab 2">Create +</a>
                                </div>
                                <div class="w-col w-col-6">
                                    <a class="w-button dsi-button dash-join" href="<?php echo $urlHandler->organisations() ?>">Join</a>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="card-p notification-stat">
                                You are a member of <strong><?php echo count($organisationsMember) ?></strong>
                                <?php echo count($organisationsMember) == 1 ? 'organisation' : 'organisations' ?>
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
                                                   href="<?php echo URL::organisation($organisation) ?>">
                                                    View
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
                        <h3 class="card-h3">Latest updates</h3>
                        <ul class="w-list-unstyled">
                            <?php foreach ($latestStories AS $story) { ?>
                                <li>
                                    <div class="w-clearfix card-notification latest-post"
                                         data-ix="notification-interaction">
                                        <?php if ($category = $story->getStoryCategory()) { ?>
                                            <div class="post-card-category">
                                                <?php echo $category->getName(); ?>
                                            </div>
                                        <?php } ?>
                                        <div class="notification-profile-image post-latest"></div>
                                        <div class="notification-detail post-latest-card">
                                            <strong>
                                                <?php echo show_input($story->getTitle()) ?>
                                            </strong>
                                        </div>
                                        <div class="latest-post-p">
                                            <?php echo show_input(substr(strip_tags($story->getContent()), 0, 170)) ?>
                                        </div>
                                        <div class="notification-interaction">
                                            <a class="w-button dsi-button notification-decline stop-following"
                                               href="<?php echo URL::blogPost($story) ?>" style="color:#18233f">
                                                Read
                                            </a>
                                        </div>
                                        <div class="post-card-date"><?php echo $story->getDatePublished('jS F Y')?></div>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

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