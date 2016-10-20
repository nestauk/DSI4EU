<?php
/** @var $urlHandler \DSI\Service\URL */
/** @var $user \DSI\Entity\User */
use \DSI\Service\Sysctl;

?>
<h1 class="content-h1 side-bar-space-h1">Your profile</h1>
<a class="sidebar-link" href="<?php echo $urlHandler->dashboard() ?>">
    <span class="green">-&nbsp;</span>Dashboard</a>
<a class="sidebar-link" href="<?php echo $urlHandler->profile($loggedInUser) ?>">
    <span class="green">-&nbsp;</span>View profile</a>
<a class="sidebar-link" href="<?php echo $urlHandler->editUserProfile($loggedInUser) ?>">
    <span class="green">-&nbsp;</span>Edit profile</a>
<a class="sidebar-link" href="<?php echo $urlHandler->logout() ?>">
    <span class="green">- Sign out</span></a>