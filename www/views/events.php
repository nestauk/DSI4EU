<?php
require __DIR__ . '/header.php'
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler Services\URL */
/** @var $userCanAddEvent bool */
/** @var $countries \DSI\Entity\Country[] */
?>
    <div ng-controller="EventsController"
         data-eventsjsonurl="<?php echo $urlHandler->eventsJson() ?>">

        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1"><?php _ehtml('Events') ?></h1>
                    <p class="intro"><?php _ehtml('Here you can find out about DSI events') ?></p>
                    <p><?php _ehtml('DSI events include everything from large conferences to small local hackathons.') ?></p>
                </div>
                <div class="sidebar w-col w-col-4 w-col-stack">
                    <h1 class="content-h1 side-bar-space-h1"><?php _ehtml('Add an event') ?></h1>
                    <p><?php _ehtml('Are you organising a DSI event?') ?></p>
                    <?php if ($userCanAddEvent) { ?>
                        <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                           href="<?php echo $urlHandler->addEvent() ?>">
                            <div class="login-li long menu-li readmore-li"><?php _ehtml('Add an event') ?></div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </a>
                    <?php } else { ?>
                        <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                           href="http://bit.ly/DSIEvent" target="_blank">
                            <div class="login-li long menu-li readmore-li"><?php _ehtml('Add an event') ?></div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </a>
                    <?php } ?>
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
                                    <h3 class="sidebar-h3"><?php _ehtml('Filter events') ?></h3>
                                    <div class="search-div">
                                        <input class="sidebar-search-field w-input" data-ix="hide-search-icon"
                                               data-name="Search 3" id="Search-3" maxlength="256" name="Search-3"
                                               placeholder="<?php _ehtml('Search by keyword') ?>" type="text"
                                               ng-model="searchName">
                                        <img class="search-mag"
                                             src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-search.png">
                                    </div>
                                    <?php /*
                                    <label class="dropdown-label" for="field">Choose between paid or free events</label>
                                    <select class="w-select" id="field" name="field" ng-model="searchFee">
                                        <option value="0">- All -</option>
                                        <option value="1">Paid</option>
                                        <option value="2">Free</option>
                                    </select>
                                    */ ?>
                                    <label class="dropdown-label"
                                           for="field-2"><?php _ehtml('Event location') ?></label>
                                    <select class="w-select" ng-model="searchCountryID">
                                        <option ng-repeat="country in data.countries" value="{{country.id}}">
                                            {{country.name}}
                                        </option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="w-col w-col-8">
                        <div ng-cloak ng-repeat="event in data.events
                             | filter: searchName
                             | filter: inCountry(searchCountryID)
                               track by event.id">
                            <a class="info-card" data-ix="underline" style="display:block" href="{{event.viewUrl}}">
                                <h2 class="funding-card-h2" ng-bind="event.title"></h2>
                                <div class="infocard top3-underline" data-ix="new-interaction-2"></div>
                                <div class="event-li-location" ng-show="event.region || event.country">
                                    {{event.region}}<span ng-show="event.region && event.country">, {{event.country}}
                                </div>
                                <p class="funding-descr" ng-bind="event.shortDescription"></p>
                                <p class="funding-descr">
                                    <span ng-show="event.price">
                                        <?php _ehtml('Cost') ?>: {{event.price}}
                                    </span>
                                    <span ng-hide="event.price">
                                        <?php _ehtml('This is a free event')?>
                                    </span>
                                </p>
                                <div class="funding-closing-date" ng-show="event.startDate">
                                    <strong><?php _ehtml('Event date')?>:</strong>
                                    <span ng-bind="event.startDate"></span>
                                </div>
                                <div class="funding-country funding-new" ng-show="event.isNew"><?php _ehtml('New event')?></div>
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