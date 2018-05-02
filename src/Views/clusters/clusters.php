<?php
require __DIR__ . '/../header.php'
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler Services\URL */
/** @var $clusters \Models\ClusterLang[] */
?>
    <div>
        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1"><?php _ehtml('Clusters') ?></h1>
                    <p class="intro"><?php _ehtml('Here you can find out about DSI clusters') ?></p>
                    <p><?php _ehtml('DSI clusters include everything from large conferences to small local hackathons.') ?></p>
                </div>
            </div>
        </div>

        <div class="content-directory">
            <div class="list-block">
                <div class="w-row">
                    <div class="w-col w-col-12">
                        <?php foreach ($clusters AS $cluster) { ?>
                            <div>
                                <a class="info-card" data-ix="underline"
                                   href="<?= $urlHandler->cluster($cluster->getClusterId()) ?>">
                                    <h2 class="funding-card-h2">
                                        <?php _ehtml($cluster->getTitle()) ?>
                                    </h2>
                                    <div class="infocard top3-underline" data-ix="new-interaction-2"></div>
                                    <p class="funding-descr">
                                        <?php _ehtml($cluster->getDescription()) ?>
                                    </p>
                                    <p class="funding-descr">
                                        <?php _ehtml($cluster->getGetInTouch()) ?>
                                    </p>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/../footer.php' ?>