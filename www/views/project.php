<?php
require __DIR__ . '/header.php';
/** @var $project \DSI\Entity\Project */
/** @var $userHasInvitation bool */
/** @var $canUserRequestMembership bool */
/** @var $userCanEditProject bool */
/** @var $isOwner bool */
/** @var $loggedInUser \DSI\Entity\User */
/** @var $projectMembers \DSI\Entity\ProjectMember[] */
/** @var $organisationProjectsObj \DSI\Entity\OrganisationProject[] */
/** @var $urlHandler \DSI\Service\URL */
?>
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
                    <div><?php echo $project->getDescription() ?></div>
                    <h3 class="descr-h3">Social Impact</h3>
                    <div><?php echo $project->getSocialImpact() ?></div>
                    <div class="involved">
                        <h3 class="descr-h3 space">Who's involved</h3>
                        <h4 class="involved-h4">People</h4>
                        <?php foreach ($projectMembers AS $projectMember) {
                            $member = $projectMember->getMember();
                            if (trim($member->getFullName()) == '') continue; ?>
                            <a class="involved-card w-inline-block"
                               href="<?php echo $urlHandler->profile($member) ?>">
                                <div class="involved-card">
                                    <div class="w-row">
                                        <div class="image-col w-col w-col-3 w-col-small-3 w-col-tiny-3">
                                            <img class="involved-profile-img"
                                                 src="<?php echo \DSI\Entity\Image::PROFILE_PIC_URL . $member->getProfilePicOrDefault() ?>"
                                                 width="50">
                                        </div>
                                        <div class="w-clearfix w-col w-col-9 w-col-small-9 w-col-tiny-9">
                                            <div
                                                class="card-name"><?php echo show_input($member->getFullName()) ?></div>
                                            <div
                                                class="card-position"><?php echo show_input($member->getJobTitle()) ?></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php } ?>
                        <h4 class="involved-h4 orgs-h">Organisations</h4>
                        <?php foreach ($organisationProjectsObj AS $organisationProject) {
                            $organisation = $organisationProject->getOrganisation(); ?>
                            <a class="sidebar-link" href="<?php echo $urlHandler->organisation($organisation) ?>">
                                <span class="green">-&nbsp;</span><?php echo show_input($organisation->getName()) ?></a>
                        <?php } ?>
                    </div>
                </div>
                <div class="column-right-small w-col w-col-4">
                    <?php if ($userCanEditProject) { ?>
                        <h3 class="cse side-bar-h3">Actions</h3>
                        <a class="sidebar-link" href="<?php echo $urlHandler->projectEdit($project) ?>">
                            <span class="green">-&nbsp;</span>Edit project
                        </a>
                        <?php if ($isOwner OR ($loggedInUser AND $loggedInUser->isSysAdmin())) { ?>
                            <a class="sidebar-link" href="<?php echo $urlHandler->projectOwnerEdit($project) ?>">
                                <span class="green">-&nbsp;</span>Change owner
                            </a>
                            <a class="sidebar-link remove" href="#" ng-click="confirmDelete()">
                                <span class="green">-&nbsp;</span>Delete project
                            </a>
                        <?php } ?>
                    <?php } ?>
                    <?php /*
                    <h3 class="cse side-bar-h3">Info</h3>
                    <p>DSI4EU has been running since February 2016 and is partnered by Nesta, The Waag Society, and
                        SUPSI</p>
                    */ ?>
                    <?php if ($project->getUrl()) { ?>
                        <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                           href="<?php echo $project->getUrl() ?>">
                            <div class="login-li long menu-li readmore-li">Visit website</div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </a>
                    <?php } ?>
                    <h3 class="cse side-bar-h3">Tagged under</h3>
                    <div class="tag" ng-repeat="tag in project.tags" ng-bind="tag"></div>
                    <h3 class="cse side-bar-h3">Areas of society impacted</h3>
                    <p>Areas of society that this project aims to support</p>
                    <div class="tag" ng-repeat="tag in project.impactTagsA" ng-bind="tag"></div>
                    <h3 class="cse side-bar-h3">DSI Focus</h3>
                    <p>Areas of DSI that this project is a part of</p>
                    <div class="tag" ng-repeat="tag in project.impactTagsB" ng-bind="tag"></div>
                    <h3 class="cse side-bar-h3">Technology type</h3>
                    <p>The types of technology involved with this project</p>
                    <div class="tag" ng-repeat="tag in project.impactTagsC" ng-bind="tag"></div>
                </div>
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

    <div class="w-section section-grey" id="who">
        <?php /*
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
<?php /*
        <div class="w-section section-grey dark" id="updates">
            <div class="container-wide">
                <div class="w-row project-info">
                    <div class="w-col w-col-3" id="textScroll">
                        <div id="text"></div>
                    </div>
                    <div class="w-col w-col-6" id="postsScroll">
                        <div id="posts">
                            <div class="info-card">
                                <?php if ($loggedInUser AND $userCanEditProject) { ?>
                                    <div class="add-post">
                                        <div class="w-clearfix post-author new-post">
                                            <img
                                                src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $loggedInUser->getProfilePicOrDefault() ?>"
                                                width="40" height="40"
                                                class="post-author-img post">
                                            <div class="profile-label">Do you have something to
                                                share?
                                            </div>
                                            <?php if ($userCanEditProject) { ?>
                                                <a href="#" data-ix="new-post-show"
                                                   class="create-new-post">
                                                    Add new post <span class="add-post-plus">+</span>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div ng-controller="ProjectPostController"
                                     ng-repeat="post in project.posts" ng-cloak>
                                    <div class="w-clearfix"
                                         ng-class="{'current-status' : $index == 0}">

                                        <h3 ng-show="$index == 0" class="status-h3">Latest
                                            post</h3>
                                        <h3 ng-show="$index == 1" class="info-h card-h">Previous
                                            posts</h3>

                                        <div class="post-author"
                                             ng-class="{'latest' : $index == 0}">
                                            <img width="40" height="40" class="post-author-img"
                                                 ng-src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/{{post.user.profilePic}}">
                                            <div class="post-author-detail"
                                                 ng-class="{'latest' : $index == 0}"
                                                 ng-bind="post.user.name"></div>
                                            <div class="posted-on"
                                                 ng-class="{'latest' : $index == 0}"
                                                 ng-bind="post.time"></div>
                                        </div>
                                        <div class="news-content"
                                             ng-bind-html="renderHtml(post.text)"></div>
                                    </div>
                                    <div class="w-clearfix comment-count" ng-cloak>
                                        <a href="#"
                                           class="w-inline-block w-clearfix comment-toggle">
                                            <img width="256" class="comment-bubble"
                                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-chatbubble.png">
                                            <div class="comment-indicator"
                                                 ng-click="loadComments()">
                                                <span ng-show="post.commentsCount == 0">There are no comments, be the first to say something</span>
                                                <span ng-show="post.commentsCount == 1">There is {{post.commentsCount}} comment</span>
                                                <span ng-show="post.commentsCount > 1">There are {{post.commentsCount}} comments</span>
                                                <span ng-show="loadingComments">| Loading comments...</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="post-comments" ng-show="showComments"
                                         ng-cloak="">
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
                                                                <form
                                                                    ng-submit="submitComment()">
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
                                                        <div class="commentor-name">
                                                            {{comment.user.name}}
                                                        </div>
                                                        <br/>
                                                        <a href="#"
                                                           ng-click="replyToComment = !replyToComment"
                                                           class="reply">Reply</a>
                                                    </div>
                                                    <div class="w-col w-col-8">
                                                        <div
                                                            class="post-comment comment-original-post">
                                                            {{comment.comment}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="w-row reply-cols"
                                                         ng-repeat="reply in comment.replies">
                                                        <div
                                                            class="w-col w-col-3 w-clearfix reply-col-1">
                                                            <img
                                                                class="commentor-img commentor-reply-img"
                                                                ng-src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/{{reply.user.profilePic}}">
                                                            <div
                                                                class="commentor-name commentor-reply">
                                                                {{reply.user.name}}
                                                            </div>
                                                        </div>
                                                        <div class="w-col w-col-9">
                                                            <div
                                                                class="post-comment reply-comment">
                                                                {{reply.comment}}
                                                            </div>
                                                            <a href="#"
                                                               ng-click="$parent.replyToComment = !$parent.replyToComment"
                                                               class="reply">Reply</a>
                                                        </div>
                                                    </div>
                                                    <?php if ($loggedInUser) { ?>
                                                        <div class="w-row reply-input"
                                                             ng-show="replyToComment">
                                                            <div
                                                                class="w-col w-col-1 w-clearfix">
                                                                <img
                                                                    class="commentor-img commentor-reply-img"
                                                                    src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $loggedInUser->getProfilePicOrDefault() ?>">
                                                            </div>
                                                            <div class="w-col w-col-11">
                                                                <div class="w-form">
                                                                    <form
                                                                        ng-submit="submitComment()">
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
                <form ng-submit="addPost()">
                    <textarea id="newPost" style="height:100%">Please type your update here...</textarea>
                    <a href="#" data-ix="hide-new-post" class="modal-save" style="right:140px">Cancel</a>
                    <input type="submit" class="modal-save" value="Publish post"
                           style="border-width:0;line-height:20px;font-weight:bold;"/>
                </form>
            </div>
        </div>
        */ ?>

    <script
        src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ProjectController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

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