<?php
require __DIR__ . '/header.php'
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler \DSI\Service\URL */
/** @var $userCanAddEvent bool */
?>
    <div ng-controller="EventsController"
         data-eventsjsonurl="<?php echo $urlHandler->eventsJson() ?>">

        <div class="container-wide content">
            <div class="w-row">
                <div class="w-col w-col-4 w-col-stack">
                    <div class="info-card">
                        <h1 class="card-h1">Events</h1>
                        <p class="card-p">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius
                            enim in eros elementum tristique</p>
                        <p class="card-p question">Have you got an event?</p>
                        <p class="card-p">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <div class="w-form">
                            <form id="email-form-2" name="email-form-2">
                                <h3 class="filter-h3">Search events</h3>
                                <input class="funding-filter w-input" data-name="Search" id="Search-2" maxlength="256"
                                       name="Search" placeholder="Search by keyword or content" required="required"
                                       ng-model="searchName"
                                       type="text">
                                <h3 class="filter-h3">Filter by:</h3>
                                <select class="funding-filter w-select" data-name="Country 6" id="country-6"
                                        name="country-6"
                                        ng-options="item as item.title for item in data.years track by item.id"
                                        ng-model="beforeYear">
                                </select>
                                <select class="funding-filter w-select" data-name="Country 5" id="country-5"
                                        name="country-5"
                                        ng-options="item as item.title for item in data.months track by item.id"
                                        ng-model="beforeMonth">
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="w-col w-col-8 w-col-stack">
                    <div class="info-card"
                         ng-repeat="event in data.events
                         | filter: searchName
                         | filter: earlierThan('' + beforeYear.id + beforeMonth.id)
                           track by event.id">
                        <h2 class="funding-card-h2" ng-bind="event.title"></h2>
                        <p class="funding-descr" ng-bind="event.shortDescription"></p>
                        <div class="funding-closing-date" ng-show="event.startDate">
                            <strong>Event date:</strong>
                            <span ng-bind="event.startDate"></span>
                        </div>
                        <a class="read-more w-button" href="{{event.url}}">Read more</a>
                        <?php if ($userCanAddEvent) { ?>
                            <a class="read-more w-button edit-funding" href="{{event.editUrl}}">Edit Event</a>
                        <?php } ?>
                        <div class="funding-country funding-new" ng-show="event.isNew">New event</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/EventsController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>