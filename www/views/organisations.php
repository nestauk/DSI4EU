<?php
require __DIR__ . '/header.php';
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler \DSI\Service\URL */

$showAdvancedSearch = (
    isset($_GET['q']) OR
    isset($_GET['tag']) OR
    isset($_GET['netwTag'])
);

?>
    <div ng-controller="OrganisationsController"
         data-organisationsjsonurl="<?php echo $urlHandler->organisations('json') ?>"
         data-organisationtagsjsonurl="<?php echo $urlHandler->organisationTagsJson() ?>"
         data-searchname="<?php echo show_input($_GET['q'] ?? '') ?>"
         data-searchtag="<?php echo show_input($_GET['tag'] ?? '0') ?>"
         data-searchnetwtag="<?php echo show_input($_GET['netwTag'] ?? '0') ?>"
         data-showadvancedsearch="<?php echo (bool)$showAdvancedSearch ?>">

        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1"><?php _ehtml('Organisations') ?></h1>
                    <p class="intro">
                        <?php _ehtml('There are thousands of organisations working to develop digital social innovation.') ?>
                    </p>
                    <p class="header-intro-descr">
                        <?php _ehtml('Here you can browse through DSI organisations across Europe.') ?>
                    </p>
                </div>
                <div class="sidebar w-col w-col-4 w-col-stack">
                    <h1 class="content-h1 side-bar-space-h1"><?php _ehtml('Add your organisation') ?></h1>
                    <p><?php _ehtml('Let others know who you are and what you do, meet potential collaborators and link your projects to your organisation.') ?></p>
                    <?php if ($loggedInUser) { ?>
                        <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow" href="#">
                            <div class="login-li long menu-li readmore-li" data-ix="create-organisation-modal">
                                <?php _ehtml('Add your organisation') ?>
                            </div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </a>
                    <?php } else { ?>
                        <a class="log-in-link long read-more w-clearfix w-inline-block"
                           href="<?php echo $urlHandler->login() ?>?from=organisation">
                            <div class="login-li long menu-li readmore-li" data-ix="create-organisation-modal">
                                <?php _ehtml('Add your organisation') ?>
                            </div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </a>
                    <?php } ?>
                </div>
            </div>
            <div class="alphabet-selectors w-clearfix">
                <a href="#" class="w-inline-block alphabet-link" style="width:50px"
                   ng-class="{selected: startLetter == ''}"
                   ng-click="setStartLetter('')">
                    <div><?php _ehtml('All') ?></div>
                </a>
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
                                <form id="email-form" name="email-form">
                                    <h3 class="sidebar-h3"><?php _ehtml('Filter Organisations') ?></h3>
                                    <div class="search-div">
                                        <input class="sidebar-search-field w-input" data-ix="hide-search-icon"
                                               data-name="Search" id="Search" maxlength="256" name="Search"
                                               placeholder="<?php _ehtml('Search by keyword, type or project') ?>"
                                               type="text" ng-model="searchName">
                                        <img class="search-mag"
                                             src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-search.png">
                                    </div>

                                    <div>
                                        <div class="advanced-search w-row">
                                            <div class="w-col w-col-6">
                                                <a class="w-clearfix w-inline-block" href="#"
                                                   ng-click="showAdvancedSearch = !showAdvancedSearch">
                                                    <div class="adv-text">Advanced filters</div>
                                                    <div ng-class="{showAdvancedSearch: showAdvancedSearch}"
                                                         class="arrow advancedSearchArrow"></div>
                                                </a>
                                            </div>
                                            <div class="w-col w-col-6"></div>
                                        </div>
                                        <div class="adv-options" ng-show="showAdvancedSearch">
                                            <label>Country</label>
                                            <select class="w-select" id="field" name="field"
                                                    ng-model="filter.countryID">
                                                <option value="0">- Select one country -</option>
                                                <option ng-repeat="country in countries" value="{{country.id}}">
                                                    {{country.text}}
                                                </option>
                                            </select>

                                            <label>Tag</label>
                                            <select class="w-select" id="field" name="field" ng-model="filter.tagID">
                                                <option value="0">- Select a tag-</option>
                                                <option ng-repeat="tag in tags" value="{{tag.id}}">
                                                    {{tag.name}}
                                                </option>
                                            </select>

                                            <label>Network</label>
                                            <select class="w-select" id="field" name="field"
                                                    ng-model="filter.netwTagID">
                                                <option value="0">- Select a tag -</option>
                                                <option ng-repeat="tag in netwTags" value="{{tag.id}}">
                                                    {{tag.name}}
                                                </option>
                                            </select>

                                            <br/>
                                        </div>
                                    </div>
                                </form>
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
                                   | filter: organisationInCountry()
                                   | filter: organisationHasTag()
                                   | filter: projectHasNetwTag()
                                    as filtered"
                                   ng-if="$index < (filtered.length / 2)">
                                    <h3 class="info-card-h3" ng-bind="organisation.name"></h3>
                                    <div class="involved-tag">
                                        <span ng-show="organisation.projectsCount == 1">
                                            <?php echo sprintf(
                                                _html('%s Project'),
                                                '<strong>1</strong>'
                                            ) ?>
                                            </span>
                                        <span ng-show="organisation.projectsCount != 1"
                                              style="text-transform: capitalize">
                                            <?php echo sprintf(
                                                _html('%s projects'),
                                                '<strong ng-bind="organisation.projectsCount"></strong>'
                                            ) ?>
                                            </span>

                                        <span ng-show="organisation.partnersCount == 1">
                                            <?php echo sprintf(
                                                _html('%s Partner'),
                                                '<strong>1</strong>'
                                            ) ?>
                                            </span>
                                        <span ng-show="organisation.partnersCount != 1">
                                            <?php echo sprintf(
                                                _html('%s Partners'),
                                                '<strong ng-bind="organisation.partnersCount"></strong>'
                                            ) ?>
                                            </span>
                                    </div>
                                </a>
                            </div>
                            <div class="w-col w-col-6 w-col-stack">
                                <a class="info-card left small w-inline-block" href="{{organisation.url}}"
                                   ng-repeat="organisation in organisations
                                   | filter: startsWithLetter
                                   | filter: searchName
                                   | filter: organisationInCountry()
                                   | filter: organisationHasTag()
                                   | filter: projectHasNetwTag()
                                   as filtered"
                                   ng-if="$index >= (filtered.length / 2)">
                                    <h3 class="info-card-h3" ng-bind="organisation.name"></h3>
                                    <div class="involved-tag">
                                        <span ng-show="organisation.projectsCount == 1">
                                            <?php echo sprintf(
                                                _html('%s Project'),
                                                '<strong>1</strong>'
                                            ) ?>
                                            </span>
                                        <span ng-show="organisation.projectsCount != 1">
                                            <?php echo sprintf(
                                                _html('%s Projects'),
                                                '<strong ng-bind="organisation.projectsCount"></strong>'
                                            ) ?>
                                            </span>

                                        <span ng-show="organisation.partnersCount == 1">
                                            <?php echo sprintf(
                                                _html('%s Partner'),
                                                '<strong>1</strong>'
                                            ) ?>
                                            </span>
                                        <span ng-show="organisation.partnersCount != 1">
                                            <?php echo sprintf(
                                                _html('%s Partners'),
                                                '<strong ng-bind="organisation.partnersCount"></strong>'
                                            ) ?>
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