<?php
/** @var $urlHandler \DSI\Service\URL */
use \DSI\Service\Sysctl;

?>
<div class="sidebar w-col w-col-4 w-col-stack">
    <h1 class="content-h1">About DSI4EU</h1>
    <a class="sidebar-link" href="<?php echo $urlHandler->aboutTheProject() ?>" data-ix="fadeinuponload-3">
        <span class="green">-&nbsp;</span>About the project
    </a>
    <a class="sidebar-link" href="<?php echo $urlHandler->partners() ?>" data-ix="fadeinuponload-4">
        <span class="green">-&nbsp;</span>Partners
    </a>
    <a class="sidebar-link" href="<?php echo $urlHandler->openDataResearchAndResources() ?>" data-ix="fadeinuponload-5">
        <span class="green">-&nbsp;</span>Open data, research &amp; resources
    </a>
    <a class="sidebar-link" href="<?php echo $urlHandler->contactDSI() ?>" data-ix="fadeinuponload-6">
        <span class="green">-&nbsp;</span>Contact DSI4EU
    </a>
</div>