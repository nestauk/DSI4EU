<?php
$isHomePage = true;

use \DSI\Service\URL;

require __DIR__ . '/header.php';
/** @var $stories \DSI\Entity\Story[] */
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
                        <div class="w-clearfix signup-input">
                            <div class="error-message" ng-show="errors.email" style="display:block">
                                <div ng-bind="errors.email"></div>
                                <div class="arrow-down"></div>
                            </div>
                            <input class="w-input invite-form-field" data-name="Email" id="Email" maxlength="256"
                                   name="Email" placeholder="Email" type="email"
                                   ng-model="email.value"
                                   ng-class="{error: errors.email}">
                        </div>

                        <div class="w-clearfix signup-input">
                            <div class="error-message" ng-show="errors.password" style="display:block">
                                <div ng-bind="errors.password"></div>
                                <div class="arrow-down"></div>
                            </div>
                            <input class="w-input invite-form-field" data-name="Password" id="Password" maxlength="256"
                                   name="Password" placeholder="Password" required="required" type="password"
                                   ng-model="password.value"
                                   ng-class="{error: errors.password}">
                        </div>

                        <div class="w-clearfix signup-input">
                            <input class="w-button invite-form-field submit" type="submit" value="Sign up"
                                   ng-disabled="loading"
                                   ng-hide="registered"
                                   ng-value="loading ? 'Loading...' : 'Sign up'">
                            <button ng-show="registered" type="button" class="w-button invite-form-field submit">
                                Welcome to
                                Digital Social!
                            </button>
                        </div>

                    </form>
                </div>
                <div class="hero-sub-heading social">or sign in with</div>
                <div class="clear-social">
                    <div class="w-row">
                        <div class="w-col w-col-3">
                            <div class="w-clearfix sm-nu-bloxk">
                                <img class="sm-icon" src="images/facebook-logo.png" width="40">
                                <a href="<?php echo URL::loginWithFacebook() ?>" class="hero-social-label">Facebook</a>
                            </div>
                        </div>
                        <div class="w-col w-col-3">
                            <div class="w-clearfix sm-nu-bloxk">
                                <img class="sm-icon" src="images/twitter-logo-silhouette.png">
                                <a href="<?php echo URL::loginWithTwitter() ?>" class="hero-social-label">Twitter</a>
                            </div>
                        </div>
                        <div class="w-col w-col-3">
                            <div class="w-clearfix sm-nu-bloxk">
                                <img class="sm-icon" src="images/social.png">
                                <a href="<?php echo URL::loginWithGitHub() ?>" class="hero-social-label">Github</a>
                            </div>
                        </div>
                        <div class="w-col w-col-3">
                            <div class="w-clearfix sm-nu-bloxk">
                                <img class="sm-icon" src="images/google-plus-logo.png">
                                <a href="<?php echo URL::loginWithGoogle() ?>" class="hero-social-label">Google +</a>
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
            <p class="p-big centre">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in
                eros elementum tristique...</p>
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
                        <a href="#top" class="w-button card-btn">Join DSI4EU</a>
                    </div>
                </div>
                <div class="w-col w-col-4 w-col-stack">
                    <div class="showcase-card">
                        <h3 class="card-h3">Projects</h3>
                        <p class="show-card-p">Existing DSI projects are showcased as well as new DSI projects</p>
                        <a href="<?php echo URL::projects() ?>" class="w-button card-btn">View existing projects</a>
                    </div>
                </div>
                <div class="w-col w-col-4 w-col-stack">
                    <div class="showcase-card">
                        <h3 class="card-h3">Organisations</h3>
                        <p class="show-card-p">Organisations offer funding and support to both projects &amp;
                            individuals</p>
                        <a href="<?php echo URL::organisations() ?>" class="w-button card-btn">Check out our partner
                            organisations</a>
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
                    <p class="p-big cta-text-light">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse
                        varius enim in eros elementum tristique...</p>
                </div>
                <div class="w-col w-col-6 cta-column-right">
                    <a class="w-button dsi-button cta" href="#top">Add project +</a>
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
                <?php foreach ($stories AS $story) { ?>
                    <div class="w-col w-col-4 w-col-stack">
                        <a class="w-inline-block hp-post-link"
                           href="<?php echo URL::story($story->getId(), $story->getTitle()) ?>">
                            <div class="hp-post-img"
                                 style="background-image: url('<?php echo \DSI\Entity\Image::STORY_FEATURED_IMAGE_URL . $story->getFeaturedImage() ?>')"></div>
                            <div class="w-clearfix hp-post">
                                <div
                                    class="hp-post-meta category">
                                    <?php if ($story->getStoryCategoryId()) { ?>
                                        <?php echo $story->getStoryCategory()->getName() ?>
                                    <?php } ?>
                                </div>
                                <div class="hp-post-meta hp-date">
                                    <?php echo $story->getDatePublished('jS F Y') ?>
                                </div>
                                <h3 class="hp-post-h3"><?php echo show_input($story->getTitle()) ?></h3>
                                <p class="hp-post-p">
                                    <?php echo show_input(
                                        substr(
                                            strip_tags($story->getContent()), 0, 200
                                        )
                                    ) ?>...
                                </p>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>