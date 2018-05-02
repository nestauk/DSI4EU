<?php
require __DIR__ . '/../header.php'
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler \Services\URL */
/** @var $cluster \Models\ClusterLang */
/** @var $canEdit bool */
?>
    <div>
        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1"><?php echo show_input($cluster->getTitle()) ?></h1>
                    <div><?php echo $cluster->getDescription() ?></div>
                    <div><?php echo $cluster->getGetInTouch() ?></div>
                </div>
                <?php if ($canEdit) { ?>
                    <div class="sidebar w-col w-col-4 w-col-stack">
                        <h1 class="content-h1 side-bar-space-h1">Actions</h1>
                        <a class="sidebar-link"
                           href="<?php echo $urlHandler->clusterEdit($cluster->getClusterId()) ?>">
                            <span class="green">- </span>Edit cluster</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/../footer.php' ?>