<?php
if (!isset($loggedInUser))
    $loggedInUser = null;
/** @var $loggedInUser \DSI\Entity\User */
/** @var $isHomePage bool */
/** @var $angularModules string[] */
/** @var $pageTitle string[] */
use \DSI\Service\URL;
use \DSI\Service\Sysctl;

$projectsCount = (new \DSI\Repository\ProjectRepositoryInAPC())->countAll();
$organisationsCount = (new \DSI\Repository\OrganisationRepositoryInAPC())->countAll();

?>
    <!DOCTYPE html>
    <html data-wf-site="56e2e31a1b1f8f784728a08c" data-wf-page="56fbef6ecf591b312d56f8be">
    <head>
        <?php require __DIR__ . '/partialViews/head.php' ?>
    </head>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/SearchController.js?<?php Sysctl::echoVersion() ?>"></script>
<body ng-app="DSIApp" ng-controller="SearchController" id="top">

    <div class="alt nav-main w-nav white-menu" data-animation="default" data-collapse="medium" data-duration="400">
        <a class="w-nav-brand" href="<?php echo URL::home() ?>">
            <img class="logo-dark" src="<?php echo SITE_RELATIVE_PATH ?>/images/dark.svg">
            <div class="beta-badge">Beta</div>
        </a>
        <nav class="nav-menu w-nav-menu" role="navigation">
            <?php /* <a class="alt nav w-nav-link" href="<?php echo URL::exploreDSI() ?>">Explore DSI</a> */ ?>
            <?php if ($loggedInUser) { ?>
                <a class="alt nav w-nav-link" href="<?php echo URL::dashboard() ?>">Dashboard</a>
            <?php } ?>
            <a class="alt nav w-nav-link" href="<?php echo URL::caseStudies() ?>">Case Studies</a>
            <a class="alt nav w-nav-link" href="<?php echo URL::blogPosts() ?>">Blog</a>
            <div class="stat-nav">
                <a class="alt nav" href="<?php echo URL::projects() ?>">Projects</a>
                <div class="stats" data-ix="showprojectstatsinfo"><?php echo number_format($projectsCount) ?></div>
                <div class="project-stat stat-info" data-ix="hide-stat">
                    <div class="close-box" data-ix="closeprojectsstatinfo">+</div>
                    <h2 class="stat-h2"><?php echo number_format($projectsCount) ?> Projects</h2>
                    <p>There are <?php echo number_format($projectsCount) ?> DSI projects registered.</p>
                    <p>
                        Projects use digitalsocial.eu to map their collaborators, showcase their work and
                        demonstrate their impact.
                    </p>
                    <a class="stat-link" data-ix="closeprojectsstatinfo" href="<?php echo URL::projects() ?>">VIEW
                        PROJECTS</a>
                </div>
            </div>
            <div class="stat-nav">
                <a class="alt nav" href="<?php echo URL::organisations() ?>">Organisations</a>
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
                    <a data-ix="closeprojectsstatinfo-2" href="<?php echo URL::organisations() ?>">VIEW
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

<?php if ($loggedInUser) { ?>
    <?php require __DIR__ . '/partialViews/createProjectAndOrganisation.php' ?>
<?php } else { ?>
    <?php require __DIR__ . '/partialViews/loginModal.php' ?>
<?php } ?>

<?php if (!isset($hideSearch) OR $hideSearch !== true) { ?>
    <div class="search bg-blur">
        <div class="dark-bg-overlay"></div>
        <div class="search-container">
            <div class="w-row top-row-personal">
                <div class="w-col w-col-5 w-col-small-5 w-clearfix" id="userMenu">
                    <?php if (isset($loggedInUser)) { ?>
                        <div class="profile-popover bg-blur">
                            <?php /*
                            <a href="<?php echo URL::dashboard() ?>" class="popover-link">
                                My dashboard
                            </a>
                            */ ?>
                            <a href="<?php echo URL::myProfile() ?>" class="popover-link">
                                View profile
                            </a>
                            <a href="<?php echo URL::editProfile() ?>" class="popover-link">
                                Edit Profile
                            </a>
                            <a href="<?php echo URL::logout() ?>" class="popover-link">
                                Sign out
                            </a>
                        </div>
                        <img width="15" src="<?php echo SITE_RELATIVE_PATH ?>/images/vertical-nav.png"
                             data-ix="showpopover"
                             class="vert-nav">
                        <a href="<?php echo SITE_RELATIVE_PATH ?>/my-profile"
                           class="w-inline-block w-clearfix link-to-profile">
                            <div class="profile-img"
                                 style="background-image: url('<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $loggedInUser->getProfilePicOrDefault() ?>');">

                            </div>
                            <h3 class="profile-name">
                                <?php echo $loggedInUser->getFirstName() ?>
                                <?php echo $loggedInUser->getLastName() ?>
                            </h3>
                            <h3 class="profile-name profile-organisation"><?php echo $loggedInUser->getJobTitle() ?></h3>
                        </a>
                    <?php } ?>
                </div>
                <div class="w-col w-col-7 w-col-small-7">
                    <div class="w-form">
                        <form class="w-clearfix search-input" id="email-form">
                            <input class="w-input search-field quicksearch" id="Search"
                                   maxlength="256" name="Search" placeholder="Search digitalsocial.eu" type="text"
                                   ng-model="search.entry" ng-focus="search.focused = true"
                                   ng-blur="search.focused = false">
                            <div ng-cloak ng-show="search.entry.length > 0" class="search-clear"
                                 ng-click="search.entry = ''">clear
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

    <div class="w-section body">

<?php require(__DIR__ . '/partialViews/search-results.php'); ?>