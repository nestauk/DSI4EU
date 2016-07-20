<?php
require __DIR__ . '/header.php';
/** @var $organisation \DSI\Entity\Organisation */
/** @var $canUserRequestMembership bool */
/** @var $isOwner bool */
/** @var $organisationTypes \DSI\Entity\OrganisationType[] */
/** @var $organisationSizes \DSI\Entity\OrganisationSize[] */
?>
    <script src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/OrganisationController.js"></script>

    <div class="header-large-section">
        <div class="header-large nesta">
            <div class="container-wide container-wide-header-large">
                <a class="w-button dsi-button profile-edit" style="bottom: auto;top: 180px;width: auto;"
                   href="<?php echo \DSI\Service\URL::editOrganisation($organisation) ?>">
                    Edit organisation</a>
                <h1 class="header-large-h1-centre"
                    data-ix="fadeinuponload"><?php echo show_input($organisation->getName()) ?></h1>
                <div class="header-large-desc">
                    <a class="ext-url" data-ix="fadeinup-2" href="#">www.nesta.org</a>
                    <div class="project-single-social" data-ix="fadeinup-3">
                        <div class="w-row">
                            <div class="w-col w-col-3 w-col-small-6 w-col-tiny-6">
                                <div class="sm-nu-bloxk w-clearfix">
                                    <img class="sm-icon" src="<?php echo SITE_RELATIVE_PATH ?>/images/facebook-logo.png"
                                         width="40">
                                    <div class="hero-social-label">Facebook</div>
                                </div>
                            </div>
                            <div class="w-col w-col-3 w-col-small-6 w-col-tiny-6">
                                <div class="sm-nu-bloxk w-clearfix">
                                    <img class="sm-icon"
                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/twitter-logo-silhouette.png">
                                    <div class="hero-social-label">Twitter</div>
                                </div>
                            </div>
                            <div class="w-col w-col-3 w-col-small-6 w-col-tiny-6">
                                <div class="sm-nu-bloxk w-clearfix">
                                    <img class="sm-icon" src="<?php echo SITE_RELATIVE_PATH ?>/images/social.png">
                                    <div class="hero-social-label">Github</div>
                                </div>
                            </div>
                            <div class="w-col w-col-3 w-col-small-6 w-col-tiny-6">
                                <div class="sm-nu-bloxk w-clearfix">
                                    <img class="sm-icon"
                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/google-plus-logo.png">
                                    <div class="hero-social-label">Google +</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="case-study-main">
        <div class="container-wide">
            <div class="case-study-logo" data-ix="fadeinuponload-3">
                <div class="ab-fab">
                    <img class="logo-img"
                         src="<?php echo SITE_RELATIVE_PATH ?>/images/nesta-6a9b5fe999e8323b379ccc0d8e70290f.png">
                </div>
            </div>
            <div class="case-study-single-container w-container">
                <h2 class="centered" data-ix="fadeinuponload-4">
                    About <?php echo show_input($organisation->getName()) ?></h2>
                <p class="centered" data-ix="fadeinuponload-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor
                    interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et justo cursus id rutrum
                    lorem imperdiet. Nunc ut sem vit</p>
                <h4 class="case-study-intro-detail centered"
                    data-ix="fadeinuponload-5"><?php echo show_input($organisation->getName()) ?> is based in England
                    and
                    has been running since 2005</h4>
                <div class="centered tagged" data-ix="fadeinup-5">Tagged under: <span class="tag">Technology</span>
                    <span class="tag">Science</span> <span class="tag">Obfuscation</span>
                </div>
                <h2 class="centered" data-ix="fadeinup">Overview
                    of <?php echo show_input($organisation->getName()) ?></h2>
                <p class="case-study-main-text" data-ix="fadeinup">The Tor network is a group of&nbsp;volunteer-operated
                    servers that allows people to improve their privacy and security on the Internet. Tor's users employ
                    this network by connecting through a series of virtual tunnels rather than making a direct
                    connection, thus allowing both organizations and individuals to share information over public
                    networks without compromising their privacy. Along the same line, Tor is an effective censorship
                    circumvention tool, allowing its users to reach otherwise blocked destinations or content. Tor can
                    also be used as a building block for software developers to create new communication tools with
                    built-in privacy features.Individuals use Tor to keep websites from tracking them and their family
                    members, or to connect to news sites, instant messaging services, or the like when these are blocked
                    by their local Internet providers.
                    <br>
                    <br>Tor's&nbsp;hidden services&nbsp;let users publish web sites and other services without needing
                    to reveal the location of the site. Individuals also use Tor for socially sensitive communication:
                    chat rooms and web forums for rape and abuse survivors, or people with illnesses.Journalists use Tor
                    to communicate more safely with whistleblowers and dissidents. Non-governmental organizations (NGOs)
                    use Tor to allow their workers to connect to their home website while they're in a foreign country,
                    without notifying everybody nearby that they're working with that organization.Groups such as
                    Indymedia recommend Tor for safeguarding their members' online privacy and security. Activist groups
                    like the Electronic Frontier Foundation (EFF) recommend Tor as a mechanism for maintaining civil
                    liberties online.
                    <br>
                    <br>Corporations use Tor as a safe way to conduct competitive analysis, and to protect sensitive
                    procurement patterns from eavesdroppers. They also use it to replace traditional VPNs, which reveal
                    the exact amount and timing of communication. Which locations have employees working late? Which
                    locations have employees consulting job-hunting websites? Which research divisions are communicating
                    with the company's patent lawyers?A branch of the U.S. Navy uses Tor for open source intelligence
                    gathering, and one of its teams used Tor while deployed in the Middle East recently. Law enforcement
                    uses Tor for visiting or surveilling web sites without leaving government IP addresses in their web
                    logs, and for security during sting operations.</p>
                <div class="centered org url-block" data-ix="fadeinup">
                    <div class="involved">
                        <h2 class="centered" data-ix="fadeinup"><?php echo show_input($organisation->getName()) ?> is
                            involved with:</h2>
                        <div class="w-row">
                            <div class="people-col w-col w-col-6">
                                <h4 class="involved-h4">Projects</h4>
                                <div class="involved-card">
                                    <div class="w-row">
                                        <div class="w-col w-col-5 w-col-small-5 w-col-tiny-5">
                                            <img class="involved-organisation-img"
                                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/waag.png">
                                        </div>
                                        <div class="w-clearfix w-col w-col-7 w-col-small-7 w-col-tiny-7">
                                            <div class="card-name">The Waag Society</div>
                                            <div class="card-position">Holland</div>
                                        </div>
                                    </div>
                                    <a class="view-profile" href="#">View</a>
                                </div>
                                <div class="involved-card">
                                    <div class="w-row">
                                        <div class="w-col w-col-5 w-col-small-5 w-col-tiny-5">
                                            <img class="involved-organisation-img"
                                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/waag.png">
                                        </div>
                                        <div class="w-clearfix w-col w-col-7 w-col-small-7 w-col-tiny-7">
                                            <div class="card-name">The Waag Society</div>
                                            <div class="card-position">Holland</div>
                                        </div>
                                    </div>
                                    <a class="view-profile" href="#">View</a>
                                </div>
                                <div class="involved-card">
                                    <div class="w-row">
                                        <div class="w-col w-col-5 w-col-small-5 w-col-tiny-5">
                                            <img class="involved-organisation-img"
                                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/waag.png">
                                        </div>
                                        <div class="w-clearfix w-col w-col-7 w-col-small-7 w-col-tiny-7">
                                            <div class="card-name">The Waag Society</div>
                                            <div class="card-position">Holland</div>
                                        </div>
                                    </div>
                                    <a class="view-profile" href="#">View</a>
                                </div>
                                <div class="involved-card">
                                    <div class="w-row">
                                        <div class="w-col w-col-5 w-col-small-5 w-col-tiny-5">
                                            <img class="involved-organisation-img"
                                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/waag.png">
                                        </div>
                                        <div class="w-clearfix w-col w-col-7 w-col-small-7 w-col-tiny-7">
                                            <div class="card-name">The Waag Society</div>
                                            <div class="card-position">Holland</div>
                                        </div>
                                    </div>
                                    <a class="view-profile" href="#">View</a>
                                </div>
                            </div>
                            <div class="orgs-col w-col w-col-6">
                                <h4 class="involved-h4">Organisations</h4>
                                <div class="involved-card">
                                    <div class="w-row">
                                        <div class="w-col w-col-5 w-col-small-5 w-col-tiny-5">
                                            <img class="involved-organisation-img"
                                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/waag.png">
                                        </div>
                                        <div class="w-clearfix w-col w-col-7 w-col-small-7 w-col-tiny-7">
                                            <div class="card-name">The Waag Society</div>
                                            <div class="card-position">Holland</div>
                                        </div>
                                    </div>
                                    <a class="view-profile" href="#">View</a>
                                </div>
                                <div class="involved-card">
                                    <div class="w-row">
                                        <div class="w-col w-col-5 w-col-small-5 w-col-tiny-5">
                                            <img class="involved-organisation-img"
                                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/waag.png">
                                        </div>
                                        <div class="w-clearfix w-col w-col-7 w-col-small-7 w-col-tiny-7">
                                            <div class="card-name">The Waag Society</div>
                                            <div class="card-position">Holland</div>
                                        </div>
                                    </div>
                                    <a class="view-profile" href="#">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>