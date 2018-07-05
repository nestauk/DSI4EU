<?php
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler Services\URL */
/** @var $clusters \Models\Relationship\ClusterLang[] */
require __DIR__ . '/../header.php' ?>

    <div class="clusters-controller">
        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1"><?php _ehtml('DSI Clusters') ?></h1>
                    <p class="intro"><?php _ehtml('Supporting DSI in different social areas') ?></p>
                    <?php // _ehtml('DSI clusters include everything from large conferences to small local hackathons.') ?>
                    <p>
                        For digital social innovation to achieve its goals, we believe it’s essential to always put
                        social challenges first. As with any social innovation, success is dependent on having a deep,
                        nuanced understanding of social challenges. For that reason, DSI4EU’s activities are focused
                        around six “DSI clusters”, each led by a project partner and focusing on a specific social
                        social area. Over the course of the project, each cluster will organise series of peer learnig
                        events, conduct cluster-specific research, engage with policymakers working in their field, and
                        act as a go-to point of reference for people working on DSI in the field.
                    </p>
                    <p>
                        Find out more about each of the clusters below and click through on any one for more
                        information.
                    </p>
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
                                   href="<?= $urlHandler->cluster($cluster) ?>">
                                    <h2 class="funding-card-h2">
                                        <?php _ehtml($cluster->getTitle()) ?>
                                    </h2>
                                    <div class="infocard top3-underline" data-ix="new-interaction-2"></div>
                                    <div class="funding-descr">
                                        <?php _e($cluster->getParagraph()) ?>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/../footer.php' ?>