<?php
require __DIR__ . '/header.php'
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler \DSI\Service\URL */
/** @var $event \DSI\Entity\Event */
?>
    <div ng-controller="EventController">

        <div class="container-wide content">
            <div class="w-row">
                <div class="w-col w-col-4 w-col-stack">
                    <div class="info-card">
                        <h1 class="card-h1">Events</h1>
                        <p class="card-p">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius
                            enim in eros elementum tristique</p>
                        <a class="back-link" href="<?php echo $urlHandler->events() ?>">&lt;&nbsp;Back to all events</a>
                    </div>
                </div>
                <div class="w-col w-col-8 w-col-stack">
                    <div class="info-card">
                        <h2 class="funding-card-h2"><?php echo show_input($event->getTitle()) ?></h2>
                        <div class="funding-closing-date">
                            <strong>Date:</strong><?php echo $event->getStartDate('jS F Y') ?></div>
                        <div class="funding-closing-date"><strong>Type of event:</strong> {event.type}}</div>
                        <p class="funding-descr"><?php echo nl2br(show_input($event->getShortDescription())) ?></p>
                        <p class="funding-descr">
                            <?php echo $event->getDescription() ?>
                        </p>
                        <div class="funding-closing-date"><strong>Contact number:</strong> {event.number}}</div>
                        <div class="funding-closing-date"><strong>Contact email:</strong> {event.email}}</div>
                        <a class="read-more w-button" href="<?php echo $event->getUrl() ?>" target="_blank">
                            Read more
                        </a>
                        <?php if ($event->isNew()) { ?>
                            <div class="funding-country funding-new">New event published</div>
                        <?php } ?>
                        <div class="full funding-country">Nesta,
                            <br>1 Plough Place,
                            <br>London,
                            <br>EC4A 1DE
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/EventController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>