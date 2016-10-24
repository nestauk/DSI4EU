<?php
require __DIR__ . '/header.php';
/** @var $organisation \DSI\Entity\Organisation */
/** @var $loggedInUser \DSI\Entity\User */
/** @var $canUserRequestMembership bool */
/** @var $isOwner bool */
/** @var $userCanEditOrganisation bool */
/** @var $organisationTypes \DSI\Entity\OrganisationType[] */
/** @var $organisationSizes \DSI\Entity\OrganisationSize[] */
/** @var $organisationProjects \DSI\Entity\OrganisationProject[] */
/** @var $partnerOrganisations \DSI\Entity\Organisation[] */
/** @var $links string[] */
/** @var $tags string[] */

if (!isset($urlHandler))
    $urlHandler = new \DSI\Service\URL();

?>
    <div
        ng-controller="OrganisationController"
        data-organisationid="<?php echo $organisation->getId() ?>">

        <div class="case-study-intro org">
            <div class="header-content org">
                <h1 class="case-study-h1 org"><?php echo show_input($organisation->getName()) ?></h1>
                <h3 class="home-hero-h3 org"></h3>
            </div>
        </div>

        <div class="page-content">
            <div class="w-row">
                <div class="content-left w-col w-col-8">
                    <div class="intro org"><?php echo show_input($organisation->getShortDescription()) ?></div>
                    <div class="intro org"><?php echo $organisation->getDescription() ?></div>
                    <div class="involved">
                        <h3 class="descr-h3 space"><?php echo show_input($organisation->getName()) ?> is involved
                            with:</h3>
                        <div class="w-row">
                            <div class="w-col w-col-6">
                                <h4 class="involved-h4 orgs-h">Projects</h4>
                                <?php foreach ($organisationProjects AS $organisationProject) { ?>
                                    <?php $project = $organisationProject->getProject() ?>
                                    <a class="sidebar-link" href="<?php echo $urlHandler->project($project) ?>">
                                        <span class="green">-&nbsp;</span><?php echo show_input($project->getName()) ?>
                                    </a>
                                <?php } ?>
                            </div>
                            <div class="w-col w-col-6">
                                <h4 class="involved-h4 orgs-h">Organisations</h4>
                                <?php foreach ($partnerOrganisations AS $org) { ?>
                                    <a class="sidebar-link" href="<?php echo $urlHandler->organisation($org) ?>">
                                        <span class="green">-&nbsp;</span><?php echo show_input($org->getName()) ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column-right-small w-col w-col-4">
                    <?php if ($userCanEditOrganisation) { ?>
                        <h3 class="cse side-bar-h3">Actions</h3>
                        <a class="sidebar-link" href="<?php echo $urlHandler->organisationEdit($organisation) ?>">
                            <span class="green">-&nbsp;</span>Edit organisation
                        </a>
                        <?php if ($isOwner OR ($loggedInUser AND $loggedInUser->isSysAdmin())) { ?>
                            <a class="sidebar-link"
                               href="<?php echo $urlHandler->organisationOwnerEdit($organisation) ?>">
                                <span class="green">-&nbsp;</span>Change owner
                            </a>
                            <a class="sidebar-link remove" href="#" ng-click="confirmDelete()">
                                <span class="green">-&nbsp;</span>Delete organisation
                            </a>
                        <?php } ?>
                        <?php /* <a class="sidebar-link"><span class="green">-&nbsp;</span>Publish / unpublish</a> */ ?>
                        <?php /* <a class="remove sidebar-link"><span class="green">-&nbsp;</span>Remove project</a> */ ?>
                    <?php } ?>
                    <h3 class="cse side-bar-h3">Info</h3>
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
                    <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                       href="<?php echo $organisation->getUrl() ?>">
                        <div class="login-li long menu-li readmore-li">Visit website</div>
                        <img class="login-arrow"
                             src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                    </a>
                    <h3 class="cse side-bar-h3">Tagged under</h3>
                    <?php foreach ($tags AS $tag) { ?>
                        <div class="tag"><?php echo show_input($tag) ?></div>
                    <?php } ?>

                    <?php if ($loggedInUser AND !$isOwner) { ?>
                        <a class="report-project" href="#" ng-click="report()">Report organisation</a>
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

    <script
        src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/OrganisationController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>