<?php
/** @var $urlHandler \DSI\Service\URL */
use \DSI\Service\Sysctl;

?>
<div class="sidebar w-col w-col-4 w-col-stack">
    <h1 class="content-h1">About DSI</h1>
    <a class="sidebar-link" href="<?php echo $urlHandler->aboutTheProject() ?>">
        <span class="green">-&nbsp;</span>About the project
    </a>
    <a class="sidebar-link" href="<?php echo $urlHandler->partners() ?>">
        <span class="green">-&nbsp;</span>Partners
    </a>
    <a class="sidebar-link" href="<?php echo $urlHandler->openDataResearchAndResources() ?>">
        <span class="green">-&nbsp;</span>Open data, research &amp; resources
    </a>
    <a class="sidebar-link" href="<?php echo $urlHandler->contactDSI() ?>">
        <span class="green">-&nbsp;</span>Contact DSI
    </a>
</div>