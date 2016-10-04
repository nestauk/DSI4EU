<?php
/** @var $loggedInUser \DSI\Entity\User */
use \DSI\Service\URL;

if (!isset($urlHandler))
    $urlHandler = new URL();
?>

    <div class="menu-full-screen" data-ix="displaynone">
        <div class="main-menu">
            <div class="main-menu-profile-block w-clearfix">
                <?php if (isset($loggedInUser) AND $loggedInUser) { ?>
                    <div class="profile-img"
                         style="background-image: url('<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $loggedInUser->getProfilePicOrDefault() ?>');"></div>
                    <h3 class="profile-name"><?php echo show_input($loggedInUser->getFullName()) ?></h3>
                    <h3 class="profile-name profile-organisation"><?php echo show_input($loggedInUser->getCompany()) ?></h3>
                    <div class="profile-options">
                        <div class="profile-options w-row">
                            <div class="profile-col w-col w-col-3">
                                <a href="<?php echo $urlHandler->dashboard() ?>">View dashboard</a>
                            </div>
                            <div class="profile-col w-col w-col-3">
                                <a href="<?php echo $urlHandler->myProfile() ?>">View profile</a>
                            </div>
                            <div class="profile-col w-col w-col-3">
                                <a href="<?php echo $urlHandler->editProfile() ?>">Edit profile</a>
                            </div>
                            <div class="profile-col w-col w-col-3">
                                <a href="<?php echo $urlHandler->logout() ?>">Sign out</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="full-menu-items w-row">
                <div class="full-menu-col w-clearfix w-col w-col-4">
                    <div class="full-menu-links-block no-border">
                        <h2 class="full-menu-h2">About DSI</h2>
                        <a class="full-menu-link" href="<?php echo $urlHandler->aboutTheProject() ?>">
                            About the project
                        </a>
                        <a class="full-menu-link" href="<?php echo $urlHandler->partners() ?>">
                            Partners
                        </a>
                        <a class="full-menu-link" href="<?php echo $urlHandler->openDataResearchAndResources() ?>">
                            Open data, research &amp; resources</a>
                        <a class="full-menu-link" href="<?php echo $urlHandler->contactDSI() ?>">
                            Contact DSI
                        </a>
                    </div>
                </div>
                <div class="full-menu-col w-col w-col-4">
                    <h2 class="full-menu-h2"><strong><?php echo number_format($projectsCount) ?></strong> Projects</h2>
                    <a class="full-menu-link" href="<?php echo $urlHandler->projects() ?>">View all projects&nbsp;</a>
                    <a class="full-menu-link" href="<?php echo $urlHandler->caseStudies() ?>">Case studies</a>
                </div>
                <div class="full-menu-col w-col w-col-4">
                    <h2 class="full-menu-h2">
                        <strong><?php echo number_format($organisationsCount) ?></strong>
                        Organisations
                    </h2>
                    <a class="full-menu-link" href="<?php echo $urlHandler->organisations() ?>">
                        View <span class="stat-number">all</span> organisations
                    </a>
                </div>
            </div>
            <div class="full-menu-items w-row">
                <div class="full-menu-col w-clearfix w-col w-col-4">
                    <h2 class="full-menu-h2">Funding &amp; Support</h2>
                    <div class="full-menu-links-block no-border">
                        <a class="full-menu-link" href="<?php echo $urlHandler->funding() ?>">See all funding
                            opportunities</a>
                    </div>
                </div>
                <div class="full-menu-col w-clearfix w-col w-col-4">
                    <h2 class="full-menu-h2">Events</h2>
                    <div class="full-menu-links-block no-border">
                        <a class="full-menu-link" href="<?php echo $urlHandler->events() ?>">See upcoming DSI events</a>
                    </div>
                </div>
                <div class="full-menu-col w-clearfix w-col w-col-4">
                    <h2 class="full-menu-h2">News &amp; blogs</h2>
                    <div class="full-menu-links-block no-border">
                        <a class="full-menu-link" href="<?php echo $urlHandler->blogPosts() ?>">
                            See all news &amp; blogs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="navbarnu w-nav" data-animation="over-left" data-collapse="all" data-duration="400">
        <a class="w-nav-brand" href="<?php echo $urlHandler->home() ?>">
            <img class="brand-logo" src="<?php echo SITE_RELATIVE_PATH ?>/images/dark_1.svg">
        </a>
        <a class="m-brand w-nav-brand" href="<?php echo $urlHandler->home() ?>">
            <img class="brand-logo m-brand" src="<?php echo SITE_RELATIVE_PATH ?>/images/shadowlight.png">
        </a>
        <div class="menu-button w-nav-button" data-ix="navbarinteraction">
            <div class="top-line"></div>
            <div class="middle-line"></div>
            <div class="bottom-line"></div>
            <div class="menu-li">Menu</div>
        </div>
        <a class="ll log-in-link w-clearfix w-inline-block" data-ix="log-in-arrow"
           href="<?php echo $urlHandler->login() ?>">
            <div class="login-li menu-li">login</div>
            <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
        </a>
        <a class="log-in-link menu-search w-clearfix w-inline-block" data-ix="search-roll" href="#">
            <div class="menu-li menu-search">Search</div>
            <img class="search-icon" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-search.png">
        </a>
    </div>

<?php /*
<div class="alt nav-main w-nav white-menu" data-animation="default" data-collapse="medium" data-duration="400">
    <a class="w-nav-brand" href="<?php echo $urlHandler->home() ?>">
        <img class="logo-dark" src="<?php echo SITE_RELATIVE_PATH ?>/images/dark.svg">
        <div class="beta-badge">Beta</div>
    </a>
    <nav class="nav-menu w-nav-menu" role="navigation">
        <?php /* <a class="alt nav w-nav-link" href="<?php echo URL::exploreDSI() ?>">Explore DSI</a> * / ?>
        <?php if ($loggedInUser) { ?>
            <a class="alt nav w-nav-link"
               href="<?php echo $urlHandler->dashboard() ?>"><?php _ehtml('Dashboard'); ?></a>
        <?php } ?>
        <a class="alt nav w-nav-link"
           href="<?php echo $urlHandler->caseStudies() ?>"><?php _ehtml('Case Studies'); ?></a>
        <a class="alt nav w-nav-link" href="<?php echo $urlHandler->blogPosts() ?>"><?php _ehtml('Blog'); ?></a>
        <div class="stat-nav">
            <a class="alt nav" href="<?php echo $urlHandler->projects() ?>"><?php _ehtml('Projects') ?></a>
            <div class="stats" data-ix="showprojectstatsinfo"><?php echo number_format($projectsCount) ?></div>
            <div class="project-stat stat-info" data-ix="hide-stat">
                <div class="close-box" data-ix="closeprojectsstatinfo">+</div>
                <h2 class="stat-h2">
                    <?php echo number_format($projectsCount) ?>
                    <?php _ehtml('Projects') ?>
                </h2>
                <p><?php echo show_input(sprintf(__('There are %s DSI projects'), number_format($projectsCount))) ?></p>
                <p><?php _ehtml('Projects use digitalsocial.eu to map their collaborators') ?></p>
                <a class="stat-link" data-ix="closeprojectsstatinfo" href="<?php echo $urlHandler->projects() ?>">
                    <p><?php _ehtml('VIEW PROJECTS (UC)') ?></p>
                </a>
            </div>
        </div>
        <div class="stat-nav">
            <a class="alt nav" href="<?php echo $urlHandler->organisations() ?>"><?php _ehtml('Organisations') ?></a>
            <div class="stats"
                 data-ix="showprojectstatsinfo-2"><?php echo number_format($organisationsCount) ?></div>
            <div class="org-stat stat-info" data-ix="closeprojectsstatinfo-2">
                <div class="close-box" data-ix="closeprojectsstatinfo-2">+</div>
                <h2 class="stat-h2">
                    <?php echo number_format($organisationsCount) ?>
                    <?php _ehtml('Organisations') ?>
                </h2>
                <p><?php echo show_input(sprintf(__('There are %s DSI organisations'), number_format($organisationsCount))) ?></p>
                <p><?php _ehtml('Organisations use digitalsocial.eu to map their collaborators') ?></p>
                <a data-ix="closeprojectsstatinfo-2" href="<?php echo $urlHandler->organisations() ?>">
                    <p><?php _ehtml('VIEW ORGANISATIONS (UC)') ?></p>
                </a>
            </div>
        </div>
        <?php if ($loggedInUser) { ?>
            <div class="w-dropdown" data-delay="0">
                <div class="alt log-in log-in-alt nav w-nav-link w-dropdown-toggle">
                    <div><?php _ehtml('Create') ?></div>
                </div>
                <nav class="create-drop-down w-dropdown-list">
                    <a class="drop-down-link w-dropdown-link" data-ix="create-project-modal" href="#">
                        <?php _ehtml('Create a new project') ?></a>
                    <a class="drop-down-link w-dropdown-link" data-ix="create-organisation-modal" href="#">
                        <?php _ehtml('Create an organisation') ?></a>
                    <div class="arror-up"></div>
                </nav>
            </div>
        <?php } else { ?>
            <a class="alt log-in nav w-nav-link white-alt" data-ix="open-login-modal" href="#">
                <?php _ehtml('Login') ?></a>
            <a class="alt log-in log-in-alt nav w-nav-link" data-ix="showsignup" href="#">
                <?php _ehtml('Signup') ?></a>
        <?php } ?>
    </nav>
    <div class="w-nav-button">
        <div class="w-icon-nav-menu"></div>
    </div>
</div>
*/ ?>