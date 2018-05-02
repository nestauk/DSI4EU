<?php
require __DIR__ . '/header.php'
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler \Services\URL */
/** @var $cluster \Models\ClusterLang */
/** @var $userCanManageEvent bool */
?>
    <div>
        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1"><?php echo show_input($cluster->getTitle()) ?></h1>
                    <div class="detail">
                        <strong><?php _ehtml('Date') ?></strong>:
                    </div>
                    <div class="detail">

                    </div>

                    <div><?php echo $cluster->getDescription() ?></div>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>