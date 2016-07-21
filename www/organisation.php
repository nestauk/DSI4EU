<?php
require __DIR__ . '/header.php';
/** @var $organisation \DSI\Entity\Organisation */
/** @var $canUserRequestMembership bool */
/** @var $isOwner bool */
/** @var $organisationTypes \DSI\Entity\OrganisationType[] */
/** @var $organisationSizes \DSI\Entity\OrganisationSize[] */
/** @var $organisationProjects \DSI\Entity\OrganisationProject[] */
/** @var $partnerOrganisations \DSI\Entity\Organisation[] */
/** @var $links string[] */
/** @var $tags string[] */
?>
    <script src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/OrganisationController.js"></script>

    <div class="header-large-section">
        <div class="header-large nesta"
             style="background-image: linear-gradient(180deg, rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)), url('<?php echo \DSI\Entity\Image::ORGANISATION_HEADER_URL . $organisation->getHeaderImage() ?>');">
            <div class="container-wide container-wide-header-large">
                <?php if ($isOwner) { ?>
                    <a class="w-button dsi-button profile-edit" style="bottom: auto;top: 180px;width: auto;"
                       href="<?php echo \DSI\Service\URL::editOrganisation($organisation) ?>">
                        Edit organisation</a>
                <?php } ?>
                <h1 class="header-large-h1-centre"
                    data-ix="fadeinuponload"><?php echo show_input($organisation->getName()) ?></h1>
                <div class="header-large-desc">
                    <a class="ext-url" data-ix="fadeinup-2"
                       href="<?php echo $organisation->getUrl() ?>"><?php echo $organisation->getUrl() ?></a>
                    <div class="project-single-social" data-ix="fadeinup-3">
                        <div class="w-row">
                            <?php if (isset($links['facebook'])) { ?>
                                <div class="w-col w-col-3 w-col-small-6 w-col-tiny-6">
                                    <div class="sm-nu-bloxk w-clearfix">
                                        <a href="<?php echo $links['facebook'] ?>" target="_blank">
                                            <img class="sm-icon"
                                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/facebook-logo.png"
                                                 width="40">
                                            <div class="hero-social-label">Facebook</div>
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (isset($links['twitter'])) { ?>
                                <div class="w-col w-col-3 w-col-small-6 w-col-tiny-6">
                                    <div class="sm-nu-bloxk w-clearfix">
                                        <a href="<?php echo $links['twitter'] ?>" target="_blank">
                                            <img class="sm-icon"
                                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/twitter-logo-silhouette.png">
                                            <div class="hero-social-label">Twitter</div>
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (isset($links['github'])) { ?>
                                <div class="w-col w-col-3 w-col-small-6 w-col-tiny-6">
                                    <div class="sm-nu-bloxk w-clearfix">
                                        <a href="<?php echo $links['github'] ?>" target="_blank">
                                            <img class="sm-icon"
                                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/social.png">
                                            <div class="hero-social-label">Github</div>
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (isset($links['googleplus'])) { ?>
                                <div class="w-col w-col-3 w-col-small-6 w-col-tiny-6">
                                    <div class="sm-nu-bloxk w-clearfix">
                                        <a href="<?php echo $links['googleplus'] ?>" target="_blank">
                                            <img class="sm-icon"
                                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/google-plus-logo.png">
                                            <div class="hero-social-label">Google +</div>
                                        </a>
                                    </div>
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
            <?php if ($organisation->getLogo()) { ?>
                <div class="case-study-logo" data-ix="fadeinuponload-3">
                    <div class="ab-fab">
                        <img class="logo-img"
                             src="<?php echo \DSI\Entity\Image::ORGANISATION_LOGO_URL . $organisation->getLogo() ?>">
                    </div>
                </div>
            <?php } ?>
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
                                                     src="<?php echo \DSI\Entity\Image::PROJECT_LOGO_URL . $project->getLogoOrDefault() ?>">
                                            </div>
                                            <div class="w-clearfix w-col w-col-7 w-col-small-7 w-col-tiny-7">
                                                <div
                                                    class="card-name"><?php echo show_input($project->getName()) ?></div>
                                                <div
                                                    class="card-position"><?php echo show_input($project->getCountryName()) ?></div>
                                            </div>
                                        </div>
                                        <a class="view-profile"
                                           href="<?php echo \DSI\Service\URL::project($project) ?>">View</a>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="orgs-col w-col w-col-6">
                                <h4 class="involved-h4">Organisations</h4>
                                <?php foreach ($partnerOrganisations AS $org) { ?>
                                    <div class="involved-card">
                                        <div class="w-row">
                                            <div class="w-col w-col-5 w-col-small-5 w-col-tiny-5">
                                                <?php if ($org->getLogo()) { ?>
                                                    <img class="involved-organisation-img"
                                                         style="max-width:100px;max-height:50px"
                                                         src="<?php echo \DSI\Entity\Image::ORGANISATION_LOGO_URL . $org->getLogo() ?>">
                                                <?php } ?>
                                            </div>
                                            <div class="w-clearfix w-col w-col-7 w-col-small-7 w-col-tiny-7">
                                                <div class="card-name"><?php echo show_input($org->getName()) ?></div>
                                                <div
                                                    class="card-position"><?php echo show_input($org->getCountryName()) ?></div>
                                            </div>
                                        </div>
                                        <a class="view-profile"
                                           href="<?php echo \DSI\Service\URL::organisation($org) ?>">View</a>
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