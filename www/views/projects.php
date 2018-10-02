<?php
require __DIR__ . '/header.php';
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler Services\URL */

$showAdvancedSearch = (
    isset($_GET['q']) OR
    isset($_GET['tag']) OR
    isset($_GET['helpTag']) OR
    isset($_GET['techTag'])
);

?>
    <div ng-controller="ProjectsController"
         data-projectsjsonurl="<?php echo $urlHandler->projectsJson() ?>"
         data-projecttagsjsonurl="<?php echo $urlHandler->projectTagsJson() ?>"
         data-searchname="<?php echo show_input($_GET['q'] ?? '') ?>"
         data-searchtag="<?php echo show_input($_GET['tag'] ?? '0') ?>"
         data-searchhelptag="<?php echo show_input($_GET['helpTag'] ?? '0') ?>"
         data-searchtechtag="<?php echo show_input($_GET['techTag'] ?? '0') ?>"
         data-searchopentag="<?php echo show_input($_GET['openTag'] ?? '0') ?>"
         data-showadvancedsearch="<?php echo (bool)$showAdvancedSearch ?>">

        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1"><?php _ehtml('Projects') ?></h1>
                    <p class="intro">
                        <?php _ehtml('Digital social innovation (DSI) covers a range of areas, from providing tools to improve democratic processes to [...]') ?>
                    </p>
                    <p class="header-intro-descr">
                        <?php _ehtml('Here you can look through projects which digital social innovators across Europe have submitted to the website. [...]') ?>
                        <strong><?php _ehtml('Open hardware') ?></strong>,
                        <strong><?php _ehtml('Open networks') ?></strong>,
                        <strong><?php _ehtml('Open data') ?></strong> <?php _ehtml('and') ?>
                        <strong><?php _ehtml('Open knowledge') ?></strong>.
                    </p>
                </div>
                <div class="sidebar w-col w-col-4 w-col-stack">
                    <h1 class="content-h1 side-bar-space-h1"><?php _ehtml('Add your project') ?></h1>
                    <p>
                        <?php _ehtml('Showcase your current and past work, identify potential collaborations and link your projects to your organisation.') ?>
                    </p>
                    <?php if ($loggedInUser) { ?>
                        <a class="log-in-link long read-more w-clearfix w-inline-block ix-create-project-modal"
                           href="#">
                            <div class="login-li long menu-li readmore-li"><?php _ehtml('Add your project') ?></div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </a>
                    <?php } else { ?>
                        <a class="log-in-link long read-more w-clearfix w-inline-block"
                           href="<?php echo $urlHandler->login() ?>?from=project">
                            <div class="login-li long menu-li readmore-li"><?php _ehtml('Add your project') ?></div>
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
                                    <h3 class="sidebar-h3"><?php _ehtml('Filter projects') ?></h3>
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
                                                    <div class="adv-text"><?php _ehtml('Advanced filters') ?></div>
                                                    <div ng-class="{showAdvancedSearch: showAdvancedSearch}"
                                                         class="arrow advancedSearchArrow"></div>
                                                </a>
                                            </div>
                                            <div class="w-col w-col-6"></div>
                                        </div>
                                        <div class="adv-options" ng-show="showAdvancedSearch">
                                            <label><?php _ehtml('Country') ?></label>
                                            <select class="w-select" id="field" name="field"
                                                    ng-model="filter.countryID">
                                                <option value="0">- <?php _ehtml('Select a country') ?> -</option>
                                                <option ng-repeat="country in countries" value="{{country.id}}">
                                                    {{country.text}}
                                                </option>
                                            </select>

                                            <label><?php _ehtml('Tag') ?></label>
                                            <select class="w-select" id="field" name="field" ng-model="filter.tagID">
                                                <option value="0">- <?php _ehtml('Select a tag') ?> -</option>
                                                <option ng-repeat="tag in tags" value="{{tag.id}}">
                                                    {{tag.name}}
                                                </option>
                                            </select>

                                            <label><?php _ehtml('Areas of impact') ?></label>
                                            <select class="w-select" id="field" name="field"
                                                    ng-model="filter.helpTagID">
                                                <option value="0">- <?php _ehtml('Select a tag') ?> -</option>
                                                <option ng-repeat="tag in impactHelpTags" value="{{tag.id}}">
                                                    {{tag.name}}
                                                </option>
                                            </select>

                                            <label><?php _ehtml('Technology') ?></label>
                                            <select class="w-select" id="field" name="field"
                                                    ng-model="filter.techTagID">
                                                <option value="0">- <?php _ehtml('Select a tag') ?> -</option>
                                                <option ng-repeat="tag in impactTechTags" value="{{tag.id}}">
                                                    {{tag.name}}
                                                </option>
                                            </select>

                                            <br/>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="filter-title">
                                        <strong><?php _ehtml('Filter by category') ?></strong>
                                    </div>
                                    <div class="filter-checkbox w-checkbox">
                                        <input class="w-checkbox-input" data-name="Checkbox 2" id="checkbox-2"
                                               name="checkbox-2" ng-model="dsiFocus[35]"
                                               type="checkbox">
                                        <label class="w-form-label"
                                               for="checkbox-2"><?php _ehtml('Open hardware') ?></label>
                                    </div>
                                    <div class="trend-notes">
                                        <?php _ehtml('Making things with open hardware to tackle social challenges') ?>
                                    </div>
                                    <div class="filter-checkbox w-checkbox">
                                        <input class="w-checkbox-input" data-name="Checkbox 5" id="checkbox-5"
                                               name="checkbox-5" ng-model="dsiFocus[9]"
                                               type="checkbox">
                                        <label class="w-form-label"
                                               for="checkbox-5"><?php _ehtml('Open networks') ?></label>
                                    </div>
                                    <div class="trend-notes">
                                        <?php _ehtml('Growing networks and infrastructure through technology from the bottom up to tackle social challenges') ?>
                                    </div>
                                    <div class="filter-checkbox w-checkbox">
                                        <input class="w-checkbox-input" data-name="Checkbox 4" id="checkbox-4"
                                               name="checkbox-4" ng-model="dsiFocus[8]"
                                               type="checkbox">
                                        <label class="w-form-label"
                                               for="checkbox-4"><?php _ehtml('Open data') ?></label>
                                    </div>
                                    <div class="trend-notes">
                                        <?php _ehtml('Capturing, sharing, analysing and using open data to tackle social challenges') ?>
                                    </div>
                                    <div class="filter-checkbox w-checkbox">
                                        <input class="w-checkbox-input" data-name="Checkbox 3" id="checkbox-3"
                                               name="checkbox-3" ng-model="dsiFocus[4]"
                                               type="checkbox">
                                        <label class="w-form-label"
                                               for="checkbox-3"><?php _ehtml('Open knowledge') ?></label>
                                    </div>
                                    <div class="trend-notes">
                                        <?php _ehtml('Harnessing the power and assets of the crowd to tackle social challenges') ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="w-col w-col-8">
                        <div class="w-row">
                            <div class="w-col w-col-6 w-col-stack">
                                <a class="info-card left small w-inline-block" href="{{project.url}}"
                                   ng-repeat="project in projects
                                   | filter: startsWithLetter
                                   | filter: searchName
                                   | filter: projectHasDsiFocusTag()
                                   | filter: projectInCountry()
                                   | filter: projectHasTag()
                                   | filter: projectHasHelpTag()
                                   | filter: projectHasTechTag()
                                   as filtered"
                                   ng-if="$index < (filtered.length / 2)">
                                    <h3 class="info-card-h3" ng-bind="project.name"></h3>
                                    <div class="involved-tag">
                                        <span ng-show="project.organisationsCount == 1">
                                            <?php _ehtml('1 Organisation involved') ?>
                                        </span>
                                        <span ng-show="project.organisationsCount != 1">
                                            <?php echo sprintf(
                                                _html('%s Organisations involved'),
                                                '<span ng-bind="project.organisationsCount"></span>'
                                            ) ?>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="w-col w-col-6 w-col-stack">
                                <a class="info-card left small w-inline-block" href="{{project.url}}"
                                   ng-repeat="project in projects
                                   | filter: startsWithLetter
                                   | filter: searchName
                                   | filter: projectHasDsiFocusTag()
                                   | filter: projectInCountry()
                                   | filter: projectHasTag()
                                   | filter: projectHasHelpTag()
                                   | filter: projectHasTechTag()
                                   as filtered"
                                   ng-if="$index >= (filtered.length / 2)">
                                    <h3 class="info-card-h3" ng-bind="project.name"></h3>
                                    <div class="involved-tag">
                                        <span ng-show="project.organisationsCount == 1">
                                            <?php _ehtml('1 Organisation involved') ?>
                                        </span>
                                        <span ng-show="project.organisationsCount != 1">
                                            <?php echo sprintf(
                                                _html('%s Organisations involved'),
                                                '<span ng-bind="project.organisationsCount"></span>'
                                            ) ?>
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <div ng-show="filtered.length == 0" ng-cloak>
                                <?php _ehtml('No results found.') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ProjectsController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>