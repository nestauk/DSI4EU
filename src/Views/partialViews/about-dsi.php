<?php
/** @var $urlHandler Services\URL */

use \DSI\Service\Sysctl;

?>

<h1 class="content-h1"><?php _ehtml('About DSI4EU') ?></h1>
<a class="sidebar-link" href="<?php echo $urlHandler->aboutTheProject() ?>">
    <span class="green">-&nbsp;</span>
    <?php _ehtml('About the project') ?>
</a>
<a class="sidebar-link" href="<?php echo $urlHandler->partners() ?>">
    <span class="green">-&nbsp;</span><?php _ehtml('Partners') ?>
</a>
<a class="sidebar-link" href="<?php echo $urlHandler->openDataResearchAndResources() ?>">
    <span class="green">-&nbsp;</span><?php _ehtml('Open data, research & resources') ?>
</a>
<a class="sidebar-link" href="<?php echo $urlHandler->contactDSI() ?>">
    <span class="green">-&nbsp;</span><?php _ehtml('Contact DSI4EU') ?>
</a>
