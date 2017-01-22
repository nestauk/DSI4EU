<?php
require __DIR__ . '/header.php';
/** @var $project \DSI\Entity\Project */
/** @var $userHasInvitation bool */
/** @var $userIsMember bool */
/** @var $userSentJoinRequest bool */
/** @var $userCanSendJoinRequest bool */
/** @var $userCanEditProject bool */
/** @var $userCanAddPost bool */
/** @var $userIsFollowing bool */
/** @var $isOwner bool */
/** @var $loggedInUser \DSI\Entity\User */
/** @var $projectMembers \DSI\Entity\ProjectMember[] */
/** @var $organisationProjectsObj \DSI\Entity\OrganisationProject[] */
/** @var $urlHandler \DSI\Service\URL */
?>

    <script src="<?php echo SITE_RELATIVE_PATH ?>/lib/rrssb-master/js/rrssb.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo SITE_RELATIVE_PATH ?>/lib/rrssb-master/css/rrssb.css">

    <div
            ng-controller="ProjectController"
            data-projectid="<?php echo $project->getId() ?>">

        <div class="case-study-intro">
            <div class="header-content">
                <div class="case-study-img-bg-blur"
                     style="background-image: linear-gradient(180deg, rgba(0, 0, 0, .3), rgba(0, 0, 0, .3)), url('<?php echo \DSI\Entity\Image::PROJECT_HEADER_URL . $project->getHeaderImageOrDefault() ?>');"></div>
                <div class="container-wide">
                    <h1 class="case-study-h1"><?php echo show_input($project->getName()) ?></h1>
                    <h3 class="home-hero-h3"></h3>
                </div>
            </div>
        </div>

        <div class="page-content">
            <div class="w-row">
                <div class="content-left w-col w-col-8">
                    <div class="intro"><?php echo show_input($project->getShortDescription()) ?></div>

                    <?php if ($project->getDescription()) { ?>
                        <h3 class="descr-h3"><?php _ehtml('What we do') ?></h3>
                        <div><?php echo $project->getDescription() ?></div>
                    <?php } ?>

                    <?php if ($project->getSocialImpact()) { ?>
                        <h3 class="descr-h3"><?php _ehtml('Our social impact') ?></h3>
                        <div><?php echo $project->getSocialImpact() ?></div>
                    <?php } ?>

                    <div class="involved">
                        <h3 class="descr-h3 space"><?php _ehtml("Who's involved") ?></h3>
                        <h4 class="involved-h4"><?php _ehtml('People') ?></h4>
                        <?php foreach ($projectMembers AS $projectMember) {
                            $member = $projectMember->getMember();
                            if (trim($member->getFullName()) == '') continue; ?>
                            <a class="involved-card w-inline-block"
                               href="<?php echo $urlHandler->profile($member) ?>">
                                <div class="involved-card">
                                    <div class="w-row">
                                        <div class="image-col w-col w-col-3 w-col-small-3 w-col-tiny-3">
                                            <img
                                                    src="<?php echo \DSI\Entity\Image::PROFILE_PIC_URL . $member->getProfilePicOrDefault() ?>"
                                                    class="involved-profile-img" width="50">
                                        </div>
                                        <div class="w-clearfix w-col w-col-9 w-col-small-9 w-col-tiny-9">
                                            <div class="card-name">
                                                <?php echo show_input($member->getFullName()) ?>
                                            </div>
                                            <div class="card-position">
                                                <?php echo show_input($member->getJobTitle()) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php } ?>
                        <h4 class="involved-h4 orgs-h"><?php _ehtml('Organisations') ?></h4>
                        <?php foreach ($organisationProjectsObj AS $organisationProject) {
                            $organisation = $organisationProject->getOrganisation(); ?>
                            <a class="sidebar-link" href="<?php echo $urlHandler->organisation($organisation) ?>">
                                <span class="green">-&nbsp;</span><?php echo show_input($organisation->getName()) ?></a>
                        <?php } ?>


                        <br/>
                        <!-- Buttons start here. Copy this ul to your document. -->
                        <ul class="rrssb-buttons clearfix">
                            <li class="rrssb-email">
                                <a href="mailto:?Subject=Digital%20Social%20Innovation">
                                    <span class="rrssb-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28">
                                            <path d="M20.11 26.147c-2.335 1.05-4.36 1.4-7.124 1.4C6.524 27.548.84 22.916.84 15.284.84 7.343 6.602.45 15.4.45c6.854 0 11.8 4.7 11.8 11.252 0 5.684-3.193 9.265-7.398 9.3-1.83 0-3.153-.934-3.347-2.997h-.077c-1.208 1.986-2.96 2.997-5.023 2.997-2.532 0-4.36-1.868-4.36-5.062 0-4.75 3.503-9.07 9.11-9.07 1.713 0 3.7.4 4.6.972l-1.17 7.203c-.387 2.298-.115 3.3 1 3.4 1.674 0 3.774-2.102 3.774-6.58 0-5.06-3.27-8.994-9.304-8.994C9.05 2.87 3.83 7.545 3.83 14.97c0 6.5 4.2 10.2 10 10.202 1.987 0 4.09-.43 5.647-1.245l.634 2.22zM16.647 10.1c-.31-.078-.7-.155-1.207-.155-2.572 0-4.596 2.53-4.596 5.53 0 1.5.7 2.4 1.9 2.4 1.44 0 2.96-1.83 3.31-4.088l.592-3.72z"/></svg>
                                    </span>
                                    <span class="rrssb-text">email</span>
                                </a>
                            </li>
                            <li class="rrssb-facebook">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=https://<?php echo SITE_DOMAIN . SITE_RELATIVE_PATH . $urlHandler->project($project) ?>"
                                   class="popup">
                                    <span class="rrssb-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29 29">
                                            <path d="M26.4 0H2.6C1.714 0 0 1.715 0 2.6v23.8c0 .884 1.715 2.6 2.6 2.6h12.393V17.988h-3.996v-3.98h3.997v-3.062c0-3.746 2.835-5.97 6.177-5.97 1.6 0 2.444.173 2.845.226v3.792H21.18c-1.817 0-2.156.9-2.156 2.168v2.847h5.045l-.66 3.978h-4.386V29H26.4c.884 0 2.6-1.716 2.6-2.6V2.6c0-.885-1.716-2.6-2.6-2.6z"/>
                                        </svg>
                                    </span>
                                    <span class="rrssb-text">facebook</span>
                                </a>
                            </li>
                            <li class="rrssb-twitter">
                                <a href="https://twitter.com/intent/tweet?text=https://<?php echo SITE_DOMAIN . SITE_RELATIVE_PATH . $urlHandler->project($project) ?>"
                                   class="popup">
                                    <span class="rrssb-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28">
                                            <path d="M24.253 8.756C24.69 17.08 18.297 24.182 9.97 24.62a15.093 15.093 0 0 1-8.86-2.32c2.702.18 5.375-.648 7.507-2.32a5.417 5.417 0 0 1-4.49-3.64c.802.13 1.62.077 2.4-.154a5.416 5.416 0 0 1-4.412-5.11 5.43 5.43 0 0 0 2.168.387A5.416 5.416 0 0 1 2.89 4.498a15.09 15.09 0 0 0 10.913 5.573 5.185 5.185 0 0 1 3.434-6.48 5.18 5.18 0 0 1 5.546 1.682 9.076 9.076 0 0 0 3.33-1.317 5.038 5.038 0 0 1-2.4 2.942 9.068 9.068 0 0 0 3.02-.85 5.05 5.05 0 0 1-2.48 2.71z"/>
                                        </svg>
                                    </span>
                                    <span class="rrssb-text">twitter</span>
                                </a>
                            </li>
                            <li class="rrssb-linkedin">
                                <!-- Replace href with your meta and URL information-->
                                <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=https://<?php echo SITE_DOMAIN . SITE_RELATIVE_PATH . $urlHandler->project($project) ?>"
                                   class="popup">
                                    <span class="rrssb-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 28 28">
                                            <path d="M25.424 15.887v8.447h-4.896v-7.882c0-1.98-.71-3.33-2.48-3.33-1.354 0-2.158.91-2.514 1.802-.13.315-.162.753-.162 1.194v8.216h-4.9s.067-13.35 0-14.73h4.9v2.087c-.01.017-.023.033-.033.05h.032v-.05c.65-1.002 1.812-2.435 4.414-2.435 3.222 0 5.638 2.106 5.638 6.632zM5.348 2.5c-1.676 0-2.772 1.093-2.772 2.54 0 1.42 1.066 2.538 2.717 2.546h.032c1.71 0 2.77-1.132 2.77-2.546C8.056 3.593 7.02 2.5 5.344 2.5h.005zm-2.48 21.834h4.896V9.604H2.867v14.73z"></path>
                                        </svg>
                                    </span>
                                    <span class="rrssb-text">linkedin</span></a>
                            </li>
                            <li class="rrssb-googleplus">
                                <!-- Replace href with your meta and URL information.-->
                                <a href="https://plus.google.com/share?url=https://<?php echo SITE_DOMAIN . SITE_RELATIVE_PATH . $urlHandler->project($project) ?>"
                                   class="popup">
                                    <span class="rrssb-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewbox="0 0 24 24">
                                            <path d="M21 8.29h-1.95v2.6h-2.6v1.82h2.6v2.6H21v-2.6h2.6v-1.885H21V8.29zM7.614 10.306v2.925h3.9c-.26 1.69-1.755 2.925-3.9 2.925-2.34 0-4.29-2.016-4.29-4.354s1.885-4.353 4.29-4.353c1.104 0 2.014.326 2.794 1.105l2.08-2.08c-1.3-1.17-2.924-1.883-4.874-1.883C3.65 4.586.4 7.835.4 11.8s3.25 7.212 7.214 7.212c4.224 0 6.953-2.988 6.953-7.082 0-.52-.065-1.104-.13-1.624H7.614z"></path>
                                        </svg>
                                    </span>
                                    <span class="rrssb-text">google+</span></a>
                            </li>
                        </ul>
                        <!-- Buttons end here -->

                        This project was last updated
                        <?php
                            $sinceLastUpdate = $project->getSinceLastUpdate();
                            if($sinceLastUpdate['years'] > 0)
                                echo "{$sinceLastUpdate['years']} year(s) ago";
                            elseif($sinceLastUpdate['months'] > 0)
                                echo "{$sinceLastUpdate['months']} month(s) ago";
                            elseif($sinceLastUpdate['days'] > 0)
                                echo "{$sinceLastUpdate['days']} day(s) ago";
                            elseif($sinceLastUpdate['days'] == 0)
                                echo "today";
                        ?>

                    </div>
                </div>
                <div class="column-right-small w-col w-col-4">
                    <?php if ($loggedInUser) { ?>
                        <h3 class="cse side-bar-h3"><?php _ehtml('Actions') ?></h3>

                        <?php if ($userCanEditProject) { ?>
                            <a class="sidebar-link" href="<?php echo $urlHandler->projectEdit($project) ?>">
                                <span class="green">-&nbsp;</span><?php _ehtml('Edit project') ?>
                            </a>
                            <?php if ($isOwner OR ($loggedInUser AND $loggedInUser->isSysAdmin())) { ?>
                                <a class="sidebar-link" href="<?php echo $urlHandler->projectOwnerEdit($project) ?>">
                                    <span class="green">-&nbsp;</span><?php _ehtml('Change owner') ?>
                                </a>
                            <?php } ?>
                        <?php } ?>

                        <?php if (!$userIsFollowing) { ?>
                            <a class="sidebar-link" href="#" ng-click="followProject()">
                                <span class="green">-&nbsp;</span><?php _ehtml('Follow Project') ?>
                            </a>
                        <?php } else { ?>
                            <a class="sidebar-link" href="#" ng-click="unfollowProject()">
                                <span class="green">-&nbsp;</span><?php _ehtml('Unfollow Project') ?>
                            </a>
                        <?php } ?>

                        <?php if ($userIsMember) { ?>
                            <a class="sidebar-link" href="#" ng-click="leaveProject()">
                                <span class="green">-&nbsp;</span><?php _ehtml('Leave Project') ?>
                            </a>
                        <?php } elseif ($userSentJoinRequest) { ?>
                            <a class="sidebar-link" href="#" ng-click="cancelJoinRequest()">
                                <span class="green">-&nbsp;</span><?php _ehtml('Cancel Join Request') ?>
                            </a>
                        <?php } elseif ($userCanSendJoinRequest) { ?>
                            <a class="sidebar-link" href="#" ng-click="joinProject()">
                                <span class="green">-&nbsp;</span><?php _ehtml('Join Project') ?>
                            </a>
                        <?php } ?>

                        <?php if (!$userCanEditProject) { ?>
                            <a class="sidebar-link remove" href="#" ng-click="report()">
                                <span class="green">-&nbsp;</span><?php _ehtml('Report project') ?>
                            </a>
                        <?php } ?>

                        <?php if ($isOwner OR ($loggedInUser AND $loggedInUser->isSysAdmin())) { ?>
                            <a class="sidebar-link remove" href="#" ng-click="confirmDelete()">
                                <span class="green">-&nbsp;</span><?php _ehtml('Delete project') ?>
                            </a>
                        <?php } ?>
                    <?php } ?>

                    <?php /*
                    <h3 class="cse side-bar-h3">Info</h3>
                    <p>DSI4EU has been running since February 2016 and is partnered by Nesta, The Waag Society, and
                        SUPSI</p>
                    */ ?>

                    <?php if ($project->getExternalUrl()) { ?>
                        <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                           href="<?php echo $project->getExternalUrl() ?>" target="_blank">
                            <div class="login-li long menu-li readmore-li"><?php _ehtml('Visit website') ?></div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </a>
                    <?php } ?>
                    <div ng-show="project.tags.length" ng-cloak>
                        <h3 class="cse side-bar-h3"><?php _ehtml('Tagged under') ?></h3>
                        <a href="<?php echo $urlHandler->projects() ?>?tag={{tag.id}}" class="tag"
                           ng-repeat="tag in project.tags" ng-bind="tag.name"></a>
                    </div>

                    <div ng-show="project.impactTagsA.length" ng-cloak>
                        <h3 class="cse side-bar-h3"><?php _ehtml('Areas of impact') ?></h3>
                        <a href="<?php echo $urlHandler->projects() ?>?helpTag={{tag.id}}" class="tag"
                           ng-repeat="tag in project.impactTagsA" ng-bind="tag.name"></a>
                    </div>

                    <div ng-show="project.impactTagsB.length" ng-cloak>
                        <h3 class="cse side-bar-h3"><?php _ehtml('Our focus') ?></h3>
                        <a href="<?php echo $urlHandler->projects() ?>?openTag={{tag.id}}" class="tag"
                           ng-repeat="tag in project.impactTagsB" ng-bind="tag.name"></a>
                    </div>

                    <div ng-show="project.impactTagsC.length" ng-cloak>
                        <h3 class="cse side-bar-h3"><?php _ehtml('Our technology') ?></h3>
                        <a href="<?php echo $urlHandler->projects() ?>?techTag={{tag.id}}" class="tag"
                           ng-repeat="tag in project.impactTagsC" ng-bind="tag.name"></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-posts-section" id="project-posts" ng-controller="ProjectPostController"
             <?php if (!$userCanAddPost) { ?>ng-show="project.posts.length > 0"<?php } ?>>
            <div class="page-content">
                <div class="w-row">
                    <div class="w-col w-col-8 w-col-stack">
                        <h3 class="descr-h3"><?php _ehtml('Project news') ?></h3>
                        <p class="post-intro"><?php _ehtml("See what we've been up to and join in the conversation!") ?></p>
                        <div class="page-posts">
                            <div class="card">
                                <div class="post-indicator">Latest post</div>
                                <div class="proj-post-block" ng-repeat="post in project.posts" ng-cloak>
                                    <div class="user-detail">
                                        <div class="involved-card user-post">
                                            <div class="w-row">
                                                <div class="image-col w-col w-col-3 w-col-small-3 w-col-tiny-3">
                                                    <img width="50" height="50" class="involved-profile-img"
                                                         ng-src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/{{post.user.profilePic}}">
                                                </div>
                                                <div class="w-col w-col-9 w-col-small-9 w-col-tiny-9">
                                                    <a class="card-name" href="{{post.user.url}}"
                                                       ng-bind="post.user.name"></a>
                                                    <div class="card-position" ng-bind="post.time"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <p ng-bind-html="renderHtml(post.text)" class="post-content"></p>
                                    </div>

                                    <a class="comment-block w-inline-block" href="#" ng-cloak
                                       ng-click="loadComments(post)">
                                        <div class="comment-row w-row">
                                            <div class="w-clearfix w-col w-col-1 w-col-small-1 w-col-tiny-1">
                                                <img class="comment-bubble"
                                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-chatbubble.png">
                                            </div>
                                            <div class="w-col w-col-11 w-col-small-11 w-col-tiny-11">
                                                <div class="comment-descr">
                                                    <span ng-show="post.commentsCount == 0">
                                                    <?php _ehtml('There are no comments, be the first to say something') ?>
                                                </span>
                                                    <span ng-show="post.commentsCount == 1">
                                                            <?php echo show_input(
                                                                sprintf(
                                                                    __('There is %s comment'),
                                                                    '{{post.commentsCount}}'
                                                                )
                                                            ); ?>
                                                        </span>
                                                    <span ng-show="post.commentsCount > 1">
                                                            <?php echo show_input(
                                                                sprintf(
                                                                    __('There are %s comments'),
                                                                    '{{post.commentsCount}}'
                                                                )
                                                            ); ?>
                                                        </span>
                                                    <span ng-show="loadingComments">
                                                            | <?php _ehtml('Loading comments...') ?>
                                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>

                                    <?php if ($loggedInUser) { ?>
                                        <a class="comment-block w-inline-block" href="#" ng-show="showComments"
                                           ng-cloak>
                                            <div class="w-row">
                                                <div class="w-col w-col-1 w-col-small-1 w-col-tiny-1">
                                                    <img class="comment-img involved-profile-img" width="50"
                                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $loggedInUser->getProfilePicOrDefault() ?>">
                                                </div>
                                                <div class="w-col w-col-11 w-col-small-11 w-col-tiny-11">
                                                    <div class="comment-form w-form">
                                                        <form id="email-form-2" ng-submit="submitComment(post)">
                                                            <input class="comment-sub w-input"
                                                                   id="name" maxlength="256"
                                                                   placeholder="<?php _ehtml('Write your comment') ?>"
                                                                   type="text" ng-model="post.newComment">
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    <?php } ?>

                                    <div class="user-detail">
                                        <div ng-controller="ProjectPostCommentController"
                                             ng-repeat="comment in post.comments">
                                            <div class="comment-large-row">
                                                <div class="reply-large w-row">
                                                    <div
                                                            class="image-col w-col w-col-1 w-col-small-1 w-col-tiny-tiny-stack">
                                                        <img class="involved-profile-img" width="50"
                                                             ng-src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/{{comment.user.profilePic}}">
                                                    </div>
                                                    <div class="w-col w-col-3 w-col-small-3 w-col-tiny-tiny-stack">
                                                        <a class="card-name comment-large" href="#">
                                                            {{comment.user.name}}
                                                        </a>
                                                        <a class="card-position comment-large" href="#"
                                                           ng-click="replyToComment = !replyToComment">
                                                            <?php _ehtml('Reply') ?>
                                                        </a>
                                                    </div>
                                                    <div class="w-col w-col-8 w-col-small-8 w-col-tiny-tiny-stack">
                                                        <p class="comment-large-p">
                                                            {{comment.comment}}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="sub-comment w-row" ng-repeat="reply in comment.replies">
                                                    <div class="w-clearfix w-col w-col-4">
                                                        <img class="involved-profile-img sub-reply" width="50"
                                                             ng-src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/{{reply.user.profilePic}}">
                                                        <a class="card-name comment-large sub-reply" href="#">
                                                            {{reply.user.name}}
                                                        </a>
                                                    </div>
                                                    <div class="w-col w-col-8">
                                                        <p class="comment-large-p">
                                                            {{reply.comment}}
                                                        </p>
                                                        <a class="reply-com" href="#"
                                                           ng-click="$parent.replyToComment = !$parent.replyToComment">
                                                            <?php _ehtml('Reply') ?>
                                                        </a>
                                                    </div>
                                                </div>

                                                <?php if ($loggedInUser) { ?>
                                                    <div class="w-row reply-input"
                                                         ng-show="replyToComment">
                                                        <a class="comment-block w-inline-block" href="#"
                                                           ng-show="showComments" ng-cloak>
                                                            <div class="w-row">
                                                                <div class="w-col w-col-1 w-col-small-1 w-col-tiny-1">
                                                                    <img class="comment-img involved-profile-img"
                                                                         width="50"
                                                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $loggedInUser->getProfilePicOrDefault() ?>">
                                                                </div>
                                                                <div
                                                                        class="w-col w-col-11 w-col-small-11 w-col-tiny-11">
                                                                    <div class="comment-form w-form">
                                                                        <form id="email-form-2"
                                                                              ng-submit="submitComment()">
                                                                            <input class="comment-sub w-input"
                                                                                   id="name" maxlength="256"
                                                                                   placeholder="<?php _ehtml('Add your reply') ?>"
                                                                                   type="text"
                                                                                   ng-model="comment.newReply">
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($userCanAddPost) { ?>
                        <div class="w-col w-col-4 w-col-stack">
                            <h3 class="descr-h3"><?php _ehtml('Add new post') ?></h3>
                            <p class="sidebar-intro"><?php _ehtml('Show people what you and your project have been up to') ?></p>
                            <a class="side-bar-action w-button" href="#"
                               data-ix="new-post-show"><?php _ehtml('Add new post') ?> +</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="new-post-bg bg-blur">
        <script>
            tinymce.init({
                selector: '#newPost',
                statusbar: false,
                height: 500,
                plugins: "autoresize autolink lists link preview paste textcolor colorpicker image imagetools media",
                autoresize_bottom_margin: 0,
                autoresize_max_height: 500,
                menubar: false,
                toolbar1: 'styleselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | preview',
                image_advtab: true,
                paste_data_images: false
            });
        </script>

        <div class="add-post-modal">
            <form ng-submit="addPost()" style="margin:5px">
                <textarea id="newPost" style="width:100%"
                          data-placeholder="<?php _ehtml('Please type your update here') ?>"
                          placeholder="<?php _ehtml('Please type your update here') ?>"></textarea>
                <a href="#" data-ix="hide-new-post" class="modal-save" style="right:140px">
                    <?php _ehtml('Cancel') ?>
                </a>
                <input type="submit" class="modal-save" value="<?php _ehtml('Publish post') ?>"
                       style="border-width:0;line-height:20px;font-weight:bold;"/>
            </form>
        </div>
    </div>
    </div>

<?php /*
    <div class="header-large-section">
        <div class="header-large"
             style="background-image: linear-gradient(180deg, rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)), url('<?php echo \DSI\Entity\Image::PROJECT_HEADER_URL . $project->getHeaderImageOrDefault() ?>');">
            <div class="container-wide container-wide-header-large">
                <?php if ($userCanEditProject) { ?>
                    <a class="dsi-button profile-edit w-button" style="z-index:1000"
                       href="<?php echo $urlHandler->editProject($project) ?>">Edit project</a>
                <?php } ?>
                <?php if ($isOwner OR ($loggedInUser AND $loggedInUser->isSysAdmin())) { ?>
                    <a class="dsi-button profile-edit w-button" style="z-index:1000;bottom:80px"
                       href="<?php echo $urlHandler->editProjectOwner($project) ?>">
                        Change owner</a>
                <?php } ?>
                <h1 class="header-large-h1-centre"
                    data-ix="fadeinuponload"><?php echo show_input($project->getName()) ?></h1>
                <div class="header-large-desc">
                    <a class="ext-url" data-ix="fadeinup-2" href="<?php echo $project->getUrl() ?>" target="_blank">
                        <?php echo $project->getUrl() ?>
                    </a>
                    <div>
                        <div class="expanding-social w-clearfix">
                            <?php if (isset($links['facebook'])) { ?>
                                <div class="inline sm-nu-bloxk w-clearfix">
                                    <a href="<?php echo $links['facebook'] ?>" target="_blank">
                                        <img class="sm-icon" width="40"
                                             src="<?php echo SITE_RELATIVE_PATH ?>/images/facebook-logo.png">
                                        <div class="hero-social-label">Facebook</div>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if (isset($links['twitter'])) { ?>
                                <div class="inline sm-nu-bloxk w-clearfix">
                                    <a href="<?php echo $links['twitter'] ?>" target="_blank">
                                        <img class="sm-icon" width="40"
                                             src="<?php echo SITE_RELATIVE_PATH ?>/images/twitter-logo-silhouette.png">
                                        <div class="hero-social-label">Twitter</div>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if (isset($links['github'])) { ?>
                                <div class="inline sm-nu-bloxk w-clearfix">
                                    <a href="<?php echo $links['github'] ?>" target="_blank">
                                        <img class="sm-icon" width="40"
                                             src="<?php echo SITE_RELATIVE_PATH ?>/images/social.png">
                                        <div class="hero-social-label">Github</div>
                                    </a>
                                </div>
                            <?php } ?>
                            <?php if (isset($links['googleplus'])) { ?>
                                <div class="inline sm-nu-bloxk w-clearfix">
                                    <a href="<?php echo $links['googleplus'] ?>" target="_blank">
                                        <img class="sm-icon" width="40"
                                             src="<?php echo SITE_RELATIVE_PATH ?>/images/google-plus-logo.png">
                                        <div class="hero-social-label">Google +</div>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php /*
    <div class="case-study-main">
        <div class="container-wide">
            <div class="case-study-logo" data-ix="fadeinuponload-3">
                <img class="case-study-logo-over ab-fab"
                     src="<?php echo \DSI\Entity\Image::PROJECT_LOGO_URL . $project->getLogoOrDefault() ?>">
            </div>
            <div class="case-study-single-container w-container">
                <h2 class="centered" data-ix="fadeinuponload-4">About the project</h2>
                <p class="centered"
                   data-ix="fadeinuponload-5"><?php echo show_input($project->getShortDescription()) ?></p>
                <h4 class="case-study-intro-detail centered" data-ix="fadeinuponload-5">
                    <?php echo show_input($project->getName()) ?>
                    <?php if ($region = $project->getRegion()) { ?>
                        is based in
                        <?php echo show_input($region->getName()) ?>,
                        <?php echo show_input($region->getCountry()->getName()) ?>
                        <?php if ($project->getStartDate() OR $project->getEndDate()) { ?>
                            and
                        <?php } ?>
                    <?php } ?>
                    <?php
                    if ($project->getStartDate() AND !$project->getEndDate()) {
                        if ($project->startDateHasPassed()) {
                            echo 'started in ', $project->getStartDate('M, Y');
                        } else {
                            echo 'will start in ', $project->getStartDate('M, Y');
                        }
                    } elseif (!$project->getStartDate() AND $project->getEndDate()) {
                        if ($project->endDateHasPassed()) {
                            echo 'ran until ', $project->getEndDate('M, Y');
                        } else {
                            echo 'is running until ', $project->getEndDate('M, Y');
                        }
                    } elseif ($project->getStartDate() AND $project->getEndDate()) {
                        if ($project->getStartDate('M, Y') == $project->getEndDate('M, Y')) {
                            if ($project->startDateHasPassed()) {
                                echo 'ran in ', $project->getStartDate('M, Y');
                            } elseif ($project->startDateIsInFuture()) {
                                echo 'will run in ', $project->getStartDate('M, Y');
                            }
                        } else {
                            if ($project->endDateHasPassed()) {
                                echo 'ran from ', $project->getStartDate('M, Y'),
                                ' to ', $project->getEndDate('M, Y');
                            } elseif ($project->startDateIsInFuture()) {
                                echo 'will run from ', $project->getStartDate('M, Y'),
                                ' to ', $project->getEndDate('M, Y');
                            } else {
                                echo 'is running from ', $project->getStartDate('M, Y'),
                                ' to ', $project->getEndDate('M, Y');
                            }
                        }
                    }
                    ?>
                </h4>
                <div class="centered tagged" data-ix="fadeinup-5">
                    Tagged under:
                    <span class="tag" ng-repeat="tag in project.tags" ng-bind="tag"></span>
                </div>
                <div class="impact w-row">
                    <div class="w-col w-col-4">
                        <h3 class="col-h3">Areas of society impacted</h3>
                        <p class="impact-descr">Areas of society that this project aims to support</p>
                        <div class="tag" ng-repeat="tag in project.impactTagsA" ng-bind="tag"></div>
                    </div>
                    <div class="w-col w-col-4">
                        <h3 class="col-h3">DSI Focus</h3>
                        <p class="impact-descr">Areas of DSI that this project is a part of<br/><br/></p>
                        <div class="tag" ng-repeat="tag in project.impactTagsB" ng-bind="tag"></div>
                    </div>
                    <div class="w-col w-col-4">
                        <h3 class="col-h3">Technology type</h3>
                        <p class="impact-descr">The types of technology involved with this project</p>
                        <div class="tag" ng-repeat="tag in project.impactTagsC" ng-bind="tag"></div>
                    </div>
                </div>
                <h2 class="centered" data-ix="fadeinup">Project Overview</h2>
                <p class="case-study-main-text" data-ix="fadeinup"><?php echo $project->getDescription() ?></p>
                <div class="centered url-block" data-ix="fadeinup">
                    <h2 class="centered" data-ix="fadeinup">Social Impact</h2>
                    <p class="case-study-main-text" data-ix="fadeinup"><?php echo $project->getSocialImpact() ?></p>
                    <div class="involved">
                        <h2 class="centered" data-ix="fadeinup">Who's involved</h2>
                        <div class="w-row">
                            <div class="people-col w-col w-col-6">
                                <h4 class="involved-h4">People involved</h4>
                                <?php foreach ($projectMembers AS $projectMember) {
                                    $member = $projectMember->getMember() ?>
                                    <div class="involved-card">
                                        <div class="w-row">
                                            <div class="image-col w-col w-col-3 w-col-small-3 w-col-tiny-3">
                                                <img class="involved-profile-img" width="50"
                                                     src="<?php echo \DSI\Entity\Image::PROFILE_PIC_URL . $member->getProfilePicOrDefault() ?>">
                                            </div>
                                            <div class="w-clearfix w-col w-col-9 w-col-small-9 w-col-tiny-9">
                                                <div style="overflow: hidden;"
                                                     class="card-name"><?php echo show_input($member->getFullName()) ?></div>
                                                <div
                                                    class="card-position"><?php echo show_input($member->getJobTitle()) ?></div>
                                            </div>
                                        </div>
                                        <a class="view-profile"
                                           href="<?php echo $urlHandler->profile($member->getId()) ?>">View</a>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="orgs-col w-col w-col-6">
                                <h4 class="involved-h4">Organisations involved</h4>
                                <?php foreach ($organisationProjectsObj AS $organisationProject) {
                                    $organisation = $organisationProject->getOrganisation(); ?>
                                    <div class="involved-card">
                                        <div class="w-row">
                                            <div class="w-col w-col-5 w-col-small-5 w-col-tiny-5">
                                                <img class="involved-organisation-img" style="height:50px"
                                                     src="<?php echo \DSI\Entity\Image::ORGANISATION_LOGO_URL . $organisation->getLogoOrDefaultSilver() ?>">
                                            </div>
                                            <div class="w-clearfix w-col w-col-7 w-col-small-7 w-col-tiny-7">
                                                <div style="overflow: hidden;"
                                                     class="card-name"><?php echo show_input($organisation->getName()) ?></div>
                                                <div
                                                    class="card-position"><?php echo show_input($organisation->getCountryName()) ?></div>
                                            </div>
                                        </div>
                                        <a class="view-profile"
                                           href="<?php echo $urlHandler->organisation($organisation) ?>">View</a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php /*
    <div class="w-section section-grey" id="who">
            <div class="container-wide">
                <h3 class="section-header">Who's involved</h3>
                <div class="w-row project-info">
                    <div class="w-col w-col-6 w-col-stack" id="textScroll">
                        <div id="text">
                            <div class="info-card alt">
                                <h3 class="info-h card-h">Contributors</h3>
                                <div class="project-owner">
                                    <div class="owner-link">
                                        <a class="w-inline-block inner-link"
                                           href="<?php echo SITE_RELATIVE_PATH ?>/profile/<?php echo $project->getOwner()->getId() ?>">
                                            <img class="project-creator-img" height="50"
                                                 ng-src="<?php echo \DSI\Entity\Image::PROFILE_PIC_URL . $project->getOwner()->getProfilePicOrDefault() ?>"
                                                 width="50">
                                            <div class="creator-name">
                                                <?php echo show_input($project->getOwner()->getFirstName()) ?>
                                                <?php echo show_input($project->getOwner()->getLastName()) ?>
                                            </div>
                                            <div class="project-creator-text">
                                                <?php echo show_input($project->getOwner()->getJobTitle()) ?>
                                            </div>
                                        </a>
                                        <div class="star-holder">
                                            <img class="star-full" style="opacity:1"
                                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-star-orange.png">
                                        </div>
                                    </div>

                                    <div class="project-members">
                                        <div class="owner-link project-member"
                                             ng-repeat="member in project.members"
                                             ng-cloak>
                                            <a class="w-inline-block inner-link"
                                               href="<?php echo SITE_RELATIVE_PATH ?>/profile/{{member.id}}">
                                                <img class="project-creator-img" height="50"
                                                     ng-src="<?php echo \DSI\Entity\Image::PROFILE_PIC_URL ?>{{member.profilePic}}"
                                                     width="50">
                                                <div class="creator-name">{{member.firstName}}
                                                    {{member.lastName}}
                                                </div>
                                                <div class="project-creator-text">
                                                    {{member.jobTitle}}
                                                </div>
                                            </a>
                                            <div class="star-holder">
                                                <?php if ($userCanEditProject) { ?>
                                                    <img class="star-full"
                                                         data-ix="add-star-admin"
                                                         style="opacity:1"
                                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-star-orange.png"
                                                         ng-click="member.isAdmin = !member.isAdmin; updateAdminStatus(member)"
                                                         ng-show="member.isAdmin">
                                                    <img class="star-empty"
                                                         data-ix="add-star-admin"
                                                         style="opacity:1"
                                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-star-outline-orange.png"
                                                         ng-click="member.isAdmin = !member.isAdmin; updateAdminStatus(member)"
                                                         ng-hide="member.isAdmin">
                                                    <div class="admin-added"
                                                         ng-bind="member.isAdmin ? 'Admin activated' : 'Admin removed'"></div>
                                                <?php } else { ?>
                                                    <img class="star-full" style="opacity:1"
                                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-star-orange.png"
                                                         ng-show="member.isAdmin">
                                                    <img class="star-empty" style="opacity:1"
                                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-star-outline-orange.png"
                                                         ng-hide="member.isAdmin">
                                                <?php } ?>
                                            </div>
                                            <?php if ($userCanEditProject) { ?>
                                                <div class="remove-from-list"
                                                     ng-click="removeMember(member)">-
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($userCanEditProject) { ?>
                                    <div class="join-project">
                                        <div class="add-item-block"
                                             style="float:right;margin-right:20px">
                                            <div class="add-item"
                                                 onclick="$('#addNewMember').toggle()">+
                                            </div>
                                        </div>

                                        <div id="addNewMember"
                                             style="margin-left:20px;display:none">
                                            <form id="add-member-form" name="add-member-form"
                                                  data-name="Add Member Form"
                                                  class="w-clearfix">
                                                Select new member:
                                                <select data-tags="true"
                                                        data-placeholder=""
                                                        id="Add-member" name="Add-member"
                                                        class="w-input"
                                                        multiple
                                                        style="width:200px">
                                                    <option></option>
                                                </select>
                                            </form>

                                            <div style="color:red;padding:12px 0 10px 100px;">
                                                <div style="color:orange">
                                                    <div ng-show="addProjectMember.loading">
                                                        Loading...
                                                    </div>
                                                </div>
                                                <div style="color:green">
                                                    <div ng-show="addProjectMember.success"
                                                         ng-bind="addProjectMember.success"></div>
                                                </div>
                                                <div
                                                    ng-show="addProjectMember.errors.email"
                                                    ng-bind="addProjectMember.errors.email"></div>
                                                <div
                                                    ng-show="addProjectMember.errors.member"
                                                    ng-bind="addProjectMember.errors.member"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="info-card alt"
                                 ng-show="project.memberRequests.length > 0" ng-cloak>
                                <h3 class="info-h card-h">Member Requests</h3>
                                <div class="project-owner">
                                    <div class="info-card" style="min-height: 0;">
                                        <div class="w-row contributors">
                                            <div class="w-col w-col-12 contributor-col"
                                                 ng-repeat="member in project.memberRequests">
                                                <a class="w-inline-block"
                                                   href="<?php echo SITE_RELATIVE_PATH ?>/profile/{{member.id}}">
                                                    <img class="project-creator-img" height="50"
                                                         ng-src="<?php echo \DSI\Entity\Image::PROFILE_PIC_URL ?>{{member.profilePic}}"
                                                         width="50">
                                                    <div class="creator-name">
                                                        {{member.firstName}} {{member.lastName}}
                                                    </div>
                                                    <div class="project-creator-text">
                                                        {{member.jobTitle}}
                                                    </div>
                                                </a>

                                                <div
                                                    style="float:right;margin-top:15px;margin-right:10px">
                                                    <a href="#" title="Approve Member Request"
                                                       class="add-item"
                                                       ng-click="approveRequestToJoin(member)"
                                                       style="background-color: green">+</a>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <a href="#" title="Reject Member Request"
                                                       class="add-item"
                                                       ng-click="rejectRequestToJoin(member)"
                                                       style="background-color: red">-</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-col w-col-6 w-col-stack" id="postsScroll">
                        <div class="info-card alt">
                            <h3 class="info-h card-h">Organisations involved</h3>
                            <div class="list-items">
                                <a class="w-inline-block partner-link"
                                   ng-repeat="organisation in project.organisationProjects"
                                   href="{{organisation.url}}">
                                    <div class="w-clearfix list-item">
                                        <div class="partner-title"
                                             ng-bind="organisation.name"></div>
                                        <div class="no-of-projects">
                                            <span ng-bind="organisation.projectsCount"></span>
                                            <span
                                                ng-bind="organisation.projectsCount == 1 ? 'Project' : 'Projects'"></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php if ($userCanEditProject) { ?>
                                <div class="add-item-block"
                                     ng-click="addingOrganisation = !addingOrganisation"
                                     style="float:right;margin-right:20px">
                                    <div class="add-item">+</div>
                                </div>
                                <form ng-show="addingOrganisation" ng-submit="addOrganisation()"
                                      ng-cloak>
                                    <label>
                                        Select organisation:
                                        <select data-placeholder="Select organisation"
                                                id="Add-organisation"
                                                style="width:300px">
                                            <option></option>
                                        </select>
                                        <input type="submit" value="Add"
                                               class="w-button add-skill-btn">
                                    </label>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php if (isset($userHasInvitation) AND $userHasInvitation) { ?>
                    <div class="you-have-invites" ng-hide="invitationActioned" ng-cloak>
                        <div class="notification-block-p">You have been invited to join this
                            project
                        </div>
                        <a class="w-button dsi-button notification-button accept" href="#"
                           ng-click="approveInvitationToJoin()">Accept</a>
                        <a class="w-button dsi-button notification-button decline" href="#"
                           ng-click="rejectInvitationToJoin()">Decline</a>
                    </div>
                <?php } ?>
            </div>
            * / ?>

        <?php if ($canUserRequestMembership) { ?>
            <div class="section-cta" ng-cloak>
                <div class="container-wide section-cta">
                    <div class="w-row">
                        <div class="w-col w-col-6">
                            <h3>Are you involved with this project?</h3>
                            <div>If you are involved you can request to join this project
                            </div>
                        </div>
                        <div class="w-col w-col-6">
                            <a class="w-button btn btn-join section-cta" href="#"
                               ng-click="sendRequestToJoin()"
                               ng-hide="requestToJoin.requestSent"
                               ng-bind="requestToJoin.loading ? 'Sending Request...' : 'Request to join +'"></a>
                            <button ng-show="requestToJoin.requestSent"
                                    class="w-button btn btn-join section-cta">
                                Request Sent
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    */ ?>

    <script
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ProjectController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('.rrssb-buttons').rrssb({
                // required:
                title: 'Digital Social Innovation',
                url: 'https://<?php echo SITE_DOMAIN . SITE_RELATIVE_PATH . $urlHandler->project($project) ?>',
            });
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>