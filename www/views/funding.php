<?php
require __DIR__ . '/header.php'
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler \DSI\Service\URL */
/** @var $userCanAddFunding bool */
/** @var $fundingTypes \DSI\Entity\FundingType[] */
/** @var $fundingTargets \DSI\Entity\FundingTarget[] */
?>
    <div ng-controller="FundingController"
         data-fundingjsonurl="<?php echo $urlHandler->fundingJson() ?>">

        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1"><?php _ehtml('Funding & support') ?></h1>
                    <p class="intro"><?php _ehtml('One of the biggest challenges to developing and growing DSI') ?></p>
                    <p><?php _ehtml('To find the opportunities that best fit with what you are looking for') ?></p>
                </div>
                <div class="sidebar w-col w-col-4 w-col-stack">
                    <h1 class="content-h1 side-bar-space-h1"><?php _ehtml('Funding to offer?') ?></h1>
                    <p><?php _ehtml('Are you a funder that provides grants, investment or social investment for DSI projects and organisations?') ?></p>

                    <?php if ($userCanAddFunding) { ?>
                        <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                           href="<?php echo $urlHandler->addFunding() ?>">
                            <div class="login-li long menu-li readmore-li"><?php _ehtml('Add funding') ?></div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </a>
                    <?php } else { ?>
                        <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                           href="https://docs.google.com/forms/d/e/1FAIpQLSd8V-vyQADRo_ofvc5n49CBB-qeEgMlymLgQ6EUTJJWLD7DkQ/viewform">
                            <div class="login-li long menu-li readmore-li"><?php _ehtml('Submit funding') ?></div>
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
                                    <h3 class="sidebar-h3"><?php _ehtml('Filter funding opportunities') ?></h3>
                                    <div class="search-div">
                                        <input class="sidebar-search-field w-input" data-ix="hide-search-icon"
                                               data-name="Search 4" id="Search-4" maxlength="256" name="Search-4"
                                               placeholder="<?php _ehtml('Search by keyword, type or project') ?>"
                                               type="text" ng-model="searchName">
                                        <img class="search-mag"
                                             src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-search.png">
                                    </div>

                                    <label class="dropdown-label"
                                           for="field-3"><?php _ehtml('Type of funding & support') ?></label>
                                    <select class="w-select" ng-model="fundingType">
                                        <option value="0">- <?php _ehtml('All') ?> -</option>
                                        <?php foreach ($fundingTypes AS $type) { ?>
                                            <option value="<?php echo $type->getId() ?>">
                                                <?php echo show_input($type->getTitle()) ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                    <label class="dropdown-label" for="field-4"><?php _ehtml('Target organisations & projects')?></label>
                                    <select class="w-select" ng-model="fundingTarget">
                                        <option value="0">- <?php _ehtml('All')?> -</option>
                                        <?php foreach ($fundingTargets AS $target) { ?>
                                            <option value="<?php echo $target->getId() ?>">
                                                <?php echo show_input($target->getTitle()) ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                    <label class="dropdown-label" for="field-6"><?php _ehtml('Country')?></label>
                                    <select class="w-select"
                                            ng-options="item as item.title for item in data.countries track by item.id"
                                            ng-model="inCountry">
                                    </select>

                                    <label class="dropdown-label" for="field-5"><?php _ehtml('Closing date')?></label>
                                    <select class="w-select"
                                            ng-options="item as item.title for item in data.years track by item.id"
                                            ng-model="beforeYear">
                                    </select>
                                    <select class="w-select"
                                            ng-options="item as item.title for item in data.months track by item.id"
                                            ng-model="beforeMonth">
                                    </select>

                                    <?php /*
                                    <label class="dropdown-label" for="field-7">Source</label>
                                    <select class="w-select"
                                            ng-options="item as item.title for item in data.sources track by item.id"
                                            ng-model="fundingSource">
                                    </select>
                                    */ ?>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="w-col w-col-8" ng-cloak>
                        <div ng-repeat="funding in data.fundings
                                 | filter: searchName
                                 | filter: fundingHasType(fundingType)
                                 | filter: fundingHasTarget(fundingTarget)
                                 | filter: {countryID: inCountry.id || ''}
                                 | filter: earlierThan('' + beforeYear.id + beforeMonth.id)
                                   track by funding.id" style="position:relative">
                            <a class="info-card" data-ix="underline" href="{{funding.url}}"
                               style="display: block" target="_blank">
                                <h2 class="funding-card-h2" ng-bind="funding.title"></h2>
                                <div class="infocard top3-underline" data-ix="new-interaction-2"></div>
                                <p class="funding-descr" ng-bind="funding.description"></p>
                                <div class="funding-closing-date" ng-show="funding.closingDate">
                                    <strong><?php _ehtml('Closing date')?>:</strong> {{funding.closingDate}}
                                </div>
                                <div class="funding-country funding-new" ng-show="funding.isNew">
                                    <?php _ehtml('New opportunity')?>
                                </div>
                            </a>
                            <?php if ($userCanAddFunding) { ?>
                                <a class="edit" href="{{funding.editUrl}}"><?php _ehtml('Edit')?></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/FundingController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>