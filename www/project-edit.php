<?php
require __DIR__ . '/header.php';
/** @var $project \DSI\Entity\Project */
?>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ProjectEditController.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <style>
        .thumb {
            width: 24px;
            height: 24px;
            float: none;
            position: relative;
            top: 7px;
        }

        form .progress {
            line-height: 15px;
        }

        .progress {
            display: inline-block;
            width: 100px;
            border: 3px groove #CCC;
        }

        .progress div {
            font-size: smaller;
            background: orange;
            width: 0;
        }
    </style>

    <div ng-controller="ProjectEditController" data-projectid="<?php echo $project->getId() ?>">
        <div class="w-section page-header">
            <div class="container-wide header">
                <h1 class="page-h1 light">Edit project</h1>
            </div>
        </div>

        <?php require(__DIR__ . '/partialViews/search-results.php'); ?>

        <div class="container-wide">
            <div class="body-content add-story">
                <div class="w-form">
                    <form class="w-clearfix" ng-submit="save()" ng-disabled="loading">
                        <div class="w-row">
                            <div class="w-col w-col-6">
                                <h2 class="edit-h2">Project info</h2>
                                <label class="story-label">Project name</label>
                                <input class="w-input story-form personal" maxlength="256"
                                       placeholder="Add your project's name" type="text" ng-model="project.name">
                                <label class="story-label" for="project-url">Website</label>
                                <input class="w-input story-form personal" maxlength="256"
                                       placeholder="Add your projects URL" type="text" ng-model="project.url">
                                <label class="story-label" for="project-bio">Project description</label>
                                <textarea class="w-input story-form" maxlength="5000" ng-model="project.description"
                                          placeholder="Briefly describe your project (no more than 500 characters)"></textarea>
                                <label class="story-label" for="Story-wysiwyg">Current status</label>
                                <div class="w-checkbox">
                                    <label class="w-form-label">
                                        <input class="w-checkbox-input" value="live" type="radio"
                                               ng-model="project.status">
                                        Live
                                    </label>
                                </div>
                                <div class="w-checkbox">
                                    <label class="w-form-label">
                                        <input class="w-checkbox-input" value="closed" type="radio"
                                               ng-model="project.status">
                                        Closed
                                    </label>
                                </div>
                                <label class="story-label profile-image" for="Title">Your project logo</label>
                                <img class="story-image-upload"
                                     src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg"
                                     ng-src="{{project.logo}}">
                                <a class="w-button dsi-button story-image-upload" href="#"
                                   ngf-select="logo.upload($file, $invalidFiles)"
                                   ng-bind="logo.loading ? 'Loading...' : 'Upload image'">
                                    Upload image
                                </a>
                                <?php /*
                                <label class="story-label" for="Title">Header background image</label>
                                <img class="story-image-upload story-image-upload-large"
                                     src="images/brussels-locations.jpg">
                                <a class="w-button dsi-button story-image-upload" href="#">Upload image</a>
                                */ ?>
                            </div>
                            <div class="w-col w-col-6">
                                <h2 class="edit-h2">Duration of project</h2>
                                <label class="story-label" for="start-date">Project start date</label>
                                <input id="start-date-hidden" type="text" style="display:none"
                                       ng-model="project.startDate">
                                <input class="w-input story-form personal" id="start-date"
                                       maxlength="256" placeholder="What date did the project start?"
                                       type="text" ng-model="project.startDateHumanReadable"
                                       onclick="$('#start-date-hidden').datepicker('show')">
                                <label class="story-label" for="End-date">Project end date (leave this blank for ongoing
                                    projects)</label>
                                <input id="end-date-hidden" type="text" style="display:none" ng-model="project.endDate">
                                <input class="w-input story-form personal" id="end-date"
                                       maxlength="256" placeholder="When did/will the project end?"
                                       type="text" ng-model="project.endDateHumanReadable"
                                       onclick="$('#end-date-hidden').datepicker('show')">
                                <h2 class="edit-h2">Where is your project based?</h2>

                                <div ng-cloak>
                                    <div>
                                        <label class="story-label" for="country">Which country are you based in?</label>
                                        <select id="edit-country" data-placeholder="Select country"
                                                style="width:400px;background:transparent">
                                            <option></option>
                                        </select>
                                    </div>
                                    <div ng-show="regionsLoaded">
                                        <br/>
                                        <label class="story-label" for="city">In which city?</label>
                                        <select
                                            data-tags="true" id="edit-countryRegion"
                                            data-placeholder="Type the city"
                                            style="width:400px;background:transparent">
                                        </select>
                                    </div>
                                    <div ng-show="regionsLoading">
                                        Loading...
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input class="w-button dsi-button post-story" type="submit" value="Update project"
                               ng-value="loading ? 'Loading...' : 'Update project'"
                               ng-disabled="loading">
                        <a href="<?php echo \DSI\Service\URL::project($project) ?>"
                           class="w-button dsi-button post-story cancel">Back to project</a>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script>
        $(function () {
            $("#start-date-hidden").datepicker({
                dateFormat: "yy-mm-dd",
                altField: "#start-date",
                altFormat: "DD, d MM, yy"
            });
            $('#end-date-hidden').datepicker({
                dateFormat: "yy-mm-dd",
                altField: "#end-date",
                altFormat: "DD, d MM, yy"
            });
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>