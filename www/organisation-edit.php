<?php
require __DIR__ . '/header.php';
/** @var $organisation \DSI\Entity\Organisation */
/** @var $organisationTypes \DSI\Entity\OrganisationType[] */
/** @var $organisationSizes \DSI\Entity\OrganisationSize[] */
/** @var $tags \DSI\Entity\TagForOrganisations[] */
/** @var $projects \DSI\Entity\Project[] */
/** @var $orgTags string[] */
/** @var $orgProjects string[] */
?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/CaseStudyEditController.js?v=<?php echo \DSI\Service\Sysctl::$version ?>"></script>

    <script src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/OrganisationEditController.js"></script>

    <div class="creator page-header">
        <div class="container-wide header">
            <h1 class="light page-h1">Edit Organisation</h1>
        </div>
    </div>
    <div class="creator section-white" ng-controller="OrganisationEditController"
         data-organisationid="<?php echo $organisation->getId() ?>">
        <div class="container-wide">
            <div class="add-story body-content">
                <div class="w-tabs" data-easing="linear">
                    <div class="creator-tab-menu w-tab-menu">
                        <a class="step-tab tab-link-1 w-inline-block w-tab-link"
                           ng-class="{'w--current': currentTab == 'step1'}" data-w-tab="Tab 1"
                           ng-click="currentTab = 'step1'">
                            <div>1 - Organisation details</div>
                        </a>
                        <a class="step-tab tab-link-2 w-inline-block w-tab-link"
                           ng-class="{'w--current': currentTab == 'step2'}" data-w-tab="Tab 2"
                           ng-click="currentTab = 'step2'">
                            <div>2 - Size, Time &amp; Location</div>
                        </a>
                        <a class="step-tab tab-link-3 w-inline-block w-tab-link"
                           ng-class="{'w--current': currentTab == 'step3'}" data-w-tab="Tab 3"
                           ng-click="currentTab = 'step3'">
                            <div>3 - Organisation Description</div>
                        </a>
                        <a class="step-tab tab-link-4 w-inline-block w-tab-link"
                           ng-class="{'w--current': currentTab == 'step4'}" data-w-tab="Tab 4" id="tab-four"
                           ng-click="currentTab = 'step4'">
                            <div>4 - Publish your Organisation</div>
                        </a>
                    </div>
                    <div class="w-tab-content">
                        <div class="step-window w-tab-pane" ng-class="{'w--tab-active': currentTab == 'step1'}"
                             data-w-tab="Tab 1">
                            <form id="email-form-3" name="email-form-3" ng-submit="submitStep1()">
                                <div class="tabbed-nav-buttons w-clearfix">
                                    <input type="submit" class="tab-button-2 tab-button-next w-button"
                                           ng-value="loading ? 'Loading...' : 'Save and continue'"
                                           ng-disabled="loading"
                                           value="Save and continue"/>
                                </div>
                                <div class="w-row">
                                    <div class="creator-col w-col w-col-4">
                                        <h2>1 - Organisation Details</h2>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius
                                            enim
                                            in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor
                                            interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et
                                            justo
                                            cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique
                                            posuere.</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius
                                            enim
                                            in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor
                                            interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et
                                            justo
                                            cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique
                                            posuere.</p>
                                    </div>
                                    <div class="creator-col creator-col-right w-col w-col-8">
                                        <div class="w-form">
                                            <div class="w-row">
                                                <div class="w-col w-col-6 w-col-stack">
                                                    <div class="padding-right-50">
                                                        <label for="name">Organisation name</label>
                                                        <input class="creator-data-entry end w-input" data-name="Name"
                                                               id="name" maxlength="256" name="name"
                                                               ng-model="organisation.name"
                                                               placeholder="Organisation Name" type="text">
                                                        <label for="email">Organisation URL</label>
                                                        <input class="creator-data-entry end w-input" data-name="Email"
                                                               id="email" maxlength="256" name="email"
                                                               placeholder="Add your organisation&#39;s URL"
                                                               ng-model="organisation.url"
                                                               type="text">
                                                        <label for="email-2">Social media links</label>
                                                        <input class="creator-data-entry w-input" data-name="Email 2"
                                                               id="email-2" maxlength="256" name="email-2"
                                                               ng-model="organisation.links.facebook"
                                                               placeholder="Facebook" type="text">
                                                        <input class="creator-data-entry w-input" data-name="Email 3"
                                                               id="email-3" maxlength="256" name="email-3"
                                                               ng-model="organisation.links.twitter"
                                                               placeholder="Twitter" type="text">
                                                        <input class="creator-data-entry w-input" data-name="Email 4"
                                                               id="email-4" maxlength="256" name="email-4"
                                                               ng-model="organisation.links.googleplus"
                                                               placeholder="Google plus"
                                                               type="text">
                                                        <input class="creator-data-entry w-input" data-name="Email 5"
                                                               id="email-5" maxlength="256" name="email-5"
                                                               ng-model="organisation.links.github"
                                                               placeholder="Github" type="text">
                                                    </div>
                                                </div>
                                                <div class="w-col w-col-6 w-col-stack">
                                                    <div class="padding-left-50">
                                                        <label>Type of Organisation</label>
                                                        <select class="w-select" id="field" name="field"
                                                                ng-model="organisation.organisationTypeId">
                                                            <?php foreach ($organisationTypes AS $orgType) { ?>
                                                                <option
                                                                    value="<?php echo $orgType->getId() ?>"><?php echo $orgType->getName() ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <br/><br/>
                                                        <label>Tag your organisation</label>
                                                        <p>Add tags that best describe your organisation:</p>
                                                        <select class="select2 creator-data-entry end w-input"
                                                                id="tagsSelect" style="width:100%;border:0"
                                                                multiple data-tags="true"
                                                                data-placeholder="Write tags">
                                                            <?php foreach ($tags AS $tag) { ?>
                                                                <option value="<?php echo $tag->getName() ?>"
                                                                    <?php if (in_array($tag->getName(), $orgTags)) echo 'selected' ?>><?php
                                                                    echo show_input($tag->getName())
                                                                    ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <br/><br/>
                                                        <label for="email">Projects your organisation is involved
                                                            with:</label>
                                                        <p>Add projects your organisation is involved with here. Leave
                                                            this blank if you are not currently involved with any
                                                            projects.</p>
                                                        <div id="projectsSelectBox" class="designSelectBox">
                                                            <select
                                                                class="select2-withDesign creator-data-entry end w-input"
                                                                id="projectsSelect" style="width:100%;border:0"
                                                                multiple
                                                                data-placeholder="Click to select projects">
                                                                <option></option>
                                                                <?php foreach ($projects AS $project) { ?>
                                                                    <option value="<?php echo $project->getId() ?>"
                                                                            data-logo="<?php echo $project->getLogoOrDefault() ?>"
                                                                            data-url="<?php echo \DSI\Service\URL::project($project) ?>"
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
                                    <input type="submit" class="tab-button-2 tab-button-next w-button"
                                           ng-value="loading ? 'Loading...' : 'Save and continue'"
                                           ng-disabled="loading"
                                           value="Save and continue"/>
                                    <a ng-click="currentTab='step1'"
                                       class="previous tab-button-1 tab-button-next w-button">Previous</a>
                                </div>
                                <div class="w-row">
                                    <div class="creator-col w-col w-col-4 w-col-stack">
                                        <h2>2 - Size, Time &amp; Location</h2>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius
                                            enim
                                            in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor
                                            interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et
                                            justo
                                            cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique
                                            posuere.</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius
                                            enim
                                            in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor
                                            interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et
                                            justo
                                            cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique
                                            posuere.</p>
                                    </div>
                                    <div class="creator-col creator-col-right w-col w-col-8 w-col-stack">
                                        <div class="w-form">
                                            <div class="w-row">
                                                <div class="w-col w-col-6">
                                                    <div class="padding-right-50">
                                                        <h2 class="edit-h2">When did your organisation start?</h2>
                                                        <label for="name">Organisation start date</label>
                                                        <div class="required">*Required</div>
                                                        <input class="creator-data-entry end w-input" data-name="Name 2"
                                                               id="startDate" maxlength="256" name="name-2"
                                                               placeholder="When did your organisation start?"
                                                               ng-model="organisation.startDate"
                                                               type="text">
                                                        <h2 class="edit-h2">Size of Organisation</h2>
                                                        <select class="w-select" id="field" name="field"
                                                                ng-model="organisation.organisationSizeId">
                                                            <?php foreach ($organisationSizes AS $orgSize) { ?>
                                                                <option
                                                                    value="<?php echo $orgSize->getId() ?>"><?php echo $orgSize->getName() ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <label for="field">Choose the size of your organisation</label>
                                                        <div class="required">*Required</div>
                                                    </div>
                                                </div>
                                                <div class="w-col w-col-6">
                                                    <div class="padding-left-50" ng-cloak>
                                                        <h2 class="edit-h2">Where is your Organisation based?</h2>
                                                        <label for="email-7">Which country are you based in?</label>
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
                                    <input type="submit" class="tab-button-2 tab-button-next w-button"
                                           ng-value="loading ? 'Loading...' : 'Save and continue'"
                                           ng-disabled="loading"
                                           value="Save and continue"/>
                                    <a ng-click="currentTab='step2'"
                                       class="previous tab-button-2 tab-button-next w-button">Previous</a>
                                </div>
                                <div class="w-row">
                                    <div class="creator-col w-col w-col-4">
                                        <h2>3 - Describe your project</h2>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius
                                            enim
                                            in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor
                                            interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et
                                            justo
                                            cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique
                                            posuere.</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius
                                            enim
                                            in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor
                                            interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et
                                            justo
                                            cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique
                                            posuere.</p>
                                    </div>
                                    <div class="creator-col creator-col-right w-col w-col-8">
                                        <div class="w-form">
                                            <label for="name">Short Description</label>
                                            <p>Please provide a short organisation description. How would you describe
                                                your project in one tweet?
                                                <br>(This will appear on your project card)</p>
                                            <textarea class="creator-data-entry end w-input wide"
                                                      data-name="Project Bio 3" id="project-bio-3" maxlength="5000"
                                                      name="project-bio-3" ng-model="organisation.shortDescription"
                                                      placeholder="Briefly describe your organisation (no more than 140 characters)"></textarea>
                                            <label class="story-label" for="project-bio">Long description</label>
                                            <p>Please provide a longer organisation description. How would you describe
                                                your project.......? (This will appear on your project page)</p>
                                            <textarea class="creator-data-entry long-description w-input wide"
                                                      data-name="Project Bio 4" id="description" maxlength="5000"
                                                      placeholder="Add an in depth organisation description"
                                                      name="project-bio-4"><?php echo $organisation->getDescription() ?></textarea>
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
                            <form id="email-form-3" name="email-form-3"
                                  ng-submit="submitStep4('<?php echo \DSI\Service\URL::organisation($organisation) ?>')">
                                <div class="tabbed-nav-buttons w-clearfix">
                                    <input type="submit" class="tab-button-next tab-button-publish w-button"
                                           ng-value="loading ? 'Loading...' : 'Publish now'"
                                           ng-disabled="loading"
                                           value="Publish now"/>
                                    <a href="<?php echo \DSI\Service\URL::home() ?>"
                                       class="tab-button-next update-button w-button">Save for later</a>
                                    <a ng-click="currentTab='step3'"
                                       class="previous tab-button-3 tab-button-next w-button">Previous</a>
                                </div>
                                <div class="w-row">
                                    <div class="creator-col w-col w-col-4">
                                        <h2>4 - Add images &amp; publish</h2>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius
                                            enim
                                            in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor
                                            interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et
                                            justo
                                            cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique
                                            posuere.</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius
                                            enim
                                            in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor
                                            interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et
                                            justo
                                            cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique
                                            posuere.</p>
                                    </div>
                                    <div class="creator-col creator-col-right w-col w-col-8">
                                        <div class="w-form">
                                            <div class="w-row">
                                                <div class="w-col w-col-6 w-col-stack">
                                                    <div class="padding-right-50">
                                                        <label for="name">Your organisation's logo</label>
                                                        <p>This will appear wherever we reference your organisation</p>
                                                        <img class="story-image-upload"
                                                             style="max-height:140px;max-width:140px"
                                                             src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg"
                                                             ng-src="{{logo.image}}">
                                                        <a class="dsi-button story-image-upload w-button" href="#"
                                                           ngf-select="logo.upload($file, $invalidFiles)"
                                                           ng-bind="logo.loading ? 'Loading...' : 'Upload image'">Upload
                                                            image
                                                        </a>
                                                        <div style="color:red" ng-show="logo.errorMsg.file" ng-cloak>
                                                            {{logo.errorMsg.file}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="w-col w-col-6 w-col-stack">
                                                    <div class="padding-left-50">
                                                        <label class="story-label" for="Title">Header background
                                                            image</label>
                                                        <p>This will appear as the header background for your
                                                            organisation's page</p>
                                                        <img class="story-image-upload story-image-upload-large"
                                                             style="max-height:140px;max-width:140px"
                                                             src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg"
                                                             ng-src="{{headerImage.image}}">
                                                        <a class="dsi-button story-image-upload w-button" href="#"
                                                           ngf-select="headerImage.upload($file, $invalidFiles)"
                                                           ng-bind="headerImage.loading ? 'Loading...' : 'Upload image'">Upload
                                                            image
                                                        </a>
                                                        <div style="color:red" ng-show="headerImage.errorMsg.file" ng-cloak>
                                                            {{headerImage.errorMsg.file}}
                                                        </div>
                                                        <div class="small-print">We may use the information you have
                                                            given us in case studies and blogs promoted on media owned
                                                            by ourselves and our partners.
                                                        </div>
                                                        <div class="w-checkbox">
                                                            <label class="w-form-label">
                                                                <input class="w-checkbox-input" data-name="Checkbox"
                                                                       id="checkbox" name="checkbox" type="checkbox"
                                                                       ng-model="organisation.confirm">
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

    <script>
        $(function () {
            var formatResult = function (object) {
                var element = $(object.element);
                var logo = element.data('logo');
                var elmType = element.data('type');
                if (elmType == 'project')
                    logo = '<?php echo \DSI\Entity\Image::PROJECT_LOGO_URL?>' + logo;

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
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>