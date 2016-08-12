<?php
use \DSI\Service\URL;

if (!isset($urlHandler))
    $urlHandler = new URL();
?>
<div class="alt nav-main w-nav white-menu" data-animation="default" data-collapse="medium" data-duration="400">
    <a class="w-nav-brand" href="<?php echo $urlHandler->home() ?>">
        <img class="logo-dark" src="<?php echo SITE_RELATIVE_PATH ?>/images/dark.svg">
        <div class="beta-badge">Beta</div>
    </a>
    <nav class="nav-menu w-nav-menu" role="navigation">
        <?php /* <a class="alt nav w-nav-link" href="<?php echo URL::exploreDSI() ?>">Explore DSI</a> */ ?>
        <?php if ($loggedInUser) { ?>
            <a class="alt nav w-nav-link"
               href="<?php echo $urlHandler->dashboard() ?>"><?php _ehtml('Dashboard'); ?></a>
        <?php } ?>
        <a class="alt nav w-nav-link" href="<?php echo URL::caseStudies() ?>"><?php _ehtml('Case Studies'); ?></a>
        <a class="alt nav w-nav-link" href="<?php echo URL::blogPosts() ?>"><?php _ehtml('Blog'); ?></a>
        <div class="stat-nav">
            <a class="alt nav" href="<?php echo $urlHandler->projects() ?>">Projects</a>
            <div class="stats" data-ix="showprojectstatsinfo"><?php echo number_format($projectsCount) ?></div>
            <div class="project-stat stat-info" data-ix="hide-stat">
                <div class="close-box" data-ix="closeprojectsstatinfo">+</div>
                <h2 class="stat-h2"><?php echo number_format($projectsCount) ?> Projects</h2>
                <p>There are <?php echo number_format($projectsCount) ?> DSI projects registered.</p>
                <p>
                    Projects use digitalsocial.eu to map their collaborators, showcase their work and
                    demonstrate their impact.
                </p>
                <a class="stat-link" data-ix="closeprojectsstatinfo" href="<?php echo $urlHandler->projects() ?>">VIEW
                    PROJECTS</a>
            </div>
        </div>
        <div class="stat-nav">
            <a class="alt nav" href="<?php echo $urlHandler->organisations() ?>">Organisations</a>
            <div class="stats"
                 data-ix="showprojectstatsinfo-2"><?php echo number_format($organisationsCount) ?></div>
            <div class="org-stat stat-info" data-ix="closeprojectsstatinfo-2">
                <div class="close-box" data-ix="closeprojectsstatinfo-2">+</div>
                <h2 class="stat-h2"><?php echo number_format($organisationsCount) ?> Organisations</h2>
                <p>There are <?php echo number_format($organisationsCount) ?> DSI organisations registered.</p>
                <p>
                    Organisations use digitalsocial.eu to map their projects, find new collaborators and funding
                    opportunities. Funding organisations also use it to find projects and people to fund.
                </p>
                <a data-ix="closeprojectsstatinfo-2" href="<?php echo $urlHandler->organisations() ?>">VIEW
                    ORGANISATIONS</a>
            </div>
        </div>
        <?php if ($loggedInUser) { ?>
            <div class="w-dropdown" data-delay="0">
                <div class="alt log-in log-in-alt nav w-nav-link w-dropdown-toggle">
                    <div>Create</div>
                </div>
                <nav class="create-drop-down w-dropdown-list">
                    <a class="drop-down-link w-dropdown-link" data-ix="create-project-modal" href="#">
                        Create a new project</a>
                    <a class="drop-down-link w-dropdown-link" data-ix="create-organisation-modal" href="#">
                        Create an organisation
                    </a>
                    <div class="arror-up"></div>
                </nav>
            </div>
        <?php } else { ?>
            <a class="alt log-in nav w-nav-link white-alt" data-ix="open-login-modal" href="#">Login</a>
            <a class="alt log-in log-in-alt nav w-nav-link" data-ix="showsignup" href="#">Signup</a>
        <?php } ?>
    </nav>
    <div class="w-nav-button">
        <div class="w-icon-nav-menu"></div>
    </div>
</div>