<?php
/** @var $urlHandler Services\URL */
/** @var $user \DSI\Entity\User */
/** @var $loggedInUser \DSI\Entity\User */
use \DSI\Service\Sysctl;

?>
<h1 class="content-h1 side-bar-space-h1"><?php _e('Your profile')?></h1>
<a class="sidebar-link" href="<?php echo $urlHandler->dashboard() ?>">
    <span class="green">-&nbsp;</span><?php _e('Dashboard')?></a>
<a class="sidebar-link" href="<?php echo $urlHandler->profile($loggedInUser) ?>">
    <span class="green">-&nbsp;</span><?php _e('View profile')?></a>
<a class="sidebar-link" href="<?php echo $urlHandler->editUserProfile($loggedInUser) ?>">
    <span class="green">-&nbsp;</span><?php _e('Edit profile')?></a>
<a class="sidebar-link" href="<?php echo $urlHandler->logout() ?>">
    <span class="green">- <?php _e('Sign out')?></span></a>