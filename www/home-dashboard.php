<?php
/** @var $totalProjects int */
/** @var $latestStories \DSI\Entity\Story[] */

use \DSI\Service\URL;

require __DIR__ . '/header.php';
?>

    <div class="container-wide archive">
        <div class="intro-title">
            <h1 class="page-h1 dash">Dashboard</h1>
            <div class="dashboard-stats">
                <div><strong><?php echo number_format($totalProjects) ?></strong> Projects have been created so far
                </div>
            </div>
        </div>
        <div class="w-row dashboard-widgets">
            <div class="w-col w-col-4 w-col-stack notification-col">
                <div class="dashboard-widget">
                    <h3 class="card-h3">Notifications</h3>
                    <div class="card-p notification-stat">You don't have any activity at the moment</div>
                </div>
                <div class="dashboard-widget">
                    <h3 class="card-h3">Notifications</h3>
                    <div class="card-p notification-stat">You currently have <strong>3 </strong>requests pending</div>
                    <ul class="w-list-unstyled notification-list">
                        <li class="notification-li">
                            <div class="w-clearfix card-notification" data-ix="notification-interaction">
                                <div class="notification-profile-image"></div>
                                <div class="notification-detail"><strong>Jason Smith</strong> has added you to the
                                    <strong>RaspberryPi</strong> project
                                </div>
                                <div class="notification-interaction">
                                    <a class="w-button dsi-button notification-accept" href="#">Accept</a>
                                    <a class="w-button dsi-button notification-decline" href="#">Decline</a>
                                    <a class="w-button dsi-button notification-view" href="#">View</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="w-clearfix card-notification" data-ix="notification-interaction">
                                <div class="notification-profile-image"></div>
                                <div class="notification-detail"><strong>Kat Sperry</strong>&nbsp;has invited you to
                                    join <strong>Nesta</strong>
                                </div>
                                <div class="notification-interaction">
                                    <a class="w-button dsi-button notification-accept" href="#">Accept</a>
                                    <a class="w-button dsi-button notification-decline" href="#">Decline</a>
                                    <a class="w-button dsi-button notification-view" href="#">View</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="w-clearfix card-notification" data-ix="notification-interaction">
                                <div class="notification-profile-image"></div>
                                <div class="notification-detail"><strong>Daniel Pettifer</strong>&nbsp;has removed you
                                    from <strong>DSI4EU</strong>
                                </div>
                                <div class="notification-interaction">
                                    <a class="w-button dsi-button notification-decline" href="#">Dismiss</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-col w-col-4 w-col-stack">
                <div class="dashboard-widget">
                    <h3 class="card-h3">Projects &amp; Organisations</h3>
                    <div class="card-p notification-stat">You have created <strong>0 </strong>project(s)</div>
                    <div class="card-p notification-stat">You are not participating in any projects at the moment. You
                        can create a new project, or request to join an exisiting project
                    </div>
                    <div class="w-row create-or-join">
                        <div class="w-col w-col-6">
                            <a class="w-button dsi-button create" href="#">Create +</a>
                        </div>
                        <div class="w-col w-col-6">
                            <a class="w-button dsi-button dash-join" href="#">Join</a>
                        </div>
                    </div>
                    <div class="card-p notification-stat">You are a member of&nbsp;<strong>0</strong> organisation(s)
                    </div>
                    <div class="card-p notification-stat">You are not associated with any organisations at the moment.
                        You can either create a new organisation, or request to become a member of an existing
                        organisation
                    </div>
                    <div class="w-row create-or-join">
                        <div class="w-col w-col-6">
                            <a class="w-button dsi-button create" href="#">Create +</a>
                        </div>
                        <div class="w-col w-col-6">
                            <a class="w-button dsi-button dash-join" href="#">Join</a>
                        </div>
                    </div>
                </div>
                <div class="dashboard-widget">
                    <h3 class="card-h3">Projects &amp; Organisations</h3>
                    <div class="card-p notification-stat">You have created <strong>4 </strong>projects</div>
                    <ul class="w-list-unstyled">
                        <li>
                            <div class="w-clearfix card-notification card-project" data-ix="notification-interaction">
                                <div class="notification-profile-image"></div>
                                <div class="notification-detail project"><strong>A project beginnin...</strong>
                                </div>
                                <div class="notification-interaction">
                                    <a class="w-button dsi-button notification-decline" href="#">Delete</a>
                                    <a class="w-button dsi-button notification-view notification-project"
                                       href="#">View</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="w-clearfix card-notification card-project" data-ix="notification-interaction">
                                <div class="notification-profile-image"></div>
                                <div class="notification-detail project"><strong>Another project</strong>
                                </div>
                                <div class="notification-interaction">
                                    <a class="w-button dsi-button notification-decline" href="#">Delete</a>
                                    <a class="w-button dsi-button notification-view notification-project"
                                       href="#">View</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="w-clearfix card-notification card-project" data-ix="notification-interaction">
                                <div class="notification-profile-image"></div>
                                <div class="notification-detail project"><strong>A project</strong>&nbsp;</div>
                                <div class="notification-interaction">
                                    <a class="w-button dsi-button notification-decline" href="#">Delete</a>
                                    <a class="w-button dsi-button notification-view notification-project"
                                       href="#">View</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="w-clearfix card-notification card-project" data-ix="notification-interaction">
                                <div class="notification-profile-image"></div>
                                <div class="notification-detail project"><strong>Who's project is th...</strong>&nbsp;
                                </div>
                                <div class="notification-interaction">
                                    <a class="w-button dsi-button notification-decline" href="#">Delete</a>
                                    <a class="w-button dsi-button notification-view notification-project"
                                       href="#">View</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="card-p notification-stat">You are a member of <strong>2</strong> organisations</div>
                    <ul class="w-list-unstyled">
                        <li>
                            <div class="w-clearfix card-notification card-project" data-ix="notification-interaction">
                                <div class="notification-profile-image"></div>
                                <div class="notification-detail project"><strong>Nesta</strong>
                                </div>
                                <div class="notification-interaction">
                                    <a class="w-button dsi-button notification-decline" href="#">Delete</a>
                                    <a class="w-button dsi-button notification-view notification-project"
                                       href="#">View</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="w-clearfix card-notification card-project" data-ix="notification-interaction">
                                <div class="notification-profile-image"></div>
                                <div class="notification-detail project"><strong>DSI4EU</strong>
                                </div>
                                <div class="notification-interaction">
                                    <a class="w-button dsi-button notification-decline" href="#">Delete</a>
                                    <a class="w-button dsi-button notification-view notification-project"
                                       href="#">View</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="w-col w-col-4 w-col-stack">
                <div class="dashboard-widget">
                    <h3 class="card-h3">Latest Updates</h3>
                    <div class="card-p notification-stat">You are not following any projects or organisations at the
                        moment. This area will start to populate as you interact with your favourite projects and
                        organisations
                    </div>
                </div>
                <div class="dashboard-widget">
                    <h3 class="card-h3">Latest updates</h3>
                    <div class="card-p notification-stat">News from projects &amp; organisations you follow</div>
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
                                        <?php echo show_input(substr(strip_tags($story->getContent()), 0, 25)) ?>
                                        Join us in Brussels the 29th of June at DG Connect for a
                                        Policy Workshop on Digital Social Innovation...
                                    </div>
                                    <div class="notification-interaction">
                                        <a class="w-button dsi-button notification-decline stop-following" href="#">Stop
                                            following</a>
                                        <a class="w-button dsi-button notification-view notification-project read-post"
                                           href="<?php echo URL::story($story->getId(), $story->getTitle()) ?>">Read</a>
                                    </div>
                                    <div class="post-card-date">25th April 2016</div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>