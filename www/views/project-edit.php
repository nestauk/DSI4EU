<?php
require __DIR__ . '/header.php';
/** @var $project \DSI\Entity\Project */
/** @var $tags \DSI\Entity\TagForProjects[] */
/** @var $impactTags \DSI\Entity\ImpactTag[] */
/** @var $dsiFocusTags \DSI\Entity\DsiFocusTag[] */
/** @var $projectTags string[] */
/** @var $projectImpactTagsA string[] */
/** @var $projectImpactTagsB string[] */
/** @var $projectImpactTagsC string[] */
/** @var $organisations \DSI\Entity\Organisation[] */
/** @var $projectOrganisations int[] */

$leftSideText = "<p>To add your project, we need to collect some information about your project and its aims. We are interested in hearing from both formal and informal projects.</p>";
$leftSideText .= "<p>Some information is optional (mandatory fields are indicated with an asterisk), but the more you can provide, the better. We will add you as soon as we have some basic data. You will be able to edit and expand on your answers later.</p>";

if (!isset($urlHandler))
    $urlHandler = new \DSI\Service\URL();

?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/CaseStudyEditController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ProjectEditController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <div ng-controller="ProjectEditController"
         data-projectid="<?php echo $project->getId() ?>"
         data-projecturl="<?php echo $urlHandler->project($project) ?>">
        <div class="creator page-header">
            <div class="container-wide header">
                <h1 class="light page-h1">Edit project</h1>
            </div>
        </div>
        <div class="creator section-white">
            <div class="container-wide">
                <div class="add-story body-content">
                    <div class="w-tabs" data-easing="linear">
                        <div class="creator-tab-menu w-tab-menu">
                            <a class="step-tab tab-link-1 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step1'}" data-w-tab="Tab 1"
                               ng-click="currentTab = 'step1'">
                                <div>1 - Project details</div>
                            </a>
                            <a class="step-tab tab-link-2 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step2'}" data-w-tab="Tab 2"
                               ng-click="currentTab = 'step2'">
                                <div>2 - Project duration & location</div>
                            </a>
                            <a class="step-tab tab-link-3 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step3'}" data-w-tab="Tab 3"
                               ng-click="currentTab = 'step3'">
                                <div>3 - Project Description</div>
                            </a>
                            <a class="step-tab tab-link-4 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step4'}" data-w-tab="Tab 4" id="tab-four"
                               ng-click="currentTab = 'step4'">
                                <div>4 - Publish your project</div>
                            </a>
                        </div>
                        <div class="w-tab-content">
                            <div class="step-window w-tab-pane" ng-class="{'w--tab-active': currentTab == 'step1'}"
                                 data-w-tab="Tab 1">
                                <form id="email-form-3" name="email-form-3" ng-submit="submitStep1()">
                                    <div class="tabbed-nav-buttons w-clearfix">
                                        <input type="submit" class="tab-button-2 tab-button-next w-button"
                                               ng-value="loading ? 'Loading...' : 'Next'"
                                               ng-disabled="loading"
                                               value="Next"/>
                                        <button type="button" class="tab-button-2 tab-button-next w-button"
                                                ng-bind="loading ? 'Loading...' : 'Save'"
                                                ng-click="submitStep1({proceed: false})"
                                                ng-disabled="loading">Save
                                        </button>
                                    </div>
                                    <div class="w-row">
                                        <div class="creator-col w-col w-col-4">
                                            <h2>1 - Project Details</h2>
                                            <?php echo $leftSideText ?>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8">
                                            <div class="w-form">
                                                <div class="w-row">
                                                    <div class="w-col w-col-6 w-col-stack">
                                                        <div class="padding-right-50">
                                                            <label for="name">Project name * </label>
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Name" id="name" maxlength="256"
                                                                   name="name" placeholder="Project Name"
                                                                   ng-model="project.name"
                                                                   type="text">
                                                            <div class="log-in-error" ng-show="errors.name"
                                                                 ng-bind="errors.name"></div>
                                                            <br/>

                                                            <label for="email">Project website</label>
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email" id="email" maxlength="256"
                                                                   name="email"
                                                                   placeholder="http://"
                                                                   ng-model="project.url"
                                                                   type="text">
                                                            <?php /*
                                                            <br/><br/>
                                                            <label for="email-2">Social media links</label>
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 2" id="email-2" maxlength="256"
                                                                   name="email-2" placeholder="Facebook"
                                                                   ng-model="project.links.facebook"
                                                                   type="text">
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 3" id="email-3" maxlength="256"
                                                                   name="email-3" placeholder="Twitter"
                                                                   ng-model="project.links.twitter"
                                                                   type="text">
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 4" id="email-4" maxlength="256"
                                                                   name="email-4" placeholder="Google plus"
                                                                   ng-model="project.links.googleplus"
                                                                   type="text">
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 5" id="email-5" maxlength="256"
                                                                   name="email-5" placeholder="Github"
                                                                   ng-model="project.links.github"
                                                                   type="text">
                                                            <i>* Please include http:// at the beginning of the URL</i>
                                                            */ ?>
                                                        </div>
                                                    </div>
                                                    <div class="w-col w-col-6 w-col-stack">
                                                        <div class="padding-left-50">
                                                            <label>Add tags that best describe your project</label>
                                                            <div class="customSelect2">
                                                                <select class="select2 creator-data-entry end w-input"
                                                                        id="tagsSelect" style="width:100%;border:0"
                                                                        multiple data-tags="true"
                                                                        data-placeholder="Write tags">
                                                                    <?php foreach ($tags AS $tag) { ?>
                                                                        <option value="<?php echo $tag->getName() ?>"
                                                                            <?php if (in_array($tag->getName(), $projectTags)) echo 'selected' ?>><?php
                                                                            echo show_input($tag->getName())
                                                                            ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <br/><br/>
                                                            <label>Areas of society impacted</label>
                                                            <p>Which areas of society does you project aim to
                                                                support?</p>
                                                            <div class="customSelect2">
                                                                <select class="select2 creator-data-entry end w-input"
                                                                        id="impact-tags-a" style="width:100%;border:0"
                                                                        multiple data-tags="true"
                                                                        data-placeholder="Write tags">
                                                                    <?php foreach ($impactTags AS $tag) { ?>
                                                                        <option value="<?php echo $tag->getName() ?>"
                                                                            <?php if (in_array($tag->getName(), $projectImpactTagsA)) echo 'selected' ?>><?php
                                                                            echo show_input($tag->getName())
                                                                            ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <br/><br/>
                                                            <label>DSI Focus</label>
                                                            <p>Which of the DSI areas is your project part of?</p>
                                                            <div class="customSelect2">
                                                                <select class="select2 creator-data-entry end w-input"
                                                                        id="impact-tags-b" style="width:100%;border:0"
                                                                        multiple data-placeholder="Write tags">
                                                                    <?php foreach ($dsiFocusTags AS $tag) { ?>
                                                                        <option value="<?php echo $tag->getName() ?>"
                                                                            <?php if (in_array($tag->getName(), $projectImpactTagsB)) echo 'selected' ?>><?php
                                                                            echo show_input($tag->getName())
                                                                            ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <br/><br/>
                                                            <label>Technology type</label>
                                                            <p>What type of digital technology is involved in your
                                                                project?</p>
                                                            <div class="customSelect2">
                                                                <select class="select2 creator-data-entry end w-input"
                                                                        id="impact-tags-c" style="width:100%;border:0"
                                                                        multiple data-tags="true"
                                                                        data-placeholder="Write tags">
                                                                    <?php foreach ($impactTags AS $tag) { ?>
                                                                        <option value="<?php echo $tag->getName() ?>"
                                                                            <?php if (in_array($tag->getName(), $projectImpactTagsC)) echo 'selected' ?>><?php
                                                                            echo show_input($tag->getName())
                                                                            ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <br/><br/>
                                                            <label for="email">Which organisations are working on this
                                                                project?</label>
                                                            <p>Add the organisations who collaborate on this project
                                                                below. Leave blank if there are not any other
                                                                organisations involved.</p>
                                                            <div id="organisationsSelectBox" class="designSelectBox">
                                                                <select
                                                                    class="select2-withDesign creator-data-entry end w-input"
                                                                    id="organisationsSelect"
                                                                    style="width:100%;border:0"
                                                                    multiple
                                                                    data-placeholder="Click to select organisations">
                                                                    <option></option>
                                                                    <?php foreach ($organisations AS $organisation) { ?>
                                                                        <option
                                                                            value="<?php echo $organisation->getId() ?>"
                                                                            data-logo="<?php echo $organisation->getLogoOrDefaultSilver() ?>"
                                                                            data-url="<?php echo $urlHandler->organisation($organisation) ?>"
                                                                            data-type="organisation"
                                                                            <?php if (in_array($organisation->getId(), $projectOrganisations)) echo ' selected '; ?>
                                                                            data-country="<?php echo $organisation->getCountryName() ?>"><?php
                                                                            echo show_input($organisation->getName())
                                                                            ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="w-form-done">
                                                    <div>Thank you! Your submission has been received!</div>
                                                </div>
                                                <div class="w-form-fail">
                                                    <div>Oops! Something went wrong while submitting the form</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="step-window w-tab-pane" ng-class="{'w--tab-active': currentTab == 'step2'}"
                                 data-w-tab="Tab 2">
                                <form id="email-form-3" name="email-form-3" ng-submit="submitStep2()">
                                    <div class="tabbed-nav-buttons w-clearfix">
                                        <input type="submit" class="tab-button-3 tab-button-next w-button"
                                               ng-value="loading ? 'Loading...' : 'Next'"
                                               ng-disabled="loading"
                                               value="Next"/>
                                        <button type="button" class="tab-button-2 tab-button-next w-button"
                                                ng-bind="loading ? 'Loading...' : 'Save'"
                                                ng-click="submitStep2({proceed: false})"
                                                ng-disabled="loading">Save
                                        </button>
                                        <a ng-click="currentTab='step1'"
                                           class="previous tab-button-1 tab-button-next w-button">Previous</a>
                                    </div>
                                    <div class="w-row">
                                        <div class="creator-col w-col w-col-4 w-col-stack">
                                            <h2>2 - Project duration & location</h2>
                                            <?php echo $leftSideText ?>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8 w-col-stack">
                                            <div class="w-form">
                                                <div class="w-row">
                                                    <div class="w-col w-col-6">
                                                        <div class="padding-right-50">
                                                            <h2 class="edit-h2">Duration of project</h2>
                                                            <label for="name">Project start date</label>
                                                            <input class="creator-data-entry w-input" data-name="Name 2"
                                                                   id="start-date" maxlength="256" name="name-2"
                                                                   placeholder="When did the project start?"
                                                                   ng-model="project.startDate"
                                                                   type="text">
                                                            <label for="email-6">Project end date (leave this blank for
                                                                ongoing projects)</label>
                                                            <input class="creator-data-entry end w-input"
                                                                   data-name="Email 6" id="end-date" maxlength="256"
                                                                   name="email-6"
                                                                   ng-model="project.endDate"
                                                                   placeholder="When did/will the project end?"
                                                                   type="text">
                                                        </div>
                                                    </div>
                                                    <div class="w-col w-col-6">
                                                        <div class="padding-left-50">
                                                            <h2 class="edit-h2">Where is your project based?</h2>
                                                            <label for="email-7">
                                                                Which country are you based in?<br/>
                                                                <span style="font-weight:normal">(leave this blank if your project is in multiple countries)</span>
                                                            </label>
                                                            <select id="edit-country" data-placeholder="Select country"
                                                                    class="creator-data-entry w-input"
                                                                    style="width:100%">
                                                                <option></option>
                                                            </select>

                                                            <div ng-show="regionsLoaded">
                                                                <br/>
                                                                <label class="story-label">and in which city?</label>
                                                                <select class="creator-data-entry w-input"
                                                                        data-tags="true" id="edit-countryRegion"
                                                                        data-placeholder="Type the city"
                                                                        style="width:100%">
                                                                </select>
                                                            </div>
                                                            <div ng-show="regionsLoading">
                                                                Loading...
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="w-form-done">
                                                    <div>Thank you! Your submission has been received!</div>
                                                </div>
                                                <div class="w-form-fail">
                                                    <div>Oops! Something went wrong while submitting the form</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="step-window w-tab-pane" ng-class="{'w--tab-active': currentTab == 'step3'}"
                                 data-w-tab="Tab 3">
                                <form id="email-form-3" name="email-form-3" ng-submit="submitStep3()">
                                    <div class="tabbed-nav-buttons w-clearfix">
                                        <input type="submit" class="tab-button-4 tab-button-next w-button"
                                               ng-value="loading ? 'Loading...' : 'Next'"
                                               ng-disabled="loading"
                                               value="Next"/>
                                        <button type="button" class="tab-button-2 tab-button-next w-button"
                                                ng-bind="loading ? 'Loading...' : 'Save'"
                                                ng-click="submitStep3({proceed: false})"
                                                ng-disabled="loading">Save
                                        </button>
                                        <a ng-click="currentTab='step2'"
                                           class="previous tab-button-2 tab-button-next w-button">Previous</a>
                                    </div>
                                    <div class="w-row">
                                        <div class="creator-col w-col w-col-4">
                                            <h2>3 - Describe your project</h2>
                                            <?php echo $leftSideText ?>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8">
                                            <div class="w-form">
                                                <label for="name">Short Description: *</label>
                                                <p>Please provide a short description for your project. This should be
                                                    up to 200 characters long. Think about how you would describe your
                                                    project in a single Tweet</p>
                                                <textarea class="creator-data-entry w-input wide" style="width:100%"
                                                          data-name="Project Bio 3" id="shortDescription"
                                                          name="project-bio-3" ng-model="project.shortDescription"
                                                          placeholder="Briefly describe your project (no more than 140 characters)"
                                                          maxlength="140"></textarea>
                                                <div class="log-in-error" ng-show="errors.shortDescription"
                                                     ng-bind="errors.shortDescription"></div>
                                                <br/>

                                                <label class="story-label" for="project-bio">Long description</label>
                                                <p>Please provide a longer description for your project. How would you
                                                    describe your project? What type of work do you do? Who do you
                                                    support?</p>
                                                <textarea
                                                    class="creator-data-entry long-description w-input wide editableTextarea"
                                                    data-name="Project Bio 4" id="description" maxlength="5000"
                                                    placeholder="Add an in depth project description"
                                                    name="project-bio-4"><?php echo $project->getDescription() ?></textarea>
                                                <label class="story-label" for="project-bio">Social impact</label>
                                                <p>Please provide a description of the social impact your project is
                                                    aiming to have? Which areas of society will your project support?
                                                    Does the project aim to address a particular issue?</p>
                                                <textarea
                                                    class="creator-data-entry long-description w-input wide editableTextarea"
                                                    data-name="Project Bio 5" id="socialImpact" maxlength="5000"
                                                    placeholder="Add an in depth project description"
                                                    name="project-bio-5"><?php echo $project->getSocialImpact() ?></textarea>
                                                <div class="w-form-done">
                                                    <div>Thank you! Your submission has been received!</div>
                                                </div>
                                                <div class="w-form-fail">
                                                    <div>Oops! Something went wrong while submitting the form</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="step-window w-tab-pane" ng-class="{'w--tab-active': currentTab == 'step4'}"
                                 data-w-tab="Tab 4">
                                <form id="email-form-3" name="email-form-3" ng-submit="submitStep4()">
                                    <div class="tabbed-nav-buttons w-clearfix">
                                        <input type="submit" class="tab-button-next tab-button-publish w-button"
                                               ng-value="loading ? 'Loading...' : 'Publish now'"
                                               ng-disabled="loading"
                                               value="Publish now"/>
                                        <a href="<?php echo $urlHandler->project($project) ?>"
                                           class="tab-button-next update-button w-button">Save for later</a>
                                        <a ng-click="currentTab='step3'"
                                           class="previous tab-button-3 tab-button-next w-button">Previous</a>
                                    </div>
                                    <div class="w-row">
                                        <div class="creator-col w-col w-col-4">
                                            <h2>4 - Add images &amp; publish</h2>
                                            <?php echo $leftSideText ?>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8">
                                            <div class="w-form">
                                                <div class="w-row">
                                                    <?php /*
                                                    <div class="w-col w-col-6 w-col-stack">
                                                        <div class="padding-right-50">
                                                            <label for="name">Your project logo</label>
                                                            <p>This will appear wherever we reference your project.</p>
                                                            <img class="story-image-upload"
                                                                 style="max-height:140px;max-width:140px"
                                                                 src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg"
                                                                 ng-src="{{logo.image}}">
                                                            <a class="dsi-button story-image-upload w-button" href="#"
                                                               ngf-select="logo.upload($file, $invalidFiles)"
                                                               ng-bind="logo.loading ? 'Loading...' : 'Upload image'">Upload
                                                                image
                                                            </a>
                                                            <div style="color:red" ng-show="logo.errorMsg.file"
                                                                 ng-cloak>
                                                                {{logo.errorMsg.file}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    */ ?>
                                                    <div class="w-col w-col-6 w-col-stack">
                                                        <div class="padding-left-50">
                                                            <label class="story-label" for="Title">Header background
                                                                image</label>
                                                            <p>This will appear as the header background for your
                                                                project’s page</p>
                                                            <img class="story-image-upload story-image-upload-large"
                                                                 style="max-height:140px;max-width:140px"
                                                                 src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg"
                                                                 ng-src="{{headerImage.image}}">
                                                            <a class="dsi-button story-image-upload w-button" href="#"
                                                               ngf-select="headerImage.upload($file, $invalidFiles)"
                                                               ng-bind="headerImage.loading ? 'Loading...' : 'Upload image'">Upload
                                                                image
                                                            </a>
                                                            <div style="color:red" ng-show="headerImage.errorMsg.file"
                                                                 ng-cloak>
                                                                {{headerImage.errorMsg.file}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="w-col w-col-6 w-col-stack">
                                                        <div class="padding-left-50">
                                                            <div class="small-print">
                                                                Any information, project data or results that you submit
                                                                to Nesta in relation to your project shall be released
                                                                under the terms of a Creative Commons Attribution
                                                                Non-Commercial Share-A-like licence (CC-BY- NC-SA). By
                                                                submitting such information, you warrant to us that you
                                                                have any required permissions, licences or consents to
                                                                do so
                                                            </div>
                                                            <div class="w-checkbox">
                                                                <label class="w-form-label">
                                                                    <input class="w-checkbox-input" data-name="Checkbox"
                                                                           id="checkbox" name="checkbox" type="checkbox"
                                                                           ng-model="project.confirm">
                                                                    I agree
                                                                </label>
                                                            </div>
                                                            <div class="error" ng-bind="errors.confirm"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="w-form-done">
                                                    <div>Thank you! Your submission has been received!</div>
                                                </div>
                                                <div class="w-form-fail">
                                                    <div>Oops! Something went wrong while submitting the form</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            var formatResult = function (object) {
                var element = $(object.element);
                var logo = element.data('logo');
                var elmType = element.data('type');
                if (elmType == 'project')
                    logo = '<?php echo \DSI\Entity\Image::PROJECT_LOGO_URL?>' + logo;
                else if (elmType == 'organisation')
                    logo = '<?php echo \DSI\Entity\Image::ORGANISATION_LOGO_URL?>' + logo;

                return $(
                    '<span>' +
                    (logo ? '<img src="' + logo + '" class="select2-logo" /> ' : '') +
                    object.text +
                    '</span>'
                );
            };
            var formatSelection = function (object) {
                var element = $(object.element);
                var logo = element.data('logo');
                var url = element.data('url');
                var country = element.data('country');
                var elmType = element.data('type');
                if (elmType == 'project')
                    logo = '<?php echo \DSI\Entity\Image::PROJECT_LOGO_URL?>' + logo;
                else if (elmType == 'organisation')
                    logo = '<?php echo \DSI\Entity\Image::ORGANISATION_LOGO_URL?>' + logo;

                return $(
                    '<div class="involved-card">' +
                    '<div class="w-row">' +
                    (
                        logo ?
                            (
                                '<div class="w-col w-col-3 w-col-small-3 w-col-tiny-3">' +
                                '<img class="involved-organisation-img" src="' + logo + '">' +
                                '</div>'
                            ) :
                            (
                                '<div class="w-col w-col-1 w-col-small-1 w-col-tiny-1"></div>'
                            )
                    ) +
                    '<div class="w-clearfix w-col w-col-9 w-col-small-9 w-col-tiny-9">' +
                    '<div class="card-name">' +
                    (object.text.substring(0, 26)) +
                    (object.text.length > 26 ? '...' : '') +
                    '</div>' +
                    '<div class="card-position">' + country + '</div>' +
                    '</div>' +
                    '</div>' +
                    '<a class="view-profile" href="' + url + '" target="_blank">View</a>' +
                    '</div>'
                );
            };

            $('select.select2').select2();
            $('select.select2-withDesign').select2({
                templateResult: formatResult,
                templateSelection: formatSelection
            });

            $("#start-date").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+10"
            });
            $('#end-date').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+50"
            });
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>