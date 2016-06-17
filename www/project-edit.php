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
        <div class="container-wide">
            <div class="body-content add-story">
                <h1 class="page-h1">Edit project</h1>
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
                                     src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg">
                                <a class="w-button dsi-button story-image-upload" href="#">Upload image</a>
                                <label class="story-label" for="Title">Header background image</label>
                                <img class="story-image-upload story-image-upload-large"
                                     src="images/brussels-locations.jpg">
                                <a class="w-button dsi-button story-image-upload" href="#">Upload image</a>
                            </div>
                            <div class="w-col w-col-6">
                                <h2 class="edit-h2">Duration of project</h2>
                                <label class="story-label" for="start-date">Project start date</label>
                                <input class="w-input story-form personal" id="start-date"
                                       maxlength="256" placeholder="What date did the project start?"
                                       type="text">
                                <label class="story-label" for="End-date">Project end date (leave this blank for ongoing
                                    projects)</label>
                                <input class="w-input story-form personal" data-name="End date" id="End-date"
                                       maxlength="256" name="End-date" placeholder="When did/will the project end?"
                                       type="text">
                                <h2 class="edit-h2">Where is your project based?</h2>
                                <label class="story-label" for="city">Which city are you based in?</label>
                                <input class="w-input story-form personal" data-name="city" id="city" maxlength="256"
                                       name="city" placeholder="Your city" type="text">
                                <label class="story-label" for="country">In which country?</label>
                                <input class="w-input story-form personal" data-name="country" id="country"
                                       maxlength="256" name="country" placeholder="Your country" type="text">
                            </div>
                        </div>
                        <input class="w-button dsi-button post-story" type="submit" value="Update project"
                               ng-value="loading ? 'Loading...' : 'Update project'"
                               ng-disabled="loading">
                        <a href="<?php echo \DSI\Service\URL::project($project->getId(), $project->getName()) ?>"
                           class="w-button dsi-button post-story cancel">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script>
        $(function() {
            $( "#start-date" ).datepicker({
                dateFormat: "DD, d MM, yy",
                altField: "#alternate-start-date",
                altFormat: "DD, d MM, yy"
            });
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>