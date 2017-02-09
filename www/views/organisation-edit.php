<?php
require __DIR__ . '/header.php';
/** @var $organisation \DSI\Entity\Organisation */
/** @var $organisationTypes \DSI\Entity\OrganisationType[] */
/** @var $organisationSizes \DSI\Entity\OrganisationSize[] */
/** @var $tags \DSI\Entity\TagForOrganisations[] */
/** @var $networkTags \DSI\Entity\NetworkTag[] */
/** @var $projects \DSI\Entity\Project[] */
/** @var $orgTags string[] */
/** @var $orgNetworkTags string[] */
/** @var $orgProjects int[] */

$leftSideText = "<p>" . _html('To add your organisation, we need to understand more about its activities and aims.') . "</p>";
$leftSideText .= "<p>" . _html('Some information is optional (mandatory fields are indicated with an asterisk).') . "</p>";

if (!isset($urlHandler))
    $urlHandler = new \DSI\Service\URL();

?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

    <div ng-controller="OrganisationEditController"
         data-organisationid="<?php echo $organisation->getId() ?>"
         data-organisationurl="<?php echo $urlHandler->organisation($organisation) ?>">
        <div class="creator page-header">
            <div class="container-wide header">
                <h1 class="light page-h1">
                    <?php _ehtml('Edit organisation') ?> -
                    <a href="<?php echo $urlHandler->organisation($organisation) ?>" ng-bind="organisation.name"></a>
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
                                <div>1 - <?php _ehtml('Organisation details') ?></div>
                            </a>
                            <a class="step-tab tab-link-2 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step2'}" data-w-tab="Tab 2"
                               ng-click="currentTab = 'step2'">
                                <div>2 - <?php _ehtml('Organisation Size & Location') ?></div>
                            </a>
                            <a class="step-tab tab-link-3 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step3'}" data-w-tab="Tab 3"
                               ng-click="currentTab = 'step3'">
                                <div>3 - <?php _ehtml('Organisation Description') ?></div>
                            </a>
                            <a class="step-tab tab-link-4 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step4'}" data-w-tab="Tab 4" id="tab-four"
                               ng-click="currentTab = 'step4'">
                                <div>4 - <?php _ehtml('Add images & publish') ?></div>
                            </a>
                        </div>
                        <div class="w-tab-content">
                            <div class="step-window w-tab-pane" ng-class="{'w--tab-active': currentTab == 'step1'}"
                                 data-w-tab="Tab 1">
                                <form id="email-form-3" name="email-form-3" ng-submit="submitStep1()">
                                    <div class="tabbed-nav-buttons w-clearfix">
                                        <input type="submit" class="tab-button-2 tab-button-next w-button"
                                               ng-value="loading ? '<?php _ehtml('Loading') ?>...' : '<?php _ehtml('Next') ?>'"
                                               ng-disabled="loading" value="<?php _ehtml('Next') ?>"/>
                                        <button type="button" class="tab-button-2 tab-button-next w-button"
                                                ng-bind="loading ? '<?php _ehtml('Loading') ?>...' : '<?php _ehtml('Save') ?>'"
                                                ng-click="submitStep1({proceed: false})"
                                                ng-disabled="loading"><?php _ehtml('Save') ?>
                                        </button>
                                    </div>
                                    <div class="w-row">
                                        <div class="creator-col w-col w-col-4">
                                            <h2>1 - <?php _ehtml('Organisation details') ?></h2>
                                            <?php echo $leftSideText ?>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8">
                                            <div class="w-form">
                                                <div class="w-row">
                                                    <div class="w-col w-col-6 w-col-stack">
                                                        <div class="padding-right-50">
                                                            <label for="name"><?php _ehtml('Organisation name') ?></label>
                                                            <input class="creator-data-entry end w-input"
                                                                   data-name="Name"
                                                                   id="name" maxlength="256" name="name"
                                                                   ng-model="organisation.name"
                                                                   placeholder="<?php _ehtml('Organisation name') ?>"
                                                                   type="text">
                                                            <label for="email"><?php _ehtml('Organisation URL') ?></label>
                                                            <input class="creator-data-entry end w-input"
                                                                   data-name="Email"
                                                                   id="email" maxlength="256" name="email"
                                                                   placeholder="http://"
                                                                   ng-model="organisation.url"
                                                                   type="text">
                                                            <label for="email-2"><?php _ehtml('Social media links') ?></label>
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 2"
                                                                   id="email-2" maxlength="256" name="email-2"
                                                                   ng-model="organisation.links.facebook"
                                                                   placeholder="Facebook" type="text">
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 3"
                                                                   id="email-3" maxlength="256" name="email-3"
                                                                   ng-model="organisation.links.twitter"
                                                                   placeholder="Twitter" type="text">
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 4"
                                                                   id="email-4" maxlength="256" name="email-4"
                                                                   ng-model="organisation.links.googleplus"
                                                                   placeholder="Google plus"
                                                                   type="text">
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 5"
                                                                   id="email-5" maxlength="256" name="email-5"
                                                                   ng-model="organisation.links.github"
                                                                   placeholder="Github" type="text">
                                                            <i>* <?php _ehtml('please include http://') ?></i>
                                                            <br/><br/>

                                                            <label><?php _ehtml('Type of Organisation') ?></label>
                                                            <select class="w-select" id="field" name="field"
                                                                    ng-model="organisation.organisationTypeId">
                                                                <?php foreach ($organisationTypes AS $orgType) { ?>
                                                                    <option value="<?php echo $orgType->getId() ?>">
                                                                        <?php echo $orgType->getName() ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                            <br/><br/>
                                                        </div>
                                                    </div>
                                                    <div class="w-col w-col-6 w-col-stack">
                                                        <div class="padding-left-50">

                                                            <label><?php _ehtml('Tags') ?></label>
                                                            <p><?php _ehtml('Please add tags which describe your organisation.') ?></p>
                                                            <div class="customSelect2">
                                                                <select class="select2 creator-data-entry end w-input"
                                                                        id="tagsSelect" style="width:100%;border:0"
                                                                        multiple data-tags="true"
                                                                        data-placeholder="<?php _ehtml('Write tags') ?>">
                                                                    <?php foreach ($tags AS $tag) { ?>
                                                                        <option value="<?php echo $tag->getName() ?>"
                                                                            <?php if (in_array($tag->getName(), $orgTags)) echo 'selected' ?>><?php
                                                                            echo show_input($tag->getName())
                                                                            ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                            <label><?php _ehtml('Networks you belong to') ?></label>
                                                            <p><?php _ehtml('Please list any formal or informal networks which you belong to') ?></p>
                                                            <div class="customSelect2">
                                                                <select class="select2 creator-data-entry end w-input"
                                                                        id="networkTagsSelect"
                                                                        style="width:100%;border:0"
                                                                        multiple data-tags="true"
                                                                        data-placeholder="<?php _ehtml('Write network tags') ?>">
                                                                    <?php foreach ($networkTags AS $tag) { ?>
                                                                        <option value="<?php echo $tag->getName() ?>"
                                                                            <?php if (in_array($tag->getName(), $orgNetworkTags)) echo 'selected' ?>><?php
                                                                            echo show_input($tag->getName())
                                                                            ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <br/>

                                                            <label for="email">
                                                                <?php _ehtml('Projects your organisation is involved with:') ?>
                                                            </label>
                                                            <p>
                                                                <?php _ehtml('Add projects your organisation is involved with here. Leave this blank if you are not currently involved with any projects.') ?>
                                                            </p>
                                                            <div id="projectsSelectBox" class="designSelectBox">
                                                                <select
                                                                        class="select2-withDesign creator-data-entry end w-input"
                                                                        id="projectsSelect" style="width:100%;border:0"
                                                                        multiple
                                                                        data-placeholder="<?php _ehtml('Click to select projects') ?>">
                                                                    <option></option>
                                                                    <?php foreach ($projects AS $project) { ?>
                                                                        <option value="<?php echo $project->getId() ?>"
                                                                                data-url="<?php echo $urlHandler->project($project) ?>"
                                                                                data-country="<?php echo $project->getCountryName() ?>"
                                                                                data-type="project"
                                                                            <?php if (in_array($project->getId(), $orgProjects)) echo 'selected' ?>><?php
                                                                            echo show_input($project->getName())
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
                                        <input type="submit" class="tab-button-2 tab-button-next w-button"
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
                                            <h2>2 - <?php _ehtml('Organisation Size & Location') ?></h2>
                                            <?php echo $leftSideText ?>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8 w-col-stack">
                                            <div class="w-form">
                                                <div class="w-row">
                                                    <div class="w-col w-col-6">
                                                        <div class="padding-right-50">
                                                            <h2 class="edit-h2"><?php _ehtml('When did your organisation start?') ?></h2>
                                                            <label for="name"><?php _ehtml('Organisation start date') ?></label>
                                                            <div class="required">*<?php _ehtml('Required') ?></div>
                                                            <input class="creator-data-entry end w-input"
                                                                   data-name="Name 2"
                                                                   id="startDate" maxlength="256" name="name-2"
                                                                   placeholder="<?php _ehtml('When did your organisation start?') ?>"
                                                                   ng-model="organisation.startDate" type="text">
                                                            <h2 class="edit-h2"><?php _ehtml('Size of Organisation') ?></h2>
                                                            <label><?php _ehtml('Choose the size of your organisation') ?></label>
                                                            <select class="w-select" id="field" name="field"
                                                                    ng-model="organisation.organisationSizeId">
                                                                <?php foreach ($organisationSizes AS $orgSize) { ?>
                                                                    <option value="<?php echo $orgSize->getId() ?>">
                                                                        <?php echo $orgSize->getName() ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                            <div class="required">*<?php _ehtml('Required') ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="w-col w-col-6">
                                                        <div class="padding-left-50" ng-cloak>
                                                            <h2 class="edit-h2"><?php _ehtml('Where is your Organisation based?') ?></h2>
                                                            <label for="email-7"><?php _ehtml('Which country is your organisation based in?') ?></label>
                                                            <select id="edit-country"
                                                                    data-placeholder="<?php _ehtml('Select country') ?>"
                                                                    class="creator-data-entry w-input"
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
                                                                <div class="log-in-error" ng-show="errors.region"
                                                                     ng-bind="errors.region"></div>
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
                                        <input type="submit" class="tab-button-2 tab-button-next w-button"
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
                                            <h2>3 - <?php _ehtml('Describe your organisation') ?></h2>
                                            <?php echo $leftSideText ?>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8">
                                            <div class="w-form">
                                                <label for="name"><?php _ehtml('Short description') ?></label>
                                                <p>
                                                    <?php _ehtml('Please provide a short description for your organisation (up to 140 characters).') ?>
                                                </p>
                                                <textarea class="creator-data-entry end w-input wide"
                                                          name="project-bio-3"
                                                          placeholder="<?php _ehtml('Briefly describe your organisation (no more than 140 characters)') ?>"
                                                          data-name="Project Bio 3" id="project-bio-3" maxlength="5000"
                                                          ng-model="organisation.shortDescription"></textarea>
                                                <label class="story-label" for="project-bio">Long description</label>
                                                <p><?php _ehtml('Please provide a longer description for your organisation. How would you describe your organisation? What type of work do you do? Who do you support?') ?></p>
                                                <p><?php _ehtml('Make your profile stand out by adding images or videos of your work.') ?></p>
                                                <textarea class="creator-data-entry long-description w-input wide"
                                                          data-name="Project Bio 4" id="description" maxlength="5000"
                                                          placeholder="<?php _ehtml('Add an in depth organisation description') ?>"
                                                          name="project-bio-4"><?php echo $organisation->getDescription() ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="step-window w-tab-pane" ng-class="{'w--tab-active': currentTab == 'step4'}"
                                 data-w-tab="Tab 4">
                                <form id="email-form-3" name="email-form-3"
                                      ng-submit="submitStep4('<?php echo $urlHandler->organisation($organisation) ?>')">
                                    <div class="tabbed-nav-buttons w-clearfix">
                                        <input type="submit" class="tab-button-next tab-button-publish w-button"
                                               ng-value="loading ? '<?php _ehtml('Loading') ?>...' : '<?php _ehtml('Publish now') ?>'"
                                               ng-disabled="loading"
                                               value="<?php _ehtml('Publish now') ?>"/>
                                        <a href="<?php echo $urlHandler->organisation($organisation) ?>"
                                           class="tab-button-next update-button w-button"><?php _ehtml('Save for later') ?></a>
                                        <a class="previous tab-button-3 tab-button-next w-button"
                                           ng-click="currentTab='step3'"><?php _ehtml('Previous') ?></a>
                                    </div>
                                    <div class="w-row">
                                        <div class="creator-col w-col w-col-4">
                                            <h2>4 - <?php _ehtml('Add images & publish')?></h2>
                                            <?php echo $leftSideText ?>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8">
                                            <div class="w-form">
                                                <div class="w-row">
                                                    <div class="w-col w-col-6 w-col-stack">
                                                        <div class="padding-left-50">
                                                            <label class="story-label" for="Title">
                                                                <?php _ehtml('Header background image')?>
                                                            </label>
                                                            <p>
                                                                <?php _ehtml("This will appear as the header background for your organisation's page")?>
                                                            </p>
                                                            <img class="story-image-upload story-image-upload-large"
                                                                 style="max-height:140px;max-width:140px"
                                                                 src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg"
                                                                 ng-src="{{headerImage.image}}">
                                                            <a class="dsi-button story-image-upload w-button" href="#"
                                                               ngf-select="headerImage.upload($file, $invalidFiles)"
                                                               ng-bind="headerImage.loading ? '<?php _ehtml('Loading') ?>...' : '<?php _ehtml('Upload image')?>'">
                                                                <?php _ehtml('Upload image')?>
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
                                                                <?php _ehtml('Any information, organisation data or results that you submit to Nesta in relation to your project shall be released under the terms of a licence')?>
                                                            </div>
                                                            <div class="w-checkbox">
                                                                <label class="w-form-label">
                                                                    <input class="w-checkbox-input" data-name="Checkbox"
                                                                           id="checkbox" name="checkbox" type="checkbox"
                                                                           ng-model="organisation.confirm">
                                                                    <?php _ehtml('I agree')?>
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

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/OrganisationEditController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

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
            $("#startDate").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0"
            });
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>