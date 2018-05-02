<?php
use Services\URL;
use \DSI\Service\Sysctl;

/** @var $loggedInUser \DSI\Entity\User */
/** @var $isIndexPage bool */
/** @var $angularModules string[] */
/** @var $pageTitle string[] */

$projectsCount = (new \DSI\Repository\ProjectRepoInAPC())->countAll();
$organisationsCount = (new \DSI\Repository\OrganisationRepoInAPC())->countAll();

if (!isset($loggedInUser))
    $loggedInUser = null;
if (!isset($urlHandler))
    $urlHandler = new URL();

?><!DOCTYPE html>
<html data-wf-site="56e2e31a1b1f8f784728a08c" data-wf-page="56fbef6ecf591b312d56f8be">
<head>
    <?php require __DIR__ . '/partialViews/head.php' ?>
</head>
<body ng-app="DSIApp">

<?php

require(__DIR__ . '/partialViews/header.php');

if ($loggedInUser)
    require __DIR__ . '/partialViews/createProjectAndOrganisation.php';
else
    require __DIR__ . '/partialViews/loginModal.php';

?>

<?php /*
<?php if (!isset($hideSearch) OR $hideSearch !== true) { ?>
    <div class="search bg-blur">
        <div class="dark-bg-overlay"></div>
        <div class="search-container">
            <div class="w-row top-row-personal">
                <div class="w-col w-col-5 w-col-small-5 w-clearfix" id="userMenu">
                    <?php if (isset($loggedInUser) AND $loggedInUser) { ?>
                        <div class="profile-popover bg-blur">
                            <a href="<?php echo $urlHandler->myProfile() ?>" class="popover-link">
                                <?php _ehtml('View profile') ?>
                            </a>
                            <a href="<?php echo $urlHandler->editProfile() ?>" class="popover-link">
                                <?php _ehtml('Edit Profile') ?>
                            </a>
                            <a href="<?php echo $urlHandler->logout() ?>" class="popover-link">
                                <?php _ehtml('Sign out') ?>
                            </a>
                        </div>
                        <img width="15" src="<?php echo SITE_RELATIVE_PATH ?>/images/vertical-nav.png"
                             data-ix="showpopover"
                             class="vert-nav">
                        <a href="<?php echo $urlHandler->myProfile() ?>"
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
                                   maxlength="256" name="Search" type="text"
                                   placeholder="<?php _ehtml('Search digitalsocial.eu') ?>"
                                   ng-model="search.entry" ng-focus="search.focused = true"
                                   ng-blur="search.focused = false">
                            <div ng-cloak ng-show="search.entry.length > 0" class="search-clear"
                                 ng-click="search.entry = ''"><?php _ehtml('clear') ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
*/ ?>

<div class="w-section body">