<?php
/** @var $loggedInUser \DSI\Entity\User */
/** @var $isHomePage bool */
/** @var $angularModules string[] */
/** @var $pageTitle string[] */
/** @var $stories \DSI\Entity\Story[] */
use \DSI\Service\URL;

?><!DOCTYPE html>
<html data-wf-site="56e2e31a1b1f8f784728a08c" data-wf-page="56fbef6ecf591b312d56f8be">
<head>
    <?php require __DIR__ . '/partialViews/head.php' ?>
</head>
<body id="top" ng-app="DSIApp">

<?php require __DIR__ . '/partialViews/loginModal.php' ?>

<div class="alt nav-main w-nav white-menu" data-animation="default" data-collapse="medium" data-duration="400"
     data-ix="menuhide">
    <div class="container-wide w-clearfix">
        <a class="w-nav-brand" href="#">
            <img class="logo-dark" src="images/dark.svg">
        </a>
        <nav class="w-nav-menu" role="navigation">
            <a class="alt nav w-nav-link" href="#">Explore DSI</a>
            <a class="alt nav w-nav-link" href="#">Case Studies</a>
            <a class="alt nav w-nav-link" href="#">Blog</a>
            <a class="alt nav w-nav-link" href="#">Projects</a>
            <a class="alt nav w-nav-link" href="#">Organisations</a>
            <a class="alt log-in nav w-nav-link" href="#" data-ix="open-login-modal">Login</a>
            <a class="alt log-in log-in-alt nav w-nav-link" href="#">Signup</a>
        </nav>
        <div class="w-nav-button">
            <div class="w-icon-nav-menu"></div>
        </div>
    </div>
</div>
<div class="nav-main w-nav" data-animation="default" data-collapse="medium" data-duration="400">
    <div class="container-wide nav-container w-clearfix">
        <a class="w-nav-brand" href="index.html">
            <img class="brand" src="images/all white.svg" width="160">
        </a>
        <nav class="m-nav-open w-nav-menu" role="navigation">
            <a class="nav w-nav-link" href="explore-dsi.html">Explore DSI</a>
            <a class="nav w-nav-link" href="stories.html">Case Studies</a>
            <a class="nav w-nav-link" href="stories.html">Blog</a>
            <a class="nav w-nav-link" href="projects.html">Projects</a>
            <a class="nav w-nav-link" href="organisations.html">Organisations</a>
            <a class="log-in log-in-alt nav w-nav-link" data-ix="open-login-modal">login</a>
            <div class="w-dropdown" data-delay="0">
                <div class="log-in nav w-dropdown-toggle">
                    <div>Sign up&nbsp;</div>
                </div>
                <nav class="create-drop-down w-dropdown-list">
                    <a class="drop-down-link w-dropdown-link" data-ix="create-project-modal" href="#">Create a new
                        project</a>
                    <a class="drop-down-link w-dropdown-link" data-ix="create-organisation-modal" href="#">Create an
                        organisation</a>
                    <div class="arror-up"></div>
                </nav>
            </div>
        </nav>
        <div class="menu-open w-nav-button">
            <div class="m-menu-btn w-icon-nav-menu"></div>
        </div>
    </div>
