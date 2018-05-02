<?php
require __DIR__ . '/header.php';
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler Services\URL */

?>
<style>
    .manage-tag{
        background-color: #2acca5;
        border: 1px solid rgba(170, 170, 170, 0);
        border-radius: 3px;
        cursor: default;
        float: left;
        margin-right: 5px;
        margin-top: 5px;
        padding: 3px 10px;
        color: #fff;
        margin-bottom: 0;
    }
    .manage-tag a{
        color: inherit;
        text-decoration: none;
    }
    .manage-tag span{
        color: #c5f1e6;
        cursor: pointer;
        display: inline-block;
        font-weight: bold;
        margin-right: 7px;
    }

</style>
    <div ng-controller="ManageTagsController"
         data-managetagsjsonurl="<?php echo $urlHandler->manageTagsJson() ?>">
        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1">Manage Tags</h1>
                    <br/><br/>
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
                                    <h3 class="sidebar-h3">Filter Tags</h3>
                                    <div class="search-div">
                                        <input class="sidebar-search-field w-input" data-ix="hide-search-icon"
                                               data-name="Search" id="Search" maxlength="256" name="Search"
                                               placeholder="Search by name"
                                               type="text" ng-model="searchName">
                                        <img class="search-mag"
                                             src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-search.png">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="w-col w-col-8">
                        <div class="w-row">
                            <div class="w-col">
                                <h3>Network Tags</h3>
                                <span class="manage-tag" ng-repeat="tag in data.networkTags
                                   | filter: startsWithLetter
                                   | filter: searchName
                                    as filtered">
                                    <span ng-click="deleteNetworkTag(tag)">X</span>
                                    <a href="<?php echo $urlHandler->organisations() ?>?netwTag={{tag.id}}"
                                       ng-bind="tag.name"></a> &nbsp;
                                </span>
                                <div style="clear:both"></div>

                                <br/>
                                <h3>Organisation Tags</h3>
                                <span class="manage-tag" ng-repeat="tag in data.organisationTags
                                   | filter: startsWithLetter
                                   | filter: searchName
                                    as filtered">
                                    <span ng-click="deleteOrganisationTag(tag)">X</span>
                                    <a href="<?php echo $urlHandler->organisations() ?>?tag={{tag.id}}"
                                       ng-bind="tag.name"></a> &nbsp;
                                </span>
                                <div style="clear:both"></div>

                                <br/>
                                <h3>Project Tags</h3>
                                <span class="manage-tag" ng-repeat="tag in data.projectTags
                                   | filter: startsWithLetter
                                   | filter: searchName
                                    as filtered">
                                    <span ng-click="deleteProjectTag(tag)">X</span>
                                    <a href="<?php echo $urlHandler->projects() ?>?tag={{tag.id}}"
                                       ng-bind="tag.name"></a> &nbsp;
                                </span>
                                <div style="clear:both"></div>

                                <br/>
                                <h3>Project Impact Tags</h3>
                                <span class="manage-tag" ng-repeat="tag in data.projectImpactTags
                                   | filter: startsWithLetter
                                   | filter: searchName
                                    as filtered">
                                    <span ng-click="deleteProjectImpactTag(tag)">X</span>
                                    <a href="#" ng-bind="tag.name"></a> &nbsp;
                                </span>
                                <div style="clear:both"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ManageTagsController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>
<?php require __DIR__ . '/footer.php' ?>