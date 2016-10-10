<?php
require __DIR__ . '/header.php'
/** @var $loggedInUser \DSI\Entity\User */
?>
    <div ng-controller="OrganisationsController"
         data-organisationsjsonurl="<?php echo $urlHandler->organisations('json') ?>">

        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1">Organisations</h1>
                    <p class="intro">Organisation intro text explaining more about organisations and leading in to
                        description of four main areas below this text</p>
                    <p class="header-intro-descr">While DSI is an incredibly diverse field, the many types of practice
                        can be understood as manifestations of four main technological trends: Open Hardware, Open
                        Networks, Open Data and Open Knowledge.</p>
                </div>
                <div class="sidebar w-col w-col-4 w-col-stack">
                    <h1 class="content-h1 side-bar-space-h1">Add your organisation</h1>
                    <p>Here are reasons to add your organisation</p>
                    <?php if ($loggedInUser) { ?>
                        <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow" href="#">
                            <div class="login-li long menu-li readmore-li" data-ix="create-organisation-modal">Add your
                                Organisation
                            </div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </a>
                    <?php } ?>
                </div>
            </div>
            <div class="alphabet-selectors w-clearfix">
                <a href="#" class="w-inline-block alphabet-link"
                   ng-class="{selected: startLetter == letter}"
                   ng-repeat="letter in letters"
                   ng-click="setStartLetter(letter)">
                    <div ng-bind="letter"></div>
                </a>
            </div>
        </div>

        <div class="content-directory">
            <div class="list-block">
                <div class="w-row">
                    <div class="filter-col-left w-col w-col-4">
                        <div class="filter-bar info-card">
                            <div class="w-form">
                                <form data-name="Email Form" id="email-form" name="email-form">
                                    <h3 class="sidebar-h3">Filter Organisations</h3>
                                    <div class="search-div">
                                        <input class="sidebar-search-field w-input" data-ix="hide-search-icon"
                                               data-name="Search" id="Search" maxlength="256" name="Search"
                                               placeholder="Search by keyword, type or project" type="text"
                                               ng-model="searchName">
                                        <img class="search-mag"
                                             src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-search.png">
                                    </div>
                                    <div class="filter-checkbox w-checkbox">
                                        <input class="w-checkbox-input" data-name="Checkbox 2" id="checkbox-2"
                                               name="checkbox-2" type="checkbox">
                                        <label class="w-form-label" for="checkbox-2">Open hardware</label>
                                    </div>
                                    <div class="trend-notes">Open hardware explanation text for this trend to go in this
                                        section here
                                    </div>
                                    <div class="filter-checkbox w-checkbox">
                                        <input class="w-checkbox-input" data-name="Checkbox 5" id="checkbox-5"
                                               name="checkbox-5" type="checkbox">
                                        <label class="w-form-label" for="checkbox-5">Open networks</label>
                                    </div>
                                    <div class="trend-notes">Open networks explanation text for this trend to go in this
                                        section here
                                    </div>
                                    <div class="filter-checkbox w-checkbox">
                                        <input class="w-checkbox-input" data-name="Checkbox 4" id="checkbox-4"
                                               name="checkbox-4" type="checkbox">
                                        <label class="w-form-label" for="checkbox-4">Open data</label>
                                    </div>
                                    <div class="trend-notes">Open data explanation text for this trend to go in this
                                        section here
                                    </div>
                                    <div class="filter-checkbox w-checkbox">
                                        <input class="w-checkbox-input" data-name="Checkbox 3" id="checkbox-3"
                                               name="checkbox-3" type="checkbox">
                                        <label class="w-form-label" for="checkbox-3">Open knowledge</label>
                                    </div>
                                </form>
                            </div>
                            <div class="trend-notes">Open knowledge explanation text for this trend to go in this
                                section here
                            </div>
                        </div>
                    </div>
                    <div class="w-col w-col-8">
                        <div class="w-row">
                            <div class="w-col w-col-6 w-col-stack">
                                <a class="info-card left small w-inline-block" href="{{organisation.url}}"
                                   ng-repeat="organisation in organisations
                                   | filter: startsWithLetter
                                   | filter: searchName
                                    as filtered"
                                   ng-if="$index < (filtered.length / 2)">
                                    <h3 class="info-card-h3" ng-bind="organisation.name"></h3>
                                    <div class="involved-tag">
                                        <strong ng-bind="organisation.projectsCount"></strong>
                                        <span
                                            ng-bind="organisation.projectsCount == 1 ? 'Project' : 'Projects'">
                                                </span>
                                        -
                                        <strong ng-bind="organisation.partnersCount"></strong>
                                        <span
                                            ng-bind="organisation.partnersCount == 1 ? 'Partner' : 'Partners'">
                                                </span>
                                    </div>
                                </a>
                            </div>
                            <div class="w-col w-col-6 w-col-stack">
                                <a class="info-card left small w-inline-block" href="{{organisation.url}}"
                                   ng-repeat="organisation in organisations
                                   | filter: startsWithLetter
                                   | filter: searchName
                                   as filtered"
                                   ng-if="$index >= (filtered.length / 2)">
                                    <h3 class="info-card-h3" ng-bind="organisation.name"></h3>
                                    <div class="involved-tag">
                                        <strong ng-bind="organisation.projectsCount"></strong>
                                        <span
                                            ng-bind="organisation.projectsCount == 1 ? 'Project' : 'Projects'">
                                                </span>
                                        -
                                        <strong ng-bind="organisation.partnersCount"></strong>
                                        <span
                                            ng-bind="organisation.partnersCount == 1 ? 'Partner' : 'Partners'">
                                                </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/OrganisationsController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>
<?php require __DIR__ . '/footer.php' ?>