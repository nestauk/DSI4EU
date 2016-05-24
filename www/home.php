<?php
$isHomePage = true;
require __DIR__ . '/header.php';
?>

    <div class="w-section homepage-hero">
        <div class="container-wide">
            <h1 class="hero-h1">A platform for Digital Social Innovation</h1>
            <?php if (!isset($loggedInUser) OR $loggedInUser === null) { ?>
                <p class="hero-sub-heading">
                    Share your project, find great people to work with &amp; interesting projects to fund.
                    <br>Sound interesting? Sign up now to get started.
                </p>
                <div class="w-form invite-form" ng-controller="RegisterController">
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
            <?php } ?>
            <div class="partner-logos">
                <div class="partner">
                    <a href="#" class="w-inline-block partner-hp-link">
                        <img src="<?php echo SITE_RELATIVE_PATH ?>/images/nesta-6a9b5fe999e8323b379ccc0d8e70290f.png">
                    </a>
                </div>
                <div class="partner">
                    <a href="#" class="w-inline-block partner-hp-link">
                        <img
                            src="<?php echo SITE_RELATIVE_PATH ?>/images/variable-7e5636d891924e07bd2d6f4494cad3f9.png">
                    </a>
                </div>
                <div class="partner">
                    <a href="#" class="w-inline-block partner-hp-link">
                        <img src="<?php echo SITE_RELATIVE_PATH ?>/images/waag-f1d052f43133268eaf2e13090a0b4bf1_1.png">
                    </a>
                </div>
                <div class="partner">
                    <a href="#" class="w-inline-block partner-hp-link">
                        <img
                            src="<?php echo SITE_RELATIVE_PATH ?>/images/interago-73f477dc0a1d65919238231e918bb56f.png">
                    </a>
                </div>
                <div class="partner">
                    <a href="#" class="w-inline-block partner-hp-link">
                        <img src="<?php echo SITE_RELATIVE_PATH ?>/images/iri-51848d69e8fcb9fa8c55089f574e311c.png">
                    </a>
                </div>
                <div class="partner">
                    <a href="#" class="w-inline-block partner-hp-link">
                        <img src="<?php echo SITE_RELATIVE_PATH ?>/images/swirrl-63c559d4c17da1273922275c1284d09b.png">
                    </a>
                </div>
                <div class="partner">
                    <a href="#" class="w-inline-block partner-hp-link">
                        <img src="<?php echo SITE_RELATIVE_PATH ?>/images/esade-b6eb89a49bfd702310d4e963f5f83695.png">
                    </a>
                </div>
                <div class="partner">
                    <a href="#" class="w-inline-block partner-hp-link">
                        <img
                            src="<?php echo SITE_RELATIVE_PATH ?>/images/future-everything-2f261cf2d078264179fd82b21e5927b7.png">
                    </a>
                </div>
            </div>
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

    <div class="w-section section-white">
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

    <div class="w-section section-grey">
        <div class="container-wide">
            <h2 class="home-h2 centre">What's happening in Digital Social Innovation?</h2>
            <p class="p-big centre">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in
                eros elementum tristique...</p>
            <div class="w-row hp-post-row">
                <div class="w-col w-col-4 w-col-stack">
                    <a href="#" class="w-inline-block hp-post-link">
                        <div class="hp-post-img"></div>
                        <div class="w-clearfix hp-post">
                            <div class="hp-post-meta category">News</div>
                            <div class="hp-post-meta hp-date">25th april 2016</div>
                            <h3 class="hp-post-h3">Policy Workshop: Shaping the Future of Digital Social Innovation in
                                Europe</h3>
                            <p class="hp-post-p">Join us in Brussels the 29th of June at DG Connect for a Policy
                                Workshop on Digital Social Innovation...</p>
                        </div>
                    </a>
                </div>
                <div class="w-col w-col-4 w-col-stack">
                    <a href="#" class="w-inline-block hp-post-link">
                        <div class="hp-post-img post-2"></div>
                        <div class="w-clearfix hp-post">
                            <div class="hp-post-meta category inspiration">Events</div>
                            <div class="hp-post-meta hp-date">14th July 2015</div>
                            <h3 class="hp-post-h3">In a rapidly changing world, we are all designers.</h3>
                            <p class="hp-post-p">Ezio Manzini is a world-leading expert on sustainable design. He is
                                founder of the&nbsp;DESIS...</p>
                        </div>
                    </a>
                </div>
                <div class="w-col w-col-4 w-col-stack">
                    <a href="#" class="w-inline-block hp-post-link">
                        <div class="hp-post-img post-3"></div>
                        <div class="w-clearfix hp-post">
                            <div class="hp-post-meta category discovery">Case Studies</div>
                            <div class="hp-post-meta hp-date">16th June 2015</div>
                            <h3 class="hp-post-h3">Digital Social Innovation, a relatively new concept</h3>
                            <p class="hp-post-p">Digital Social Innovation (DSI) is an emerging field, with little
                                existing knowledge on who the digital social innovators are...</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>