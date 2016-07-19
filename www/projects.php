<?php
require __DIR__ . '/header.php'
/** @var $loggedInUser \DSI\Entity\User */
?>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ProjectsController.js"></script>

    <div ng-controller="ProjectsController">

        <div class="w-section page-header stories-header">
            <div class="container-wide header">
                <h1 class="page-h1 light">Projects</h1>
                <div class="w-clearfix alphabet-selectors">
                    <a href="#" class="w-inline-block alphabet-link"
                       ng-class="{selected: startLetter == letter}"
                       ng-repeat="letter in letters"
                       ng-click="setStartLetter(letter)">
                        <div ng-bind="letter"></div>
                    </a>
                    <?php if ($loggedInUser) { ?>
                        <a class="w-button dsi-button top-filter add-new-story" href="#"
                           data-ix="create-project-modal">Add new project +</a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="w-section project-archive">
            <div class="container-wide archive">
                <div class="w-row card-row">
                    <div class="w-col w-col-4 w-col-stack"
                         ng-repeat="project in projects | filter:startsWithLetter">
                        <a ng-href="{{project.url}}" class="w-inline-block card-thin">
                            <div class="w-row">
                                <div class="w-col w-col-3 w-col-medium-6 w-col-small-6">
                                    <img width="50" src="images/dsi logo placeholder.svg" class="card-logo-small">
                                </div>
                                <div class="w-col w-col-9 w-col-medium-6 w-col-small-6 card-slim-info">
                                    <h2 class="card-slim-h2" ng-bind="project.name"></h2>
                                    <div class="card-slim-location"
                                         ng-bind="project.region + ', ' + project.country"></div>
                                    <div class="card-slim-stats">
                                        <div>
                                            <strong ng-bind="project.organisationsCount"></strong>
                                            <span
                                                ng-bind="project.organisationsCount == 1 ? 'Organisation' : 'Organisations'">
                                            </span> involved
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

<?php require __DIR__ . '/footer.php' ?>