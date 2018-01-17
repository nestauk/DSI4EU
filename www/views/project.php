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
    <div ng-controller="ProjectController"
         data-jsonurl="<?php echo $urlHandler->projectJson($project) ?>" class="page-project">

        <div class="case-study-intro data-vis-intro">
            <div class="header-content">
                <div class="case-study-img-bg-blur"></div>
                <div class="container-wide">
                    <div class="w-row">
                        <div class="w-col w-col-6 w-col-stack">
                            <h1 class="case-study-h1 data-vis-h1"><?php echo show_input($project->getName()) ?></h1>
                            <?php if ($project->isWaitingApproval()) { ?>
                                <div style="color:red;font-weight:bold">
                                    This project is waiting approval. Only the project owner can see this page.
                                </div>
                            <?php } ?>
                            <h3 class="home-hero-h3"><?php echo show_input($project->getShortDescription()) ?></h3>
                        </div>
                        <div class="w-col w-col-6 w-col-stack">
                            <div class="html-embed-2 w-embed w-iframe">
                                <style>
                                    .embed-container {
                                        position: relative;
                                        padding-bottom: 56.25%;
                                        height: 0;
                                        overflow: hidden;
                                        max-width: 100%;
                                    }

                                    .embed-container iframe, .embed-container object, .embed-container embed {
                                        position: absolute;
                                        top: 0;
                                        left: 0;
                                        width: 100%;
                                        height: 100%;
                                    }
                                </style>
                                <div class="embed-container">
                                    <iframe src="<?= \DSI\Service\DataVis::getUrl() ?>#/network?l=0&e=1&prj=<?= $project->getId() ?>"
                                            style="border:0"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                            <img src="<?php echo \DSI\Entity\Image::PROFILE_PIC_URL . $member->getProfilePicOrDefault() ?>"
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
                        <?php
                        $socialShare = new \DSI\Service\SocialShare('https://' . SITE_DOMAIN . SITE_RELATIVE_PATH . $urlHandler->project($project));
                        $socialShare->renderHtml();

                        $sinceLastUpdate = $project->getSinceLastUpdate();
                        if ($sinceLastUpdate['years'] == 1)
                            _ehtml("This project was last updated 1 year ago");
                        elseif ($sinceLastUpdate['years'] > 1)
                            echo show_input(sprintf(
                                _html("This project was last updated %s years ago"),
                                $sinceLastUpdate['years']
                            ));
                        elseif ($sinceLastUpdate['months'] == 1)
                            _ehtml("This project was last updated 1 month ago");
                        elseif ($sinceLastUpdate['months'] > 1)
                            echo show_input(sprintf(
                                _html("This project was last updated %s months ago"),
                                $sinceLastUpdate['months']
                            ));
                        elseif ($sinceLastUpdate['days'] == 1)
                            _ehtml("This project was last updated yesterday");
                        elseif ($sinceLastUpdate['days'] > 1)
                            echo show_input(sprintf(
                                _html("This project was last updated %s days ago"),
                                $sinceLastUpdate['days']
                            ));
                        else
                            _ehtml("This project was last updated today");
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
                            <a class="sidebar-link" href="<?php echo $urlHandler->projectMembers($project) ?>">
                                <span class="green">-&nbsp;</span><?php _ehtml('Manage members') ?>
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
                                <span class="green">- </span><?php _ehtml('Delete project') ?>
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

    <script>
      var translate = new Translate();
      <?php foreach([
          'Delete project',
          'Are you sure you want to delete this project?',
          'Deleted',
          'The project has been deleted.',
          'Report this project',
          'Please tell us why you are reporting this project',
          'Reason for reporting',
          'Please write your reason for reporting.',
          'Reported',
          'Thank you for your report',
          'Cancel Join Request',
          'Are you sure you want to cancel the join request?',
          'Request Cancelled',
          'Your request has been cancelled.',
          'Join Project',
          'Are you sure you want to join this project?',
          'Your join request has been sent.',
          'Leave Project',
          'Are you sure you want to leave this project?',
          'Success',
          'You have left this project',
          'Follow Project',
          'Are you sure you want to follow this project?',
          'You are now following this project.',
          'Unfollow Project',
          'Are you sure you want to unfollow this project?',
          "You won't receive any more news from this project.",
      ] AS $translate) { ?>
      translate.set('<?php echo show_input($translate)?>', '<?php _ehtml($translate)?>');
      <?php } ?>
    </script>
    <script src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ProjectController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>