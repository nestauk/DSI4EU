<?php
require __DIR__ . '/header.php';
/** @var $organisation \DSI\Entity\Organisation */
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
    <script
        src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/OrganisationController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <div class="header-large-section">
        <div class="header-large nesta"
             style="background-image: linear-gradient(180deg, rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)), url('<?php echo \DSI\Entity\Image::ORGANISATION_HEADER_URL . $organisation->getHeaderImageOrDefault() ?>');">
            <div class="container-wide container-wide-header-large">
                <?php if ($userCanEditOrganisation) { ?>
                    <a class="w-button dsi-button profile-edit" style="bottom: auto;top: 180px;width: auto;"
                       href="<?php echo $urlHandler->editOrganisation($organisation) ?>">
                        Edit organisation</a>
                <?php } ?>
                <h1 class="header-large-h1-centre"
                    data-ix="fadeinuponload"><?php echo show_input($organisation->getName()) ?></h1>
                <div class="header-large-desc">
                    <a class="ext-url" data-ix="fadeinup-2"
                       href="<?php echo $organisation->getUrl() ?>"><?php echo $organisation->getUrl() ?></a>
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
                     src="<?php echo \DSI\Entity\Image::ORGANISATION_LOGO_URL . $organisation->getLogoOrDefault() ?>">
            </div>
            <div class="case-study-single-container w-container">
                <h2 class="centered" data-ix="fadeinuponload-4">
                    About <?php echo show_input($organisation->getName()) ?></h2>
                <p class="centered" data-ix="fadeinuponload-5">
                    <?php echo show_input($organisation->getShortDescription()) ?>
                </p>
                <h4 class="case-study-intro-detail centered" data-ix="fadeinuponload-5">
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
                </h4>
                <div class="centered tagged" data-ix="fadeinup-5">
                    Tagged under:
                    <?php foreach ($tags AS $tag) { ?>
                        <span class="tag"><?php echo show_input($tag) ?></span>
                    <?php } ?>
                </div>
                <h2 class="centered" data-ix="fadeinup">Overview
                    of <?php echo show_input($organisation->getName()) ?></h2>
                <p class="case-study-main-text" data-ix="fadeinup">
                    <?php echo $organisation->getDescription() ?>
                </p>
                <div class="centered org url-block" data-ix="fadeinup">
                    <div class="involved">
                        <h2 class="centered" data-ix="fadeinup"><?php echo show_input($organisation->getName()) ?> is
                            involved with:</h2>
                        <div class="w-row">
                            <div class="people-col w-col w-col-6">
                                <h4 class="involved-h4">Projects</h4>
                                <?php foreach ($organisationProjects AS $organisationProject) { ?>
                                    <?php $project = $organisationProject->getProject() ?>
                                    <div class="involved-card">
                                        <div class="w-row">
                                            <div class="w-col w-col-5 w-col-small-5 w-col-tiny-5">
                                                <img class="involved-organisation-img"
                                                     style="max-width:100px;max-height:50px"
                                                     src="<?php echo \DSI\Entity\Image::PROJECT_LOGO_URL . $project->getLogoOrDefaultSilver() ?>">
                                            </div>
                                            <div class="w-clearfix w-col w-col-7 w-col-small-7 w-col-tiny-7">
                                                <div style="overflow: hidden;"
                                                     class="card-name"><?php echo show_input($project->getName()) ?></div>
                                                <div
                                                    class="card-position"><?php echo show_input($project->getCountryName()) ?></div>
                                            </div>
                                        </div>
                                        <a class="view-profile"
                                           href="<?php echo $urlHandler->project($project) ?>">View</a>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="orgs-col w-col w-col-6">
                                <h4 class="involved-h4">Organisations</h4>
                                <?php foreach ($partnerOrganisations AS $org) { ?>
                                    <div class="involved-card">
                                        <div class="w-row">
                                            <div class="w-col w-col-5 w-col-small-5 w-col-tiny-5">
                                                <img class="involved-organisation-img"
                                                     style="max-width:100px;max-height:50px"
                                                     src="<?php echo \DSI\Entity\Image::ORGANISATION_LOGO_URL . $org->getLogoOrDefaultSilver() ?>">
                                            </div>
                                            <div class="w-clearfix w-col w-col-7 w-col-small-7 w-col-tiny-7">
                                                <div style="overflow: hidden;"
                                                     class="card-name"><?php echo show_input($org->getName()) ?></div>
                                                <div
                                                    class="card-position"><?php echo show_input($org->getCountryName()) ?></div>
                                            </div>
                                        </div>
                                        <a class="view-profile"
                                           href="<?php echo $urlHandler->organisation($org) ?>">View</a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>