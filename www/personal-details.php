<?php
require __DIR__ . '/header.php';
/** @var $user \DSI\Entity\User */
/** @var $languages \DSI\Entity\Language[] */
/** @var $userLanguages int[] */
/** @var $skills \DSI\Entity\Skill[] */
/** @var $userSkills string[] */
/** @var $projects \DSI\Entity\Project[] */
/** @var $userProjects int[] */
/** @var $organisations \DSI\Entity\Organisation[] */
/** @var $userOrganisations int[] */
?>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/PersonalDetailsController.js"></script>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/UpdatePasswordController.js"></script>

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

    <div class="change-password-block login-modal modal" ng-controller="UpdatePasswordController">
        <div class="modal-container">
            <div class="modal-helper">
                <div class="modal-content">
                    <h2 class="centered modal-h2 log-in">Change password</h2>
                    <div class="w-form" style="text-align:center">
                        <div data-ix="destroypasswordchange"
                             style="color: silver;font-family: open sans-serif;font-weight: 300"
                             class="close modal-close">+
                        </div>
                        <form id="email-form" name="email-form"
                              ng-submit="savePassword()">
                            <input id="new-password" type="password" placeholder="Enter your new password"
                                   name="new-password" data-name="new password"
                                   class="w-input login-field"
                                   ng-class="{error: errors.password}"
                                   ng-model="password">
                            <div style="color:red" ng-bind="errors.password"></div>
                            <input id="confirm-password" type="password" placeholder="Confirm password"
                                   name="confirm-password" data-name="confirm password"
                                   class="w-input login-field"
                                   ng-class="{error: errors.retypePassword}"
                                   ng-model="retypePassword">
                            <div style="color:red" ng-bind="errors.retypePassword"></div>
                            <input ng-hide="saved" type="submit"
                                   value="Update password"
                                   ng-value="loading ? 'Loading...' : 'Update password'"
                                   ng-disabled="loading"
                                   class="w-button login-button">
                            <div ng-show="saved" class="success-message">Your password has been saved</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require(__DIR__ . '/partialViews/search-results.php'); ?>

    <div ng-controller="PersonalDetailsController">

        <div class="w-section page-header">
            <div class="container-wide header">
                <h1 class="page-h1 light">Edit your Personal Profile</h1>
            </div>
        </div>

        {{currentTab}}

        <div class="creator section-white">
            <div class="container-wide">
                <div class="add-story body-content">
                    <div class="w-tabs" data-easing="linear">
                        <div class="creator-tab-menu w-tab-menu">
                            <a class="step-tab tab-link-1 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step1'}" data-w-tab="Tab 1"
                               ng-click="currentTab = 'step1'">
                                <div>1 - Your details</div>
                            </a>
                            <a class="step-tab tab-link-2 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step2'}" data-w-tab="Tab 2"
                               ng-click="currentTab = 'step2'">
                                <div>2 - Location, Languages &amp; Skills</div>
                            </a>
                            <a class="step-tab tab-link-3 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step3'}" data-w-tab="Tab 3"
                               ng-click="currentTab = 'step3'">
                                <div>3 - Projects &amp; Organisations</div>
                            </a>
                            <a class="step-tab tab-link-4 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step4'}" data-w-tab="Tab 4" id="tab-four"
                               ng-click="currentTab = 'step4'">
                                <div>4 - Publish your Profile</div>
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
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse
                                                varius
                                                enim in eros elementum tristique. Duis cursus, mi quis viverra ornare,
                                                eros
                                                dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus
                                                nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus
                                                tristique posuere.</p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse
                                                varius
                                                enim in eros elementum tristique. Duis cursus, mi quis viverra ornare,
                                                eros
                                                dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus
                                                nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus
                                                tristique posuere.</p>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8">
                                            <div class="w-form">
                                                <div class="w-row">
                                                    <div class="w-col w-col-6 w-col-stack">
                                                        <div class="padding-right-50">
                                                            <label for="name">Your name</label>
                                                            <input class="creator-data-entry w-input" data-name="Name"
                                                                   id="name" maxlength="256" name="name"
                                                                   ng-model="user.firstName"
                                                                   placeholder="First name" type="text">
                                                            <div class="error" ng-bind="errors.firstName"></div>
                                                            <input class="creator-data-entry end w-input"
                                                                   data-name="Name 3" id="name-3" maxlength="256"
                                                                   ng-model="user.lastName"
                                                                   name="name-3" placeholder="Last name" type="text">
                                                            <div class="error" ng-bind="errors.lastName"></div>
                                                            <label for="name">Your profile picture</label>
                                                            <img class="story-image-upload"
                                                                 ng-src="{{user.profilePic}}">
                                                            <a class="dsi-button story-image-upload w-button" href="#"
                                                               ngf-select="profilePic.upload($file, $invalidFiles)"
                                                               ng-bind="profilePic.loading ? 'Loading...' : 'Upload image'">
                                                                Upload image
                                                            </a>
                                                            <div class="error" ng-bind="errors.profilePic"></div>
                                                        </div>
                                                    </div>
                                                    <div class="w-col w-col-6 w-col-stack">
                                                        <div class="padding-left-50">
                                                            <label for="name-5">Tell us about yourself in 140
                                                                characters</label>
                                                            <textarea class="creator-data-entry end w-input" id="field"
                                                                      maxlength="5000" name="field"
                                                                      ng-model="user.bio"
                                                                      placeholder="Quick bio"></textarea>
                                                            <label for="email-9">Your email address</label>
                                                            <input class="creator-data-entry end w-input"
                                                                   data-name="Email 9" id="email-9" maxlength="256"
                                                                   name="email-9" placeholder="Add your email address"
                                                                   ng-model="user.email"
                                                                   required="required" type="text">
                                                            <label for="email-10">Social media links</label>
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 10" id="email-10" maxlength="256"
                                                                   name="email-10" placeholder="Facebook"
                                                                   type="text">
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 3" id="email-3" maxlength="256"
                                                                   name="email-3" placeholder="Twitter"
                                                                   type="text">
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 5" id="email-5" maxlength="256"
                                                                   name="email-5" placeholder="Github"
                                                                   type="text">
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 4" id="email-4" maxlength="256"
                                                                   name="email-4" placeholder="Google plus"
                                                                   type="text">
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
                                               ng-value="loading ? 'Loading...' : 'Save and continue'"
                                               ng-disabled="loading"
                                               value="Save and continue"/>
                                        <a class="previous tab-button-1 tab-button-next w-button"
                                           ng-click="currentTab='step1'">Previous</a>
                                    </div>
                                    <div class="w-row">
                                        <div class="creator-col w-col w-col-4 w-col-stack">
                                            <h2>2 - Location, Languages &amp; Skills</h2>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse
                                                varius
                                                enim in eros elementum tristique. Duis cursus, mi quis viverra ornare,
                                                eros
                                                dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus
                                                nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus
                                                tristique posuere.</p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse
                                                varius
                                                enim in eros elementum tristique. Duis cursus, mi quis viverra ornare,
                                                eros
                                                dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus
                                                nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus
                                                tristique posuere.</p>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8 w-col-stack">
                                            <div class="w-form">
                                                <div class="w-row">
                                                    <div class="w-col w-col-6">
                                                        <div class="padding-right-50">
                                                            <h2 class="edit-h2">Where are you based?</h2>
                                                            <label for="email-7">Which country are you based in?</label>
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 7" id="email-7" maxlength="256"
                                                                   name="email-7" placeholder="Your country"
                                                                   ng-model="user.countryName"
                                                                   required="required" type="text">
                                                            <label for="email-8">and in which city?</label>
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 8" id="email-8" maxlength="256"
                                                                   name="email-8" placeholder="Your city"
                                                                   ng-model="user.cityName"
                                                                   required="required" type="text">
                                                            <h2 class="edit-h2">Languages</h2>
                                                            <label for="name">Which languages do you know?</label>
                                                            <select class="select2 creator-data-entry end w-input"
                                                                    id="languagesSelect" style="width:100%;border:0"
                                                                    multiple
                                                                    data-placeholder="Click to select languages">
                                                                <?php foreach ($languages AS $language) { ?>
                                                                    <option value="<?php echo $language->getId() ?>"
                                                                        <?php if (in_array($language->getId(), $userLanguages)) echo 'selected' ?>><?php
                                                                        echo show_input($language->getName())
                                                                        ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="w-col w-col-6">
                                                        <div class="padding-left-50">
                                                            <h2 class="edit-h2">Your skills</h2>
                                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                                Suspendisse varius enim in eros elementum tristique.
                                                                Duis cursus, mi qui...</p>
                                                            <label for="name-6">What skills do you have?</label>
                                                            <select class="select2 creator-data-entry end w-input"
                                                                    id="skillsSelect" style="width:100%;border:0"
                                                                    multiple data-tags="true"
                                                                    data-placeholder="Write your skills">
                                                                <?php foreach ($skills AS $skill) { ?>
                                                                    <option value="<?php echo $skill->getName() ?>"
                                                                        <?php if (in_array($skill->getName(), $userSkills)) echo 'selected' ?>><?php
                                                                        echo show_input($skill->getName())
                                                                        ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <br/><br/>
                                                            <label for="name-7">Where do you work?</label>
                                                            <input class="creator-data-entry end w-input"
                                                                   data-name="Name 7" id="name-7" maxlength="256"
                                                                   name="name-7" placeholder="Your place of work"
                                                                   ng-model="user.company"
                                                                   type="text">
                                                            <label for="name-8">What's your job title?</label>
                                                            <input class="creator-data-entry end w-input"
                                                                   data-name="Name 8" id="name-8" maxlength="256"
                                                                   ng-model="user.jobTitle"
                                                                   name="name-8" placeholder="Your title" type="text">
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
                                               ng-value="loading ? 'Loading...' : 'Save and continue'"
                                               ng-disabled="loading"
                                               value="Save and continue"/>
                                        <a class="previous tab-button-2 tab-button-next w-button"
                                           ng-click="currentTab='step2'">Previous</a>
                                    </div>
                                    <div class="w-row">
                                        <div class="creator-col w-col w-col-4 w-col-stack">
                                            <h2>3 - Projects &amp; Organisations</h2>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse
                                                varius
                                                enim in eros elementum tristique. Duis cursus, mi quis viverra ornare,
                                                eros
                                                dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus
                                                nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus
                                                tristique posuere.</p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse
                                                varius
                                                enim in eros elementum tristique. Duis cursus, mi quis viverra ornare,
                                                eros
                                                dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus
                                                nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus
                                                tristique posuere.</p>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8 w-col-stack">
                                            <div class="w-form">
                                                <div class="w-row">
                                                    <div class="w-col w-col-6">
                                                        <div class="padding-right-50">
                                                            <h2 class="edit-h2">Projects</h2>
                                                            <div id="projectsSelectBox">
                                                                <select class="select2 creator-data-entry end w-input"
                                                                        id="projectsSelect" style="width:100%;border:0"
                                                                        multiple
                                                                        data-placeholder="Click to select projects">
                                                                    <option></option>
                                                                    <?php foreach ($projects AS $project) { ?>
                                                                        <option value="<?php echo $project->getId() ?>"
                                                                            <?php if (in_array($project->getId(), $userProjects)) echo 'selected' ?>><?php
                                                                            echo show_input($project->getName())
                                                                            ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="w-col w-col-6">
                                                        <div class="padding-left-50">
                                                            <h2 class="edit-h2">Organisations</h2>
                                                            <div id="organisationsSelectBox">
                                                                <select class="select2 creator-data-entry end w-input"
                                                                        id="organisationsSelect"
                                                                        style="width:100%;border:0"
                                                                        multiple
                                                                        data-placeholder="Click to select organisations">
                                                                    <option></option>
                                                                    <?php foreach ($organisations AS $organisation) { ?>
                                                                        <option
                                                                            value="<?php echo $organisation->getId() ?>"
                                                                            <?php if (in_array($organisation->getId(), $userOrganisations)) echo 'selected' ?>><?php
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
                            <div class="step-window w-tab-pane" ng-class="{'w--tab-active': currentTab == 'step4'}"
                                 data-w-tab="Tab 4">
                                <form id="email-form-3" name="email-form-3"
                                      ng-submit="submitStep4('<?php echo \DSI\Service\URL::myProfile() ?>')">
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
                                    <h2>4 - Publish your profile</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim
                                        in
                                        eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor
                                        interdum
                                        nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et justo cursus
                                        id
                                        rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim
                                        in
                                        eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor
                                        interdum
                                        nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et justo cursus
                                        id
                                        rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.</p>
                                    <div class="w-form">
                                        <label>Permission</label>
                                        <div class="small-print">We may use the information you have given us in case
                                            studies and blogs promoted on media owned by ourselves and our partners.
                                        </div>
                                        <div class="w-checkbox">
                                            <label class="w-form-label" for="checkbox">
                                                <input class="w-checkbox-input" id="checkbox"
                                                       name="checkbox" type="checkbox" ng-model="user.confirm">
                                                I agree
                                            </label>
                                        </div>
                                        <div class="error" ng-bind="errors.confirm"></div>
                                        <div class="w-form-done">
                                            <div>Thank you! Your submission has been received!</div>
                                        </div>
                                        <div class="w-form-fail">
                                            <div>Oops! Something went wrong while submitting the form</div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php /*
        <div class="container-wide">
            <div class="body-content add-story">
                <div class="w-form">
                    <form class="w-clearfix" ng-submit="savePersonalDetails()" ng-disabled="loading">
                        <div class="w-row">
                            <div class="w-col w-col-6">
                                <h2 class="edit-h2">Personal info</h2>
                                <label class="story-label" for="First-name">Name</label>
                                <input class="w-input story-form personal" maxlength="256"
                                       placeholder="Add your first name" type="text" ng-model="user.firstName">
                                <label class="story-label" for="surname">Surname</label>
                                <input class="w-input story-form personal" maxlength="256"
                                       placeholder="Add your surname" type="text" ng-model="user.lastName">
                                <label class="story-label" for="surname-2">Email</label>
                                <input class="w-input story-form personal" maxlength="256"
                                       placeholder="Add your email address" type="text" ng-model="user.email">
                                <label class="story-label" for="Story-wysiwyg">Privacy</label>
                                <div class="w-checkbox">
                                    <label class="w-form-label">
                                        <input class="w-checkbox-input" value="1" type="checkbox"
                                               ng-model="user.showEmail">
                                        Show email publicly
                                    </label>
                                </div>
                                <label class="story-label profile-image">
                                    Your profile image
                                </label>
                                <img class="story-image-upload" ng-src="{{user.profilePic}}">
                                <a class="w-button dsi-button story-image-upload" href="#"
                                   ngf-select="profilePic.upload($file, $invalidFiles)"
                                   ng-bind="profilePic.loading ? 'Loading...' : 'Upload image'">
                                    Upload image
                                </a>
                                <div ng-show="profilePic.f.progress > 0 && profilePic.f.progress < 100"
                                     class="">
                                    <div style="font-size:smaller">
                                        <span ng-bind="{{profilePic.name}}"></span>
                                        <span ng-bind="{{profilePic.$error}}"></span>
                                        <span ng-bind="{{profilePic.$errorParam}}"></span>
                                        <span class="progress" ng-show="profilePic.f.progress >= 0">
                                                    <div style="width:{{profilePic.f.progress}}%"
                                                         ng-bind="profilePic.f.progress + '%'"></div>
                                                </span>
                                    </div>
                                    <div style="color:red" ng-bind="{{profilePic.errorMsg.file}}"></div>
                                </div>

                                <?php /* * / ?>
                                <label class="story-label" for="Title">Header background image</label>
                                <img class="story-image-upload story-image-upload-large"
                                     src="images/brussels-locations.jpg">
                                <a class="w-button dsi-button story-image-upload" href="#">Upload image</a>
                                <?php /* * / ?>
                            </div>
                            <div class="w-col w-col-6">
                                <h2 class="edit-h2">Professional Info</h2>
                                <label class="story-label" for="job-title">Job title</label>
                                <input class="w-input story-form personal" maxlength="256"
                                       placeholder="What do you do?" type="text" ng-model="user.jobTitle">
                                <label class="story-label" for="company">Company</label>
                                <input class="w-input story-form personal" maxlength="256"
                                       placeholder="Where do you do it?" type="text" ng-model="user.company">

                                <h2 class="edit-h2">About you</h2>
                                <label class="story-label" for="city">Which city are you based in?</label>
                                <input class="w-input story-form personal" maxlength="256"
                                       placeholder="Your city" type="text" ng-model="user.cityName">
                                <label class="story-label" for="country">In which country?</label>
                                <input class="w-input story-form personal" maxlength="256"
                                       placeholder="Your country" type="text" ng-model="user.countryName">
                                <label class="story-label" for="bio">Your bio</label>
                                <textarea class="w-input story-form" maxlength="5000" ng-model="user.bio"
                                          placeholder="Say something about yourself (maximum 450 characters)"></textarea>
                            </div>
                        </div>
                        <input class="w-button dsi-button post-story" type="submit" value="Update profile"
                               ng-value="loading ? 'Loading...' : 'Update profile'"
                               ng-disabled="loading">
                        <a href="<?php echo \DSI\Service\URL::myProfile() ?>"
                           class="w-button dsi-button post-story cancel">Cancel</a>
                    </form>
                </div>
                <div data-ix="showpasswordchange" class="change-password top-right">Change password</div>
            </div>
        </div>
        <?php /* */ ?>

    </div>

    <script>
        $(function () {
            $('select.select2').select2();
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>