<?php
require __DIR__ . '/../header.php'
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler \Services\URL */
/** @var $cluster \Models\Relationship\ClusterLang */
/** @var $canEdit bool */
?>
    <div class="cluster-controller">
        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1"><?php echo show_input($cluster->getTitle()) ?></h1>
                    <p class="intro">
                        <?php echo $cluster->getSubtitle() ?>
                    </p>
                    <div><?php echo $cluster->getDescription() ?></div>
                    <div><?php echo $cluster->getGetInTouch() ?></div>
                    <?php foreach ($cluster->getImages() AS $image) { ?>
                        <div class="cluster-image">
                            <a href="<?= $image->getLink() ?>" target="_blank">
                                <img src="<?= \DSI\Entity\Image::UPLOAD_FOLDER_URL . $image->getFilename() ?>"/>
                            </a>
                            <br><br>
                            <?= $image->getCaption() ?>
                            <br><br>
                        </div>
                    <?php } ?>
                    <div style="clear: both;"></div>
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