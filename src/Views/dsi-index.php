<?php
/** @var $urlHandler Services\URL */
/** @var $title \Models\Text */
/** @var $content \Models\Text */
/** @var $canEdit bool */

require __DIR__ . '/header.php';
?>
<div class="dsi-index-controller">
    <div class="content-block">
        <div class="w-row">
            <div class="w-col w-col-8">
                <h1 class="content-h1"><?php _html($title->getCopy()) ?></h1>
                <div class="p-head">
                    <?= $content->getCopy() ?>
                </div>
            </div>
            <div class="sidebar w-col w-col-4 w-col-stack">
                <?php require __DIR__ . '/partialViews/about-dsi.php' ?>
                <?php if ($canEdit) { ?>
                    <a class="sidebar-link" href="<?php echo $urlHandler->dsiIndexEdit() ?>">
                        <span class="green">-&nbsp;</span>
                        <span class="green"><?php _ehtml('Edit page') ?></span>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/footer.php' ?>
