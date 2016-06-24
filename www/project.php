<?php
require __DIR__ . '/header.php';
/** @var $project \DSI\Entity\Project */
/** @var $userHasInvitation bool */
/** @var $canUserRequestMembership bool */
/** @var $isOwner bool */
/** @var $loggedInUser \DSI\Entity\User */
?>
    <script src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ProjectController.js"></script>

    <div
        ng-controller="ProjectController"
        data-projectid="<?php echo $project->getId() ?>">

        <div class="w-section page-header project-header-exp">
            <div class="container-wide header project-header-exp">
                <div class="w-clearfix bread-crumbs">
                    <a class="w-inline-block breadcrumb-root" href="<?php echo \DSI\Service\URL::projects() ?>">
                        <div class="breadcrumb-link">Projects</div>
                        <div class="arrow-right"></div>
                    </a>
                    <a class="w-inline-block breadcrumb-root path" href="#">
                        <div class="arrow-bottom-left"></div>
                        <div class="arrow-top-left"></div>
                        <div class="breadcrumb-link">
                            <?php echo substr(show_input($project->getName()), 0, 35) ?>
                            <?php echo strlen($project->getName()) > 35 ? '...' : '' ?>
                        </div>
                        <div class="arrow-right"></div>
                    </a>
                </div>
                <h1 class="page-h1 light"><?php echo show_input($project->getName()) ?></h1>
                <div class="dsi4eu-stats project-header-exp">
                    <a class="project-url-link" href="<?php echo $project->getUrl() ?>"
                       target="_blank"><?php echo $project->getUrl() ?></a>
                </div>
                <img class="large-profile-img project-header-exp"
                     src="<?php echo \DSI\Entity\Image::PROJECT_LOGO_URL . $project->getLogoOrDefault() ?>">
                <?php if ($isOwner) { ?>
                    <a class="w-button dsi-button profile-edit project-header-exp"
                       href="<?php echo \DSI\Service\URL::editProject($project->getId()) ?>">
                        Edit project</a>
                <?php } ?>
            </div>
        </div>

        <div class="w-section section-grey dark">
            <div class="container-wide padding-20">
                <div class="project-description light">
                    <?php echo show_input($project->getDescription()) ?>
                </div>
                <?php if ($project->getCountryRegion() OR $project->getStartDate() OR $project->getEndDate()) { ?>
                    <p class="project-summary alt light">
                        <strong class="light strong-light"><?php echo show_input($project->getName()) ?></strong>
                        <?php if ($region = $project->getCountryRegion()) { ?>
                            is based in
                            <strong class="light strong-light">
                                <?php echo show_input($region->getName()) ?>,
                                <?php echo show_input($region->getCountry()->getName()) ?>
                            </strong>
                            <?php if ($project->getStartDate()) { ?>
                                and
                            <?php } ?>
                        <?php } ?>
                        <?php if ($project->getStartDate()) { ?>
                            ran from <strong class="light strong-light">
                                <?php echo date('M, Y', $project->getUnixStartDate()) ?>
                            </strong>
                            <?php if ($project->getEndDate()) { ?>
                                to <strong class="light strong-light">
                                    <?php echo date('M, Y', $project->getUnixEndDate()) ?>
                                </strong>
                            <?php } ?>
                        <?php } ?>
                    </p>
                <?php } ?>
                <a class="w-button project-nav" href="#tags">Tags</a>
                <a class="w-button project-nav" href="#who">Who's involved</a>
                <a class="w-button project-nav" href="#social">Social impact</a>
                <a class="w-button project-nav" href="#updates">News &amp; updates</a>
            </div>
        </div>

        <div class="w-section section-white tag-section" id="tags">
            <div class="container-wide">
                <h3 class="info-h card-h alt">This project is tagged under:</h3>
                <div class="w-clearfix tags-block" ng-cloak>
                    <div class="skill" ng-repeat="tag in project.tags">
                        <div ng-bind="tag"></div>
                        <div class="tag-label"></div>
                        <div class="tag-hole"></div>
                        <?php if ($isOwner) { ?>
                            <div class="delete" ng-click="removeTag(tag)">-</div>
                        <?php } ?>
                    </div>
                    <?php if ($isOwner) { ?>
                        <div class="add-item-block" ng-click="addingTag = !addingTag">
                            <div class="add-item">+</div>
                        </div>

                        <div class="w-form" style="float:left"
                             ng-show="addingTag">
                            <form class="w-clearfix add-skill-section"
                                  ng-submit="addTag()">
                                <select data-tags="true"
                                        data-placeholder="Add a tag"
                                        id="Add-tag"
                                        class="w-input add-language"
                                        style="width:200px;display:inline">
                                    <option></option>
                                </select>
                                <input type="submit" value="Add" class="w-button add-skill-btn">
                            </form>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

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
                                        <div class="owner-link project-member" ng-repeat="member in project.members"
                                             ng-cloak>
                                            <a class="w-inline-block inner-link"
                                               href="<?php echo SITE_RELATIVE_PATH ?>/profile/{{member.id}}">
                                                <img class="project-creator-img" height="50"
                                                     ng-src="<?php echo \DSI\Entity\Image::PROFILE_PIC_URL ?>{{member.profilePic}}"
                                                     width="50">
                                                <div class="creator-name">{{member.firstName}} {{member.lastName}}</div>
                                                <div class="project-creator-text">{{member.jobTitle}}</div>
                                            </a>
                                            <div class="star-holder">
                                                <?php if ($isOwner) { ?>
                                                    <img class="star-full" data-ix="add-star-admin" style="opacity:1"
                                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-star-orange.png"
                                                         ng-click="member.isAdmin = !member.isAdmin; updateAdminStatus(member)"
                                                         ng-show="member.isAdmin">
                                                    <img class="star-empty" data-ix="add-star-admin" style="opacity:1"
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
                                            <?php if ($isOwner) { ?>
                                                <div class="remove-from-list" ng-click="removeMember(member)">-</div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($isOwner) { ?>
                                    <div class="join-project">
                                        <div class="add-item-block" style="float:right;margin-right:20px">
                                            <div class="add-item" onclick="$('#addNewMember').toggle()">+</div>
                                        </div>

                                        <div id="addNewMember" style="margin-left:20px;display:none">
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
                                                    <div ng-show="addProjectMember.loading">Loading...</div>
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

                            <div class="info-card alt" ng-show="project.memberRequests.length > 0" ng-cloak>
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
                                                    <div class="creator-name">{{member.firstName}} {{member.lastName}}
                                                    </div>
                                                    <div class="project-creator-text">{{member.jobTitle}}</div>
                                                </a>

                                                <div style="float:right;margin-top:15px;margin-right:10px">
                                                    <a href="#" title="Approve Member Request" class="add-item"
                                                       ng-click="approveRequestToJoin(member)"
                                                       style="background-color: green">+</a>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <a href="#" title="Reject Member Request" class="add-item"
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
                                        <div class="partner-title" ng-bind="organisation.name"></div>
                                        <div class="no-of-projects">
                                            <span ng-bind="organisation.projectsCount"></span>
                                            <span
                                                ng-bind="organisation.projectsCount == 1 ? 'Project' : 'Projects'"></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php if ($isOwner) { ?>
                                <div class="add-item-block"
                                     ng-click="addingOrganisation = !addingOrganisation"
                                     style="float:right;margin-right:20px">
                                    <div class="add-item">+</div>
                                </div>
                                <form ng-show="addingOrganisation" ng-submit="addOrganisation()" ng-cloak>
                                    <label>
                                        Select organisation:
                                        <select data-placeholder="Select organisation"
                                                id="Add-organisation"
                                                style="width:300px">
                                            <option></option>
                                        </select>
                                        <input type="submit" value="Add" class="w-button add-skill-btn">
                                    </label>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php if (isset($userHasInvitation) AND $userHasInvitation) { ?>
                    <div class="you-have-invites" ng-hide="invitationActioned" ng-cloak>
                        <div class="notification-block-p">You have been invited to join this project
                        </div>
                        <a class="w-button dsi-button notification-button accept" href="#"
                           ng-click="approveInvitationToJoin()">Accept</a>
                        <a class="w-button dsi-button notification-button decline" href="#"
                           ng-click="rejectInvitationToJoin()">Decline</a>
                    </div>
                <?php } ?>
            </div>

            <?php if ($canUserRequestMembership) { ?>
                <div class="section-cta" ng-cloak>
                    <div class="container-wide section-cta">
                        <div class="w-row">
                            <div class="w-col w-col-6">
                                <h3>Are you involved with this project?</h3>
                                <div>If you are involved you can request to join this project</div>
                            </div>
                            <div class="w-col w-col-6">
                                <a class="w-button btn btn-join section-cta" href="#"
                                   ng-click="sendRequestToJoin()"
                                   ng-hide="requestToJoin.requestSent"
                                   ng-bind="requestToJoin.loading ? 'Sending Request...' : 'Request to join +'"></a>
                                <button ng-show="requestToJoin.requestSent"
                                        class="w-button btn btn-join">
                                    Request Sent
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="w-section section-white" id="social">
            <div class="container-wide">
                <h3 class="info-h card-h section-header">Social impact</h3>
                <div class="w-row">
                    <div class="w-col w-col-4">
                        <div class="impact-block">
                            <h4 class="impact-h4">Areas of society impacted</h4>
                            <div class="w-clearfix tags-block impact">
                                <div class="skill" ng-repeat="tag in project.impactTagsA">
                                    <?php if ($isOwner) { ?>
                                        <div class="delete" ng-click="removeImpactTagA(tag)">-</div>
                                    <?php } ?>
                                    <div ng-bind="tag"></div>
                                </div>
                                <?php if ($isOwner) { ?>
                                    <div class="add-item-block"
                                         ng-click="addingImpactTagA = !addingImpactTagA">
                                        <div class="add-item">+</div>
                                    </div>
                                    <div class="w-form" style="float:left"
                                         ng-show="addingImpactTagA">
                                        <form class="w-clearfix add-skill-section"
                                              ng-submit="addImpactTagA()">
                                            <select data-tags="true"
                                                    data-placeholder="Type your skill"
                                                    id="Add-impact-tag-a"
                                                    class="w-input add-language"
                                                    style="width:200px;display:inline">
                                                <option></option>
                                            </select>
                                            <input type="submit" value="Add"
                                                   class="w-button add-skill-btn">
                                        </form>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="w-col w-col-4">
                        <div class="impact-block">
                            <h4 class="impact-h4">Technology focus</h4>
                            <div class="w-clearfix tags-block impact">
                                <div class="skill" ng-repeat="tag in project.impactTagsB">
                                    <?php if ($isOwner) { ?>
                                        <div class="delete" ng-click="removeImpactTagB(tag)">-</div>
                                    <?php } ?>
                                    <div ng-bind="tag"></div>
                                </div>
                                <?php if ($isOwner) { ?>
                                    <div class="add-item-block"
                                         ng-click="addingImpactTagB = !addingImpactTagB">
                                        <div class="add-item">+</div>
                                    </div>
                                    <div class="w-form" style="float:left"
                                         ng-show="addingImpactTagB">
                                        <form class="w-clearfix add-skill-section"
                                              ng-submit="addImpactTagB()">
                                            <select data-tags="true"
                                                    data-placeholder="Type your skill"
                                                    id="Add-impact-tag-b"
                                                    class="w-input add-language"
                                                    style="width:200px;display:inline">
                                                <option></option>
                                            </select>
                                            <input type="submit" value="Add"
                                                   class="w-button add-skill-btn">
                                        </form>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="w-col w-col-4">
                        <div class="impact-block last">
                            <h4 class="impact-h4">Technology method</h4>
                            <div class="w-clearfix tags-block impact">
                                <div class="skill" ng-repeat="tag in project.impactTagsC">
                                    <?php if ($isOwner) { ?>
                                        <div class="delete" ng-click="removeImpactTagC(tag)">-</div>
                                    <?php } ?>
                                    <div ng-bind="tag"></div>
                                </div>
                                <?php if ($isOwner) { ?>
                                    <div class="add-item-block"
                                         ng-click="addingImpactTagC = !addingImpactTagC">
                                        <div class="add-item">+</div>
                                    </div>
                                    <div class="w-form" style="float:left"
                                         ng-show="addingImpactTagC">
                                        <form class="w-clearfix add-skill-section"
                                              ng-submit="addImpactTagC()">
                                            <select data-tags="true"
                                                    data-placeholder="Type your skill"
                                                    id="Add-impact-tag-c"
                                                    class="w-input add-language"
                                                    style="width:200px;display:inline">
                                                <option></option>
                                            </select>
                                            <input type="submit" value="Add"
                                                   class="w-button add-skill-btn">
                                        </form>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="w-section section-grey dark" id="updates">
            <div class="container-wide">
                <div class="w-row project-info">
                    <div class="w-col w-col-3" id="textScroll">
                        <div id="text"></div>
                    </div>
                    <div class="w-col w-col-6" id="postsScroll">
                        <div id="posts">
                            <div class="info-card">
                                <?php if ($loggedInUser AND $isOwner) { ?>
                                    <div class="add-post">
                                        <div class="w-clearfix post-author new-post">
                                            <img
                                                src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $loggedInUser->getProfilePicOrDefault() ?>"
                                                width="40" height="40" class="post-author-img post">
                                            <div class="profile-label">Do you have something to share?</div>
                                            <a href="#" data-ix="new-post-show" class="create-new-post">Add new
                                                post <span
                                                    class="add-post-plus">+</span></a>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div ng-controller="ProjectPostController" ng-repeat="post in project.posts" ng-cloak>
                                    <div class="w-clearfix" ng-class="{'current-status' : $index == 0}">

                                        <h3 ng-show="$index == 0" class="status-h3">Latest post</h3>
                                        <h3 ng-show="$index == 1" class="info-h card-h">Previous posts</h3>

                                        <div class="post-author" ng-class="{'latest' : $index == 0}">
                                            <img width="40" height="40" class="post-author-img"
                                                 ng-src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/{{post.user.profilePic}}">
                                            <div class="post-author-detail" ng-class="{'latest' : $index == 0}"
                                                 ng-bind="post.user.name"></div>
                                            <div class="posted-on" ng-class="{'latest' : $index == 0}"
                                                 ng-bind="post.time"></div>
                                        </div>
                                        <div class="news-content"
                                             ng-bind-html="renderHtml(post.text)"></div>
                                    </div>
                                    <div class="w-clearfix comment-count" ng-cloak>
                                        <a href="#" class="w-inline-block w-clearfix comment-toggle">
                                            <img width="256" class="comment-bubble"
                                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-chatbubble.png">
                                            <div class="comment-indicator" ng-click="loadComments()">
                                                <span ng-show="post.commentsCount == 0">There are no comments, be the first to say something</span>
                                                <span ng-show="post.commentsCount == 1">There is {{post.commentsCount}} comment</span>
                                                <span ng-show="post.commentsCount > 1">There are {{post.commentsCount}} comments</span>
                                                <span ng-show="loadingComments">| Loading comments...</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="post-comments" ng-show="showComments" ng-cloak="">
                                        <div class="comment">
                                            <?php if ($loggedInUser) { ?>
                                                <div class="w-row">
                                                    <div class="w-col w-col-1 w-clearfix">
                                                        <img class="commentor-img"
                                                             src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $loggedInUser->getProfilePicOrDefault() ?>">
                                                    </div>
                                                    <div class="w-col w-col-11">
                                                        <div class="post-comment">
                                                            <div class="w-form">
                                                                <form ng-submit="submitComment()">
                                                                    <input type="text"
                                                                           placeholder="Write your comment"
                                                                           class="w-input add-comment"
                                                                           ng-model="post.newComment">
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div ng-controller="ProjectPostCommentController"
                                                 ng-repeat="comment in post.comments">
                                                <div class="w-row comment-original">
                                                    <div class="w-col w-col-4 w-clearfix">
                                                        <img class="commentor-img"
                                                             ng-src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/{{comment.user.profilePic}}">
                                                        <div class="commentor-name">{{comment.user.name}}</div>
                                                        <br/>
                                                        <a href="#" ng-click="replyToComment = !replyToComment"
                                                           class="reply">Reply</a>
                                                    </div>
                                                    <div class="w-col w-col-8">
                                                        <div class="post-comment comment-original-post">
                                                            {{comment.comment}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="w-row reply-cols"
                                                         ng-repeat="reply in comment.replies">
                                                        <div class="w-col w-col-3 w-clearfix reply-col-1">
                                                            <img class="commentor-img commentor-reply-img"
                                                                 ng-src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/{{reply.user.profilePic}}">
                                                            <div class="commentor-name commentor-reply">
                                                                {{reply.user.name}}
                                                            </div>
                                                        </div>
                                                        <div class="w-col w-col-9">
                                                            <div class="post-comment reply-comment">
                                                                {{reply.comment}}
                                                            </div>
                                                            <a href="#"
                                                               ng-click="$parent.replyToComment = !$parent.replyToComment"
                                                               class="reply">Reply</a>
                                                        </div>
                                                    </div>
                                                    <?php if ($loggedInUser) { ?>
                                                        <div class="w-row reply-input" ng-show="replyToComment">
                                                            <div class="w-col w-col-1 w-clearfix">
                                                                <img class="commentor-img commentor-reply-img"
                                                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $loggedInUser->getProfilePicOrDefault() ?>">
                                                            </div>
                                                            <div class="w-col w-col-11">
                                                                <div class="w-form">
                                                                    <form ng-submit="submitComment()">
                                                                        <input type="text"
                                                                               placeholder="Add your reply"
                                                                               class="w-input add-comment"
                                                                               ng-model="comment.newReply">
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="new-post-bg bg-blur">
            <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
            <script>
                tinymce.init({
                    selector: '#newPost',
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
                <form ng-submit="addPost()">
                    <textarea id="newPost" style="height:100%">Please type your update here...</textarea>
                    <a href="#" data-ix="hide-new-post" class="modal-save" style="right:140px">Cancel</a>
                    <input type="submit" class="modal-save" value="Publish post"
                           style="border-width:0;line-height:20px;font-weight:bold;"/>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $('.project-members')
                .on('mouseenter', '.project-member', function () {
                    $('.remove-from-list', $(this)).show();
                })
                .on('mouseleave', '.project-member', function () {
                    $('.remove-from-list', $(this)).hide();
                })
        });
    </script>
<?php require __DIR__ . '/footer.php' ?>