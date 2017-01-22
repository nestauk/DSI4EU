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

$leftSideText = "<p>" . _html('To add your project, we need to understand more about its activities and aims. The more information you provide, the easier it will be for others to find out about your work.') . "</p>";
$leftSideText .= "<p>" . _html('Some information is optional (mandatory fields are indicated with an asterisk). You can edit answers later.') . "</p>";

if (!isset($urlHandler))
    $urlHandler = new \DSI\Service\URL();

?>
    <div ng-controller="ProjectEditController"
         data-projectid="<?php echo $project->getId() ?>"
         data-projecturl="<?php echo $urlHandler->project($project) ?>">
        <div class="creator page-header">
            <div class="container-wide header">
                <h1 class="light page-h1">
                    <?php _ehtml('Edit project') ?> -
                    <a href="<?php echo $urlHandler->project($project) ?>" ng-bind="project.name"></a>
                </h1>
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
                                <div>1 - <?php _ehtml('Project details') ?></div>
                            </a>
                            <a class="step-tab tab-link-2 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step2'}" data-w-tab="Tab 2"
                               ng-click="currentTab = 'step2'">
                                <div>2 - <?php _ehtml('Project duration & location') ?></div>
                            </a>
                            <a class="step-tab tab-link-3 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step3'}" data-w-tab="Tab 3"
                               ng-click="currentTab = 'step3'">
                                <div>3 - <?php _ehtml('Project Description') ?></div>
                            </a>
                            <a class="step-tab tab-link-4 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step4'}" data-w-tab="Tab 4" id="tab-four"
                               ng-click="currentTab = 'step4'">
                                <div>4 - <?php _ehtml('Publish your project') ?></div>
                            </a>
                        </div>
                        <div class="w-tab-content">
                            <div class="step-window w-tab-pane" ng-class="{'w--tab-active': currentTab == 'step1'}"
                                 data-w-tab="Tab 1">
                                <form id="email-form-3" name="email-form-3" ng-submit="submitStep1()">
                                    <div class="tabbed-nav-buttons w-clearfix">
                                        <input type="submit" class="tab-button-2 tab-button-next w-button"
                                               ng-value="loading ? '<?php _ehtml('Loading') ?>...' : '<?php _ehtml('Next') ?>'"
                                               ng-disabled="loading"
                                               value="<?php _ehtml('Next') ?>"/>
                                        <button type="button" class="tab-button-2 tab-button-next w-button"
                                                ng-bind="loading ? '<?php _ehtml('Loading') ?>...' : '<?php _ehtml('Save') ?>'"
                                                ng-click="submitStep1({proceed: false})"
                                                ng-disabled="loading"><?php _ehtml('Save') ?>
                                        </button>
                                    </div>
                                    <div class="w-row">
                                        <div class="creator-col w-col w-col-4">
                                            <h2>1 - <?php _ehtml('Project details') ?></h2>
                                            <?php echo $leftSideText ?>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8">
                                            <div class="w-form">
                                                <div class="w-row">
                                                    <div class="w-col w-col-6 w-col-stack">
                                                        <div class="padding-right-50">
                                                            <label for="name"><?php _ehtml('Project name') ?> * </label>
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Name" id="name" maxlength="256"
                                                                   name="name" placeholder="Project Name"
                                                                   ng-model="project.name"
                                                                   type="text">
                                                            <div class="log-in-error" ng-show="errors.name"
                                                                 ng-bind="errors.name"></div>
                                                            <br/>

                                                            <label for="email"><?php _ehtml('Project website') ?></label>
                                                            <input class="creator-data-entry w-input" data-name="Email"
                                                                   id="email" maxlength="256" name="email" type="text"
                                                                   placeholder="http://" ng-model="project.url"/>
                                                            <i><?php _ehtml('please include http://') ?></i>
                                                            <br/><br/>
                                                            <label><?php _ehtml('Areas of impact') ?></label>
                                                            <p>
                                                                <?php _ehtml('In which area(s) of society is your project seeking to have impact? For example: education, democracy, culture, health, work, regeneration, environment, science, finance.') ?>
                                                            </p>
                                                            <div class="customSelect2">
                                                                <select class="select2 creator-data-entry end w-input"
                                                                        id="impact-tags-a" style="width:100%;border:0"
                                                                        multiple data-tags="true"
                                                                        data-placeholder="<?php _ehtml('Write tags') ?>">
                                                                    <?php foreach ($impactTags AS $tag) { ?>
                                                                        <option value="<?php echo $tag->getName() ?>"
                                                                            <?php if (in_array($tag->getName(), $projectImpactTagsA)) echo 'selected' ?>><?php
                                                                            echo show_input($tag->getName())
                                                                            ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <br/><br/>

                                                            <label><?php _ehtml('Your focus') ?></label>
                                                            <p>
                                                                <?php _ehtml('Please tag the category or categories of DSI to which your project belongs.') ?>
                                                            </p>

                                                            <?php foreach ($dsiFocusTags AS $tag) { ?>
                                                                <label>
                                                                    <input type="checkbox" name="focusTags[]"
                                                                        <?php if (in_array($tag->getName(), $projectImpactTagsB)) echo 'checked' ?>
                                                                           value="<?php echo $tag->getName() ?>"/>
                                                                    <strong><?php echo show_input($tag->getName()) ?></strong>
                                                                    <br/>
                                                                    <span style="font-weight: normal">
                                                                        <?php _ehtml($tag->getDescription()) ?>
                                                                    </span>
                                                                </label>
                                                                <br/>
                                                            <?php } ?>
                                                            <div class="log-in-error" ng-show="errors.focusTags"
                                                                 ng-bind="errors.focusTags"></div>
                                                            <br/><br/>

                                                            <label><?php _ehtml('Your technology') ?></label>
                                                            <p>
                                                                <?php _ehtml('Please add tags which describe the technology your project uses.') ?>
                                                            </p>
                                                            <div class="customSelect2">
                                                                <select class="select2 creator-data-entry end w-input"
                                                                        id="impact-tags-c" style="width:100%;border:0"
                                                                        multiple data-tags="true"
                                                                        data-placeholder="<?php _ehtml('Write tags') ?>">
                                                                    <?php foreach ($impactTags AS $tag) { ?>
                                                                        <option value="<?php echo $tag->getName() ?>"
                                                                            <?php if (in_array($tag->getName(), $projectImpactTagsC)) echo 'selected' ?>><?php
                                                                            echo show_input($tag->getName())
                                                                            ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <br/><br/>

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
                                                            <label><?php _ehtml('Tags') ?></label>
                                                            <p><?php _ehtml('Please add tags which describe your project') ?></p>
                                                            <div class="customSelect2">
                                                                <select class="select2 creator-data-entry end w-input"
                                                                        id="tagsSelect" style="width:100%;border:0"
                                                                        multiple data-tags="true"
                                                                        data-placeholder="<?php _ehtml('Write tags') ?>">
                                                                    <?php foreach ($tags AS $tag) { ?>
                                                                        <option value="<?php echo $tag->getName() ?>"
                                                                            <?php if (in_array($tag->getName(), $projectTags)) echo 'selected' ?>><?php
                                                                            echo show_input($tag->getName())
                                                                            ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="log-in-error" ng-show="errors.tags"
                                                                 ng-bind="errors.tags"></div>
                                                            <br/><br/>

                                                            <label for="email">
                                                                <?php _ehtml('Which organisations are working on this project?') ?>
                                                            </label>
                                                            <p>
                                                                <?php _ehtml('Add the organisations who collaborate on the project below [...]') ?>
                                                            </p>
                                                            <div id="organisationsSelectBox" class="designSelectBox">
                                                                <select multiple id="organisationsSelect"
                                                                        class="select2-withDesign creator-data-entry end w-input"
                                                                        style="width:100%;border:0"
                                                                        data-placeholder="<?php _ehtml('Click to select organisations') ?>">
                                                                    <option></option>
                                                                    <?php foreach ($organisations AS $organisation) { ?>
                                                                        <option
                                                                                value="<?php echo $organisation->getId() ?>"
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
                                               ng-value="loading ? '<?php _ehtml('Loading') ?>...' : '<?php _ehtml('Next') ?>'"
                                               ng-disabled="loading"
                                               value="<?php _ehtml('Next') ?>"/>
                                        <button type="button" class="tab-button-2 tab-button-next w-button"
                                                ng-bind="loading ? '<?php _ehtml('Loading') ?>...' : '<?php _ehtml('Save') ?>'"
                                                ng-click="submitStep2({proceed: false})"
                                                ng-disabled="loading"><?php _ehtml('Save') ?>
                                        </button>
                                        <a ng-click="currentTab='step1'"
                                           class="previous tab-button-1 tab-button-next w-button">Previous</a>
                                    </div>
                                    <div class="w-row">
                                        <div class="creator-col w-col w-col-4 w-col-stack">
                                            <h2>2 - <?php _ehtml('Project duration & location') ?></h2>
                                            <?php echo $leftSideText ?>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8 w-col-stack">
                                            <div class="w-form">
                                                <div class="w-row">
                                                    <div class="w-col w-col-6">
                                                        <div class="padding-right-50">
                                                            <h2 class="edit-h2"><?php _ehtml('Duration of project') ?></h2>
                                                            <label for="name"><?php _ehtml('Project start date') ?></label>
                                                            <input class="creator-data-entry w-input" data-name="Name 2"
                                                                   id="start-date" maxlength="256" name="name-2"
                                                                   placeholder="<?php _ehtml('When did the project start?') ?>"
                                                                   ng-model="project.startDate" type="text">
                                                            <br/>

                                                            <label for="email-6">
                                                                <?php _ehtml('Project end date (leave this blank for ongoing projects)') ?>
                                                            </label>
                                                            <input class="creator-data-entry end w-input"
                                                                   data-name="Email 6" id="end-date" maxlength="256"
                                                                   name="email-6" ng-model="project.endDate" type="text"
                                                                   placeholder="<?php _ehtml('When did/will the project end?') ?>">
                                                        </div>
                                                    </div>
                                                    <div class="w-col w-col-6">
                                                        <div class="padding-left-50">
                                                            <h2 class="edit-h2"><?php _ehtml('Where is your project based?') ?></h2>
                                                            <label for="email-7">
                                                                <?php _ehtml('Which country is your project based in?') ?>
                                                                <br/>
                                                                <span style="font-weight:normal">
                                                                    (
                                                                    <?php _ehtml('leave this blank if your project is in multiple countries') ?>
                                                                    )
                                                                </span>
                                                            </label>
                                                            <select id="edit-country" class="creator-data-entry w-input"
                                                                    data-placeholder="<?php _ehtml('Select country') ?>"
                                                                    style="width:100%">
                                                                <option></option>
                                                            </select>

                                                            <div ng-show="regionsLoaded">
                                                                <br/>
                                                                <label class="story-label"><?php _ehtml('And in which city?') ?></label>
                                                                <select class="creator-data-entry w-input"
                                                                        data-tags="true" id="edit-countryRegion"
                                                                        data-placeholder="<?php _ehtml('Type the city') ?>"
                                                                        style="width:100%">
                                                                </select>
                                                            </div>
                                                            <div ng-show="regionsLoading">
                                                                <?php _ehtml('Loading') ?>...
                                                            </div>
                                                        </div>
                                                    </div>
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
                                               ng-value="loading ? '<?php _ehtml('Loading') ?>...' : '<?php _ehtml('Next') ?>'"
                                               ng-disabled="loading"
                                               value="<?php _ehtml('Next') ?>"/>
                                        <button type="button" class="tab-button-2 tab-button-next w-button"
                                                ng-bind="loading ? '<?php _ehtml('Loading') ?>...' : '<?php _ehtml('Save') ?>'"
                                                ng-click="submitStep3({proceed: false})"
                                                ng-disabled="loading"><?php _ehtml('Save') ?>
                                        </button>
                                        <a ng-click="currentTab='step2'"
                                           class="previous tab-button-2 tab-button-next w-button">Previous</a>
                                    </div>
                                    <div class="w-row">
                                        <div class="creator-col w-col w-col-4">
                                            <h2>3 - <?php _ehtml('Describe your project') ?></h2>
                                            <?php echo $leftSideText ?>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8">
                                            <div class="w-form">
                                                <label for="name"><?php _ehtml('Short description') ?>: *</label>
                                                <p>
                                                    <?php _ehtml('Please provide a short description for your project (up to 140 characters) [...]') ?>
                                                </p>
                                                <textarea class="creator-data-entry w-input wide" style="width:100%"
                                                          data-name="Project Bio 3" id="shortDescription"
                                                          name="project-bio-3" ng-model="project.shortDescription"
                                                          placeholder="<?php _ehtml('Briefly describe your project (no more than 140 characters)') ?>"
                                                          maxlength="140"></textarea>
                                                <div class="log-in-error" ng-show="errors.shortDescription"
                                                     ng-bind="errors.shortDescription"></div>
                                                <br/>

                                                <label class="story-label"
                                                       for="project-bio"><?php _ehtml('Long description') ?></label>
                                                <p><?php _ehtml('Please provide a longer description for your project. [...]') ?></p>
                                                <p><?php _ehtml('Make your project stand out by adding images or videos of your work') ?></p>
                                                <textarea
                                                        class="creator-data-entry long-description w-input wide editableTextarea"
                                                        data-name="Project Bio 4" id="description" maxlength="5000"
                                                        placeholder="<?php _ehtml('Add an in depth project description') ?>"
                                                        name="project-bio-4"><?php echo $project->getDescription() ?></textarea>
                                                <br/>

                                                <label class="story-label" for="project-bio">
                                                    <?php _ehtml('Your social impact') ?>
                                                </label>
                                                <p>
                                                    <?php _ehtml('Please provide a description of the social impact your project is aiming to have. [...]') ?>
                                                </p>
                                                <textarea
                                                        class="creator-data-entry long-description w-input wide editableTextarea"
                                                        data-name="Project Bio 5" id="socialImpact" maxlength="5000"
                                                        placeholder="<?php _ehtml('Add an in depth project description') ?>"
                                                        name="project-bio-5"><?php echo $project->getSocialImpact() ?></textarea>
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
                                               ng-value="loading ? '<?php _ehtml('Loading') ?>...' : '<?php _ehtml('Publish now') ?>'"
                                               ng-disabled="loading"
                                               value="<?php _ehtml('Publish now') ?>"/>
                                        <a href="<?php echo $urlHandler->project($project) ?>"
                                           class="tab-button-next update-button w-button">
                                            <?php _ehtml('Save for later') ?></a>
                                        <a ng-click="currentTab='step3'"
                                           class="previous tab-button-3 tab-button-next w-button">
                                            <?php _ehtml('Previous') ?></a>
                                    </div>
                                    <div class="w-row">
                                        <div class="creator-col w-col w-col-4">
                                            <h2>4 - <?php _ehtml('Add images & publish') ?></h2>
                                            <?php echo $leftSideText ?>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8">
                                            <div class="w-form">
                                                <div class="w-row">
                                                    <div class="w-col w-col-6 w-col-stack">
                                                        <div class="padding-left-50">
                                                            <label class="story-label" for="Title">
                                                                <?php _ehtml('Header background image') ?>
                                                            </label>
                                                            <p>
                                                                <?php _ehtml("This will appear as the header background for your project's page") ?>
                                                            </p>
                                                            <img class="story-image-upload story-image-upload-large"
                                                                 style="max-height:140px;max-width:140px"
                                                                 src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg"
                                                                 ng-src="{{headerImage.image}}">
                                                            <a class="dsi-button story-image-upload w-button" href="#"
                                                               ngf-select="headerImage.upload($file, $invalidFiles)"
                                                               ng-bind="headerImage.loading ? '<?php _ehtml('Loading') ?>...' : '<?php _ehtml('Upload image') ?>'">
                                                                <?php _ehtml('Upload image') ?>
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
                                                                <?php _ehtml('Any information, project data or results that you submit to Nesta in relation to your project shall be released under the terms of a licence [...]') ?>
                                                            </div>
                                                            <div class="w-checkbox">
                                                                <label class="w-form-label">
                                                                    <input class="w-checkbox-input" data-name="Checkbox"
                                                                           id="checkbox" name="checkbox" type="checkbox"
                                                                           ng-model="project.confirm">
                                                                    <?php _ehtml('I agree') ?>
                                                                </label>
                                                            </div>
                                                            <div class="error" ng-bind="errors.confirm"></div>
                                                        </div>
                                                    </div>
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

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ProjectEditController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <script>
        $(function () {
            var formatResult = function (object) {
                var element = $(object.element);
                var elmType = element.data('type');

                return $(
                    '<span>' +
                    object.text +
                    '</span>'
                );
            };
            var formatSelection = function (object) {
                var element = $(object.element);
                var url = element.data('url');
                var country = element.data('country');
                var elmType = element.data('type');

                return $(
                    '<span class="info-card left small w-inline-block" href="">' +
                    '<h3 class="info-card-h3">' +
                    (object.text.substring(0, 35)) +
                    (object.text.length > 35 ? '...' : '') +
                    '</h3>' +
                    '<div class="involved-tag">' +
                    '<a href="' + url + '" target="_blank">View</a>' +
                    '</div>' +
                    '</span>'
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