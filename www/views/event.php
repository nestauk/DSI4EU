<?php
require __DIR__ . '/header.php'
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler \DSI\Service\URL */
/** @var $event \DSI\Entity\Event */
/** @var $userCanManageEvent bool */
?>
    <div ng-controller="EventController">

        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1"><?php echo show_input($event->getTitle()) ?></h1>
                    <div class="detail"><strong>Date</strong>: <?php echo show_input($event->getStartDate('jS F Y')) ?>
                    </div>
                    <div class="detail">
                        <?php if ($event->getPrice()) { ?>
                            <?php echo show_input($event->getPrice()) ?>
                        <?php } else { ?>
                            <strong>This event is free</strong>
                        <?php } ?>
                    </div>

                    <p class="intro"><?php echo nl2br(show_input($event->getShortDescription())) ?></p>
                    <div><?php echo $event->getDescription() ?></div>
                    <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                       href="<?php echo $event->getUrl() ?>">
                        <div class="login-li long menu-li readmore-li">Visit event website</div>
                        <img class="login-arrow"
                             src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                    </a>
                </div>
                <div class="sidebar w-col w-col-4 w-col-stack">
                    <?php if($userCanManageEvent) { ?>
                        <h1 class="content-h1 side-bar-space-h1">Actions</h1>
                        <a class="sidebar-link" href="<?php echo $urlHandler->eventEdit($event)?>"><span class="green">-&nbsp;</span>Edit event</a>
                        <?php /* <a class="sidebar-link"><span class="green">-&nbsp;</span>Publish / unpublish</a> */ ?>
                        <?php /* <a class="remove sidebar-link"><span class="green">-&nbsp;</span>Remove</a> */ ?>
                    <?php } ?>

                    <h1 class="content-h1 side-bar-space-h1">Events</h1>
                    <p>DSI events include everything from large conferences to small local hackathons. As long as it
                        focusses on how digital technologies can be used to address social challenges, we are interested
                        in listing it and sharing it with the rest of the community.</p>
                    <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                       href="<?php echo $urlHandler->events() ?>">
                        <div class="login-li long menu-li readmore-li">See all events</div>
                        <img class="login-arrow"
                             src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/EventController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>