</div>
<div class="massive-hero w-section" data-ix="reveal-menu">
    <div class="massive-hero-container">
        <h1 class="massive-hero-h1" data-ix="fadeinup">Plain English heading that explains DSI4EU</h1>
        <div class="massive-hero-sub-header" data-ix="fadeinup-2">Sub text that further explains in plain English
            what exactly DSI4EU is and why it matters
        </div>
        <div class="massive-light-block" data-ix="fadeinup-4">
            <div class="case-study-label">Case study</div>
        </div>
    </div>
    <div class="massive-hero-slider w-slider" data-animation="outin" data-autoplay="1" data-delay="7000"
         data-duration="800" data-infinite="1">
        <div class="massive-hero-slide-mask w-slider-mask">
            <div class="massive-hero-slide w-slide wikihouse">
                <div class="container-wide massive-hero-slide-container">
                    <div class="slide-info" data-ix="slide-info">
                        <h2 class="massive-hero-slide-h2">Wikihouse</h2>
                        <p class="massive-hero-slide-detail">WikiHouse is an open source building&nbsp;system. Many
                            designers, collaborating&nbsp;to&nbsp;make it simple for everyone to design, print and
                            assemble&nbsp;beautiful, low-energy homes, customised to their&nbsp;needs.</p>
                        <a class="massive-hero-detail-link" href="home-alt.html">Read more</a>
                    </div>
                </div>
            </div>
            <div class="massive-hero-slide w-slide wikihouse">
                <div class="container-wide massive-hero-slide-container">
                    <div class="slide-info" data-ix="slide-info">
                        <h2 class="massive-hero-slide-h2">Wikihouse</h2>
                        <p class="massive-hero-slide-detail">WikiHouse is an open source building&nbsp;system. Many
                            designers, collaborating&nbsp;to&nbsp;make it simple for everyone to design, print and
                            assemble&nbsp;beautiful, low-energy homes, customised to their&nbsp;needs.</p>
                        <a class="massive-hero-detail-link" href="#">Read more</a>
                    </div>
                </div>
            </div>
            <div class="massive-hero-slide w-slide wikihouse">
                <div class="container-wide massive-hero-slide-container">
                    <div class="slide-info" data-ix="slide-info">
                        <h2 class="massive-hero-slide-h2">Wikihouse</h2>
                        <p class="massive-hero-slide-detail">WikiHouse is an open source building&nbsp;system. Many
                            designers, collaborating&nbsp;to&nbsp;make it simple for everyone to design, print and
                            assemble&nbsp;beautiful, low-energy homes, customised to their&nbsp;needs.</p>
                        <a class="massive-hero-detail-link" href="#">Read more</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-slider-arrow-left">
            <div class="icon-left w-icon-slider-left"></div>
        </div>
        <div class="w-slider-arrow-right">
            <div class="icon-right w-icon-slider-right"></div>
        </div>
        <div class="slide-nav-small w-round w-slider-nav"></div>
    </div>
</div>
<div class="massive-intro-section w-section">
    <div class="container-wide">
        <div class="whats-your-story" data-ix="fadeinup-5">
            <h3 class="what-h3">Have you got a project to share?</h3>
            <p class="what-p">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in
                eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo
                diam libero vitae erat. Aenean faucibus nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem
                vitae risus tristique posuere.</p>
            <a class="what-text-button" href="#">Get started</a>
        </div>
        <div class="what-is-dsi" data-ix="fadeinup-3">
            <h2 class="big-h2">DSI is BIG</h2>
            <div class="massiv-hero-stats">So far <strong>1,171</strong> Organisations
                <br>have collaborated on <strong>740</strong> projects
            </div>
            <a class="what-text-button" href="#">View projects</a>
        </div>
    </div>
</div>
<div class="w-section">
    <div class="container-wide">
        <h2 class="massive-homepage-h2" data-ix="fadeinuponload-2">Who is <span class="highlight">using</span> DSI?
        </h2>
        <p class="massive-homepage-p" data-ix="fadeinuponload-3">Lorem ipsum dolor sit amet, consectetur adipiscing
            elit. Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros
            dolor interdum nulla, ut commodo diam libero vitae era...</p>
        <div class="w-row">
            <div class="massive-who-column w-col w-col-4">
                <div class="massive-who-card" data-ix="fadeinuponload">
                    <h3 class="massive-h3">People</h3>
                    <p class="what-p">People use DSI4EU as a way to get involved with projects and share their
                        skills</p>
                    <a class="bottom what-text-button" href="#">Join DSI4EU</a>
                </div>
            </div>
            <div class="massive-who-column w-col w-col-4">
                <div class="alt massive-who-card" data-ix="fadeinuponload-2">
                    <h3 class="massive-h3">Projects</h3>
                    <p class="what-p">Existing DSI projects are showcased as well as new DSI projects</p>
                    <a class="bottom what-text-button" href="#">Projects</a>
                </div>
            </div>
            <div class="massive-who-column w-col w-col-4">
                <div class="massive-who-card" data-ix="fadeinuponload-3">
                    <h3 class="massive-h3">Organisations</h3>
                    <p class="what-p">Organisations offer funding and support to both projects &amp; individuals</p>
                    <a class="bottom what-text-button" href="#">Organisations</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="massive-showcase w-section">
    <div class="container-wide">
        <h2 class="massive-homepage-h2" data-ix="fadeinuponload-2">How are they&nbsp;<span
                class="highlight">using</span> DSI?</h2>
        <p class="massive-homepage-p" data-ix="fadeinuponload-3">Lorem ipsum dolor sit amet, consectetur adipiscing
            elit. Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros
            dolor interdum nulla, ut commodo diam libero vitae era...</p>
        <div class="case-studies-box" data-ix="fadeinuponload">
            <h3 class="what-h3">Case studies</h3>
            <p class="what-p">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in
                eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo
                diam libero vitae erat. Aenean faucibus nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem
                vitae risus tristique posuere.</p>
            <a class="what-text-button" href="#">See all case studies</a>
        </div>
        <div class="case-studies-row w-row">
            <div class="case-study-col-1 w-col w-col-4">
                <div class="onloadone" data-ix="fadeinuponload-3">
                    <div class="case-study-card" data-ix="case-study-card-overlay">
                        <div class="case-study-card-overlay"></div>
                        <div class="case-study-card-info">
                            <img class="case-study-card-logo" src="images/arduino.png" width="75">
                            <div class="case-study-card-p">In 2005, Massimo Banzi, an Italian engineer and designer,
                                started the Arduino project to enable students at the Interaction Design Institute
                                Ivrea (IDII) to build electronic devices using an open-source hardware board.
                            </div>
                            <a class="case-study-card-read-more" href="#">See the case study</a>
                        </div>
                        <div class="case-study-card-label w-clearfix">
                            <div class="case-study-card-name">Arduino</div>
                            <div class="case-study-card-name country">Switzerland</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="case-study-col-2 w-col w-col-4">
                <div class="onloadtwo" data-ix="fadeinuponload-4">
                    <div class="case-study-card tor" data-ix="case-study-card-overlay">
                        <div class="case-study-card-overlay tor"></div>
                        <div class="case-study-card-info">
                            <img class="case-study-card-logo" src="images/tor.png" width="75">
                            <div class="case-study-card-p">The Onion Router project (TOR) is a non-profit
                                organisation that conducts research and development into online privacy and
                                anonymity.&nbsp;It has developed software tools designed to stop people – including
                                government agencies and corporations – learning web users location or tracking their
                                browsing habits.
                            </div>
                            <a class="case-study-card-read-more" href="#">See the case study</a>
                        </div>
                        <div class="case-study-card-label w-clearfix">
                            <div class="case-study-card-name">The Onion Router P...</div>
                            <div class="case-study-card-name country">Germany</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="case-study-col-3 w-col w-col-4">
                <div class="onloadthree" data-ix="fadeinuponload-5">
                    <div class="case-study-card communia" data-ix="case-study-card-overlay">
                        <div class="case-study-card-overlay communia"></div>
                        <div class="case-study-card-info">
                            <img class="case-study-card-logo" src="images/communia.png" width="75">
                            <div class="case-study-card-p">COMMUNIA – The European Thematic Network on the Digital
                                Public Domain, is an international association based in Brussels. The COMMUNIA
                                association is built on the eponymous COMMUNIA Project Thematic Network, funded by
                                the European Commission from 2007 to 2011, which issued the Public Domain Manifesto
                                and gathered over 50 members..
                            </div>
                            <a class="case-study-card-read-more" href="#">See the case study</a>
                        </div>
                        <div class="case-study-card-label w-clearfix">
                            <div class="case-study-card-name">Communia</div>
                            <div class="case-study-card-name country">Italy</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="w-section">
    <div class="email-signup">
        <div class="container-wide newsletter-signup"></div>
    </div>
</div>
<div>

<?php require __DIR__ . '/footer.php' ?>