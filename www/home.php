<?php
$isHomePage = true;

use \DSI\Service\URL;

require __DIR__ . '/header.php';
?>

    <div class="w-section homepage-hero">
        <div class="overlay"></div>
        <div class="w-background-video video-bg" data-autoplay="data-autoplay" data-loop="data-loop"
             data-poster-url="https://daks2k3a4ib2z.cloudfront.net/56e2e31a1b1f8f784728a08c/573d767d3a2f809c6a1a3e0b_dsi bg-poster-00001.jpg"
             data-video-urls="https://daks2k3a4ib2z.cloudfront.net/56e2e31a1b1f8f784728a08c/573d767d3a2f809c6a1a3e0b_dsi bg-transcode.webm,https://daks2k3a4ib2z.cloudfront.net/56e2e31a1b1f8f784728a08c/573d767d3a2f809c6a1a3e0b_dsi bg-transcode.mp4"
             data-wf-ignore="data-wf-ignore"></div>
        <div class="container-wide">
            <h1 class="hero-h1">A platform for Digital Social Innovation</h1>
            <?php if (!isset($loggedInUser) OR $loggedInUser === null) { ?>
                <p class="hero-sub-heading">
                    Share your project, find great people to work with &amp; interesting projects to fund.
                    <br>Sound interesting? Sign up now to get started.
                </p>
                <div class="w-form invite-form" ng-controller="RegisterController" ng-cloak>
                    <form id="email-form" name="email-form" data-name="Email Form" ng-submit="onSubmit()">
                        <input id="Email" type="email" placeholder="Email" name="Email" data-name="Email"
                               class="w-input invite-form-field"
                               ng-model="email.value"
                               ng-class="{error: errors.email}">
                        <div style="color:red" ng-show="errors.email" ng-bind="errors.email"></div>
                        <input id="Password" type="password" placeholder="Password" name="Password" data-name="Password"
                               required="required" class="w-input invite-form-field"
                               ng-model="password.value"
                               ng-class="{error: errors.password}">
                        <div style="color:red" ng-show="errors.password" ng-bind="errors.password"></div>
                        <div ng-hide="registered">
                            <input ng-hide="loading" type="submit" value="Sign Up"
                                   class="w-button invite-form-field submit">
                            <button ng-show="loading" type="button" class="w-button invite-form-field submit">
                                Loading...
                            </button>
                        </div>
                        <button ng-show="registered" type="button" class="w-button invite-form-field submit">
                            Welcome to
                            Digital Social!
                        </button>
                    </form>
                </div>
                <div class="hero-sub-heading social">or sign in with</div>
                <div class="clear-social">
                    <div class="w-row">
                        <div class="w-col w-col-3">
                            <div class="w-clearfix sm-nu-bloxk">
                                <img class="sm-icon" src="images/facebook-logo.png" width="40">
                                <a href="<?php echo URL::loginWithFacebook()?>" class="hero-social-label">Facebook</a>
                            </div>
                        </div>
                        <div class="w-col w-col-3">
                            <div class="w-clearfix sm-nu-bloxk">
                                <img class="sm-icon" src="images/twitter-logo-silhouette.png">
                                <a href="<?php echo URL::loginWithTwitter()?>" class="hero-social-label">Twitter</a>
                            </div>
                        </div>
                        <div class="w-col w-col-3">
                            <div class="w-clearfix sm-nu-bloxk">
                                <img class="sm-icon" src="images/social.png">
                                <a href="<?php echo URL::loginWithGitHub()?>" class="hero-social-label">Github</a>
                            </div>
                        </div>
                        <div class="w-col w-col-3">
                            <div class="w-clearfix sm-nu-bloxk">
                                <img class="sm-icon" src="images/google-plus-logo.png">
                                <a href="<?php echo URL::loginWithGoogle()?>" class="hero-social-label">Google +</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="w-section section-grey">
        <div class="w-container">
            <div class="w-row">
                <div class="w-col w-col-4 w-col-stack">
                    <h2 class="home-h2">What is Digital Social Innovation?</h2>
                    <p class="p-big">Digital Social Innovation (DSI) uses technology to solve social challenges, from
                        crowdsourcing campaigns to helping create transparency in government spending through open
                        data</p>
                </div>
                <div class="w-col w-col-8 w-col-stack">
                    <div class="image-placeholder"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="section-white">
        <div class="container-wide">
            <h2 class="home-h2 centre">DSI4EU is a partnership between 4 leaders in the field</h2>
            <p class="p-big centre">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique...</p>
            <div class="w-row partner-logos">
                <div class="w-col w-col-3">
                    <img class="partner-logo" src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg">
                </div>
                <div class="w-col w-col-3">
                    <img class="partner-logo" src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg">
                </div>
                <div class="w-col w-col-3">
                    <img class="partner-logo" src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg">
                </div>
                <div class="w-col w-col-3">
                    <img class="partner-logo" src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg">
                </div>
            </div>
        </div>
    </div>

    <div class="w-section section-grey">
        <div class="container-wide">
            <h2 class="home-h2 centre">Who does Digital Social Innovation?</h2>
            <p class="p-big centre">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in
                eros elementum tristique...</p>
            <div class="w-row showcase">
                <div class="w-col w-col-4 w-col-stack">
                    <div class="showcase-card">
                        <h3 class="card-h3">People</h3>
                        <p class="show-card-p">People use DSI4EU as a way to get involved with projects and share their
                            skills</p>
                        <a href="#" class="w-button card-btn">Join DSI4EU</a>
                    </div>
                </div>
                <div class="w-col w-col-4 w-col-stack">
                    <div class="showcase-card">
                        <h3 class="card-h3">Projects</h3>
                        <p class="show-card-p">Existing DSI projects are showcased as well as new DSI projects</p>
                        <a href="#" class="w-button card-btn">View existing projects</a>
                    </div>
                </div>
                <div class="w-col w-col-4 w-col-stack">
                    <div class="showcase-card">
                        <h3 class="card-h3">Organisations</h3>
                        <p class="show-card-p">Organisations offer funding and support to both projects &amp;
                            individuals</p>
                        <a href="#" class="w-button card-btn">Check out our partner organisations</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-section cta-block">
        <div class="container-wide">
            <div class="w-row">
                <div class="w-col w-col-6 cta-column-left">
                    <h2 class="home-h2 cta-text-light">Have you got a project to add?</h2>
                    <p class="p-big cta-text-light">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique...</p>
                </div>
                <div class="w-col w-col-6 cta-column-right">
                    <a class="w-button dsi-button cta" href="#">Add project +</a>
                </div>
            </div>
        </div>
    </div>

    <div class="w-section section-grey">
        <div class="container-wide">
            <h2 class="home-h2 centre">What's happening in Digital Social Innovation?</h2>
            <p class="p-big centre">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique...</p>
            <div class="w-row hp-post-row">
                <div class="w-col w-col-4 w-col-stack">
                    <a class="w-inline-block hp-post-link" href="#">
                        <div class="hp-post-img"></div>
                        <div class="w-clearfix hp-post">
                            <div class="hp-post-meta category">News</div>
                            <div class="hp-post-meta hp-date">25th april 2016</div>
                            <h3 class="hp-post-h3">Policy Workshop: Shaping the Future of Digital Social Innovation in Europe</h3>
                            <p class="hp-post-p">Join us in Brussels the 29th of June at DG Connect for a Policy Workshop on Digital Social Innovation...</p>
                        </div>
                    </a>
                </div>
                <div class="w-col w-col-4 w-col-stack">
                    <a class="w-inline-block hp-post-link" href="#">
                        <div class="hp-post-img post-2"></div>
                        <div class="w-clearfix hp-post">
                            <div class="hp-post-meta category inspiration">Events</div>
                            <div class="hp-post-meta hp-date">14th July 2015</div>
                            <h3 class="hp-post-h3">In a rapidly changing world, we are all designers.</h3>
                            <p class="hp-post-p">Ezio Manzini is a world-leading expert on sustainable design. He is founder of the&nbsp;DESIS...</p>
                        </div>
                    </a>
                </div>
                <div class="w-col w-col-4 w-col-stack">
                    <a class="w-inline-block hp-post-link" href="#">
                        <div class="hp-post-img post-3"></div>
                        <div class="w-clearfix hp-post">
                            <div class="hp-post-meta category discovery">Case Studies</div>
                            <div class="hp-post-meta hp-date">16th June 2015</div>
                            <h3 class="hp-post-h3">Digital Social Innovation, a relatively new concept</h3>
                            <p class="hp-post-p">Digital Social Innovation (DSI) is an emerging field, with little existing knowledge on who the digital social innovators are...</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>