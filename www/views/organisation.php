<?php
require __DIR__ . '/header.php';
/** @var $organisation \DSI\Entity\Organisation */
/** @var $loggedInUser \DSI\Entity\User */
/** @var $canUserRequestMembership bool */
/** @var $isOwner bool */
/** @var $userIsMember bool */
/** @var $userSentJoinRequest bool */
/** @var $userCanSendJoinRequest bool */
/** @var $userCanEditOrganisation bool */
/** @var $userIsFollowing bool */
/** @var $organisationTypes \DSI\Entity\OrganisationType[] */
/** @var $organisationSizes \DSI\Entity\OrganisationSize[] */
/** @var $organisationProjects \DSI\Entity\OrganisationProject[] */
/** @var $organisationMembers \DSI\Entity\User[] */
/** @var $partnerOrganisations \DSI\Entity\Organisation[] */
/** @var $links string[] */
/** @var $tags \DSI\Entity\TagForOrganisations[] */
/** @var $networkTags \DSI\Entity\NetworkTag[] */

if (!isset($urlHandler))
    $urlHandler = new Services\URL();

?>
    <div ng-controller="OrganisationController"
         data-organisationid="<?php echo $organisation->getId() ?>" class="page-organisation">

        <div class="case-study-intro org">
            <div class="header-content org">
                <div class="w-row">
                    <div class="w-col w-col-6 w-col-stack">
                        <h1 class="case-study-h1 org"><?php echo show_input($organisation->getName()) ?></h1>
                        <?php if ($organisation->isWaitingApproval()) { ?>
                            <div style="color:red;font-weight:bold">
                                This organisation is waiting approval. Only the organisation owner can see this page.
                            </div>
                        <?php } ?>
                        <h3 class="home-hero-h3 org"><?php echo show_input($organisation->getShortDescription()) ?></h3>
                    </div>
                    <div class="column-3 w-col w-col-6 w-col-stack">
                        <div class="w-embed w-iframe">
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
                                <iframe src="<?= \DSI\Service\DataVis::getUrl() ?>#/network?l=0&e=1&org=<?= $organisation->getId() ?>"
                                        style="border:0"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content">
            <div class="w-row">
                <div class="content-left w-col w-col-8">
                    <div class="intro org"><?php echo show_input($organisation->getShortDescription()) ?></div>
                    <div class="intro org"><?php echo $organisation->getDescription() ?></div>
                    <div class="involved">
                        <h3 class="descr-h3 space">
                            <?php echo show_input(sprintf(
                                __('%s is involved with'),
                                $organisation->getName()
                            )) ?>:
                        </h3>
                        <div class="w-row">
                            <div class="w-col w-col-6">
                                <h4 class="involved-h4 orgs-h"><?php _ehtml('Projects') ?></h4>
                                <?php foreach ($organisationProjects AS $organisationProject) { ?>
                                    <?php $project = $organisationProject->getProject() ?>
                                    <a class="sidebar-link" href="<?php echo $urlHandler->project($project) ?>">
                                        <span class="green">- </span>
                                        <?php echo show_input($project->getName()) ?>
                                    </a>
                                <?php } ?>

                                <h4 class="involved-h4 orgs-h"><?php _ehtml('Members') ?></h4>
                                <?php foreach ($organisationMembers AS $member) { ?>
                                    <a class="sidebar-link" href="<?php echo $urlHandler->profile($member) ?>">
                                        <span class="green">- </span>
                                        <?php echo show_input($member->getFullName()) ?>
                                    </a>
                                <?php } ?>
                            </div>
                            <div class="w-col w-col-6">
                                <h4 class="involved-h4 orgs-h"><?php _ehtml('Organisations') ?></h4>
                                <?php foreach ($partnerOrganisations AS $org) { ?>
                                    <a class="sidebar-link" href="<?php echo $urlHandler->organisation($org) ?>">
                                        <span class="green">- </span><?php echo show_input($org->getName()) ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="w-row">
                            <div class="w-col w-col-12">
                                <?php
                                $socialShare = new \DSI\Service\SocialShare('https://' . SITE_DOMAIN . SITE_RELATIVE_PATH . $urlHandler->organisation($organisation));
                                $socialShare->renderHtml();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column-right-small w-col w-col-4">
                    <?php if ($loggedInUser) { ?>
                        <h3 class="cse side-bar-h3">Actions</h3>
                        <?php if ($userCanEditOrganisation) { ?>
                            <a class="sidebar-link" href="<?php echo $urlHandler->organisationEdit($organisation) ?>">
                                <span class="green">-&nbsp;</span>
                                <?php _ehtml('Edit organisation') ?>
                            </a>
                            <?php if ($isOwner OR ($loggedInUser AND $loggedInUser->isSysAdmin())) { ?>
                                <a class="sidebar-link"
                                   href="<?php echo $urlHandler->organisationOwnerEdit($organisation) ?>">
                                    <span class="green">-&nbsp;</span>
                                    <?php _ehtml('Change owner') ?>
                                </a>
                            <?php } ?>
                        <?php } ?>

                        <?php if (!$userIsFollowing) { ?>
                            <a class="sidebar-link" href="#" ng-click="followOrganisation()">
                                <span class="green">-&nbsp;</span>
                                <?php _ehtml('Follow Organisation') ?>
                            </a>
                        <?php } else { ?>
                            <a class="sidebar-link" href="#" ng-click="unfollowOrganisation()">
                                <span class="green">-&nbsp;</span>
                                <?php _ehtml('Unfollow Organisation') ?>
                            </a>
                        <?php } ?>

                        <?php if ($userIsMember) { ?>
                            <a class="sidebar-link" href="#" ng-click="leaveOrganisation()">
                                <span class="green">-&nbsp;</span>
                                <?php _ehtml('Leave Organisation') ?>
                            </a>
                        <?php } elseif ($userSentJoinRequest) { ?>
                            <a class="sidebar-link" href="#" ng-click="cancelJoinRequest()">
                                <span class="green">-&nbsp;</span>
                                <?php _ehtml('Cancel Join Request') ?>
                            </a>
                        <?php } elseif ($userCanSendJoinRequest) { ?>
                            <a class="sidebar-link" href="#" ng-click="joinOrganisation()">
                                <span class="green">-&nbsp;</span>
                                <?php _ehtml('Join Organisation') ?>
                            </a>
                        <?php } ?>

                        <?php if ($isOwner OR ($loggedInUser AND $loggedInUser->isSysAdmin())) { ?>
                            <a class="sidebar-link remove" href="#" ng-click="confirmDelete()">
                                <span class="green">-&nbsp;</span>
                                <?php _ehtml('Delete organisation') ?>
                            </a>
                        <?php } ?>

                        <?php if (!$userCanEditOrganisation) { ?>
                            <a class="sidebar-link remove" href="#" ng-click="report()">
                                <span class="green">-&nbsp;</span>
                                <?php _ehtml('Report organisation') ?>
                            </a>
                        <?php } ?>
                    <?php } ?>

                    <h3 class="cse side-bar-h3"><?php _ehtml('Info') ?></h3>
                    <p>
                        <?php echo show_input($organisation->getName()) ?>
                        <?php if ($organisation->getCountry()) { ?>
                            is based in <?php echo $organisation->getCountryName() ?>
                        <?php } ?>
                        <?php if ($organisation->getCountry() AND $organisation->getStartDate()) { ?>
                            and
                        <?php } ?>
                        <?php if ($organisation->getStartDate()) { ?>
                            has been running since <?php echo date('M Y', $organisation->getUnixStartDate()) ?>
                        <?php } ?>
                    </p>
                    <?php if ($organisation->getExternalUrl()) { ?>
                        <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                           href="<?php echo $organisation->getExternalUrl() ?>" target="_blank">
                            <div class="login-li long menu-li readmore-li">
                                <?php _ehtml('Visit website') ?>
                            </div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </a>
                    <?php } ?>

                    <?php if ($tags) { ?>
                        <h3 class="cse side-bar-h3"><?php _ehtml('Tagged under') ?></h3>
                        <?php foreach ($tags AS $tag) { ?>
                            <a href="<?php echo $urlHandler->organisations() ?>?tag=<?php echo $tag->getId() ?>"
                               class="tag">
                                <?php echo show_input($tag->getName()) ?>
                            </a>
                        <?php } ?>
                    <?php } ?>

                    <?php if ($networkTags) { ?>
                        <h3 class="cse side-bar-h3"><?php _ehtml('Networks we belong to') ?></h3>
                        <?php foreach ($networkTags AS $tag) { ?>
                            <a href="<?php echo $urlHandler->organisations() ?>?netwTag=<?php echo $tag->getId() ?>"
                               class="tag"><?php echo show_input($tag->getName()) ?></a>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

<?php /*
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
                            */ ?>

    <script src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/OrganisationController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>