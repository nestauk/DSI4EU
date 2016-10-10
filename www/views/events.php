<?php
require __DIR__ . '/header.php'
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler \DSI\Service\URL */
/** @var $userCanAddEvent bool */
?>
    <div ng-controller="EventsController"
         data-eventsjsonurl="<?php echo $urlHandler->eventsJson() ?>">

        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1">Events</h1>
                    <p class="intro">Here you can find out about DSI events taking place across Europe.</p>
                    <p>DSI events include everything from large conferences to small local hackathons. As long as it
                        focusses on how digital technologies can be used to address social challenges, we are interested
                        in listing it and sharing it with the rest of the community.</p>
                </div>
                <div class="sidebar w-col w-col-4 w-col-stack">
                    <h1 class="content-h1 side-bar-space-h1">Add an event</h1>
                    <p>Are you organising a DSI event? Let us know and we will add it to the list.</p>
                    <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                       href="http://bit.ly/DSIEvent" target="_blank">
                        <div class="login-li long menu-li readmore-li">Add an event</div>
                        <img class="login-arrow"
                             src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                    </a>
                </div>
            </div>
        </div>

        <div class="content-directory">
            <div class="list-block">
                <div class="w-row">
                    <div class="w-col w-col-4">
                        <div class="filter-bar info-card">
                            <div class="w-form">
                                <form id="email-form" name="email-form">
                                    <h3 class="sidebar-h3">Filter events</h3>
                                    <div class="search-div">
                                        <input class="sidebar-search-field w-input" data-ix="hide-search-icon"
                                               data-name="Search 3" id="Search-3" maxlength="256" name="Search-3"
                                               placeholder="Search by keyword" type="text" ng-model="searchName">
                                        <img class="search-mag"
                                             src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-search.png">
                                    </div>
                                    <label class="dropdown-label" for="field">Choose between paid or free events</label>
                                    <select class="w-select" id="field" name="field" ng-model="searchFee">
                                        <option value="0">- All -</option>
                                        <option value="1">Paid</option>
                                        <option value="2">Free</option>
                                    </select>
                                    <?php /*
                                    <label class="dropdown-label" for="field-2">Event location</label>
                                    <select class="w-select" data-name="Field 2" id="field-2" name="field-2">
                                        <option value="location">Select</option>
                                        <option value="First">First Choice</option>
                                        <option value="Second">Second Choice</option>
                                        <option value="Third">Third Choice</option>
                                    </select>
                                    */ ?>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="w-col w-col-8">
                        <div class="info-card" data-ix="underline" ng-cloak
                             ng-repeat="event in data.events
                             | filter: searchName
                             | filter: hasFee(searchFee)
                               track by event.id">
                            <h2 class="funding-card-h2" ng-bind="event.title"></h2>
                            <div class="infocard top3-underline" data-ix="new-interaction-2"></div>
                            <div class="event-li-location" ng-show="event.region || event.country">
                                {{event.region}}<span ng-show="event.region && event.country">, {{event.country}}
                            </div>
                            <p class="funding-descr" ng-bind="event.shortDescription"></p>
                            <p class="funding-descr">
                                <span ng-show="event.price">
                                    {{event.price}}
                                </span>
                                <span ng-hide="event.price">
                                    This is a <strong>free</strong> event
                                </span>
                            </p>
                            <div class="funding-closing-date" ng-show="event.startDate">
                                <strong>Event date:</strong>
                                2016-09-26
                                <span ng-bind="event.startDate"></span>
                            </div>
                            <div class="funding-country funding-new" ng-show="event.isNew">New event</div>
                            <a class="infocard log-in-link read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                               href="{{event.viewUrl}}">
                                <div class="login-li menu-li readmore-li">Read more</div>
                                <img class="login-arrow"
                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/EventsController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>