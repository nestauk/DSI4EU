<?php
/** @var $urlHandler \DSI\Service\URL */
?>
<div class="stat-text w-row">
    <div class="w-col w-col-5">
        <div class="number-of-orgs"
             data-ix="fadeinuponload-3"><?php echo number_format($organisationsCount, 0, ',', '.') ?></div>
        <a class="organisations-2" data-ix="fadeinuponload-4" href="<?php echo $urlHandler->organisations() ?>">Organizaciones</a>
    </div>
    <div class="w-col w-col-2">
        <div class="have-collab" data-ix="fadeinuponload-5">han colaborado en</div>
    </div>
    <div class="w-col w-col-5">
        <div class="number-of-orgs pro"
             data-ix="fadeinuponload-6"><?php echo number_format($projectsCount, 0, ',', '.') ?></div>
        <a class="organisations-2" data-ix="fadeinuponload-7" href="<?php echo $urlHandler->projects() ?>">Proyectos</a>
    </div>
</div>