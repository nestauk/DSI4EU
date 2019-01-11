<?php
require __DIR__ . '/header.php';
/** @var $languages \DSI\Entity\Language[] */
/** @var $userLanguages int[] */
/** @var $skills \DSI\Entity\Skill[] */
/** @var $userSkills string[] */
/** @var $projects \DSI\Entity\Project[] */
/** @var $userProjects int[] */
/** @var $organisations \DSI\Entity\Organisation[] */
/** @var $userOrganisations int[] */
/** @var $urlHandler Services\URL */
/** @var $loggedInUser \DSI\Entity\User */
/** @var $user \DSI\Entity\User */

$leftSideText = "<p>" . _html('To create your profile, we would like to collect some information about you.') . "</p>";
$leftSideText .= "<p>" . _html('You can edit any of your answers later.') . "</p>";

?>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ProfileEditController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/UpdatePasswordController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

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
                    <h2 class="centered modal-h2 log-in"><?php _ehtml('Change password') ?></h2>
                    <div class="w-form" style="text-align:center">
                        <div data-ix="destroypasswordchange"
                             style="color: silver;font-family: open sans-serif;font-weight: 300"
                             class="close modal-close">+
                        </div>
                        <form id="email-form" name="email-form"
                              ng-submit="savePassword()">
                            <input id="new-password" type="password"
                                   placeholder="<?php _ehtml('Enter your new password') ?>"
                                   name="new-password" data-name="new password"
                                   class="w-input login-field" ng-class="{error: errors.password}"
                                   ng-model="password">
                            <div style="color:red" ng-bind="errors.password"></div>
                            <input id="confirm-password" type="password"
                                   placeholder="<?php _ehtml('Confirm password') ?>"
                                   name="confirm-password" data-name="confirm password"
                                   class="w-input login-field" ng-class="{error: errors.retypePassword}"
                                   ng-model="retypePassword">
                            <div style="color:red" ng-bind="errors.retypePassword"></div>
                            <input ng-hide="saved" type="submit"
                                   value="<?php _ehtml('Update password') ?>" ng-disabled="loading"
                                   class="w-button login-button"
                                   ng-value="loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Update password') ?>'">
                            <div ng-show="saved"
                                 class="success-message"><?php _ehtml('Your password has been changed') ?></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div ng-controller="ProfileEditController"
         data-urlprofile="<?php echo $urlHandler->profile($user) ?>"
    >

        <div class="w-section page-header">
            <div class="container-wide header">
                <h1 class="page-h1 light">
                    <?php _ehtml('Edit') ?> -
                    <a href="<?php echo $urlHandler->profile($user) ?>">
                        <?php _ehtml('Your Personal Profile') ?>
                    </a>
                </h1>
            </div>
        </div>

        <div class="creator section-white">
            <div class="container-wide">
                <div class="add-story body-content">
                    <div class="w-tabs" data-easing="linear">
                        <div class="creator-tab-menu w-tab-menu edit-personal-profile">
                            <a class="step-tab tab-link-1 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step1'}"
                               ng-click="changeCurrentTab('step1')">
                                <div>1 - <?php _ehtml('Your details') ?></div>
                            </a>
                            <a class="step-tab tab-link-2 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step2'}"
                               ng-click="changeCurrentTab('step2')">
                                <div>2 - <?php _ehtml('Location & work') ?></div>
                            </a>
                            <a class="step-tab tab-link-3 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step3'}"
                               ng-click="changeCurrentTab('step3')">
                                <div>3 - <?php _ehtml('Projects & Organisations') ?></div>
                            </a>
                            <?php /*
                            <a class="step-tab tab-link-4 w-inline-block w-tab-link"
                               ng-class="{'w--current': currentTab == 'step4'}" data-w-tab="Tab 4" id="tab-four"
                               ng-click="currentTab = 'step4'">
                                <div>4 - Publish your Profile</div>
                            </a>
                            */ ?>
                        </div>
                        <div class="w-tab-content">
                            <div class="step-window w-tab-pane" ng-class="{'w--tab-active': currentTab == 'step1'}"
                                 data-w-tab="Tab 1">
                                <form ng-submit="submitStep1()">
                                    <div class="tabbed-nav-buttons w-clearfix">
                                        <input type="submit" class="tab-button-2 tab-button-next w-button"
                                               ng-value="loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Next') ?>'"
                                               ng-disabled="loading" value="<?php _ehtml('Next') ?>"/>
                                        <button type="button" class="tab-button-2 tab-button-next w-button"
                                                ng-bind="loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Save') ?>'"
                                                ng-click="submitStep1({alert: true})"
                                                ng-disabled="loading"><?php _ehtml('Save') ?>
                                        </button>
                                    </div>
                                    <div class="w-row">
                                        <div class="creator-col w-col w-col-4">
                                            <h2>1 - <?php _ehtml('Your details') ?></h2>
                                            <?php echo $leftSideText ?>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8">
                                            <div class="w-form">
                                                <div class="w-row">
                                                    <div class="w-col w-col-6 w-col-stack">
                                                        <div class="padding-right-50">
                                                            <label for="name"><?php _ehtml('Your name') ?> *</label>
                                                            <input class="creator-data-entry w-input" data-name="Name"
                                                                   id="name" maxlength="256" name="name"
                                                                   placeholder="<?php _ehtml('First name') ?>"
                                                                   ng-model="user.firstName" type="text">
                                                            <div class="error" ng-bind="errors.firstName"></div>
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Name 3" maxlength="256"
                                                                   placeholder="<?php _ehtml('Last name') ?>"
                                                                   ng-model="user.lastName" type="text">
                                                            <div class="error" ng-bind="errors.lastName"></div>
                                                            <br/>

                                                            <label for="name">
                                                                <?php _ehtml('Your profile picture') ?>
                                                            </label>
                                                            <p>
                                                                <?php _ehtml('This will appear on your profile page and when you post a comment.') ?>
                                                            </p>
                                                            <img class="story-image-upload"
                                                                 ng-src="{{user.profilePic}}">
                                                            <a class="dsi-button story-image-upload w-button" href="#"
                                                               ngf-select="profilePic.upload($file, $invalidFiles)"
                                                               ng-bind="profilePic.loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Upload image') ?>'">
                                                                <?php _ehtml('Upload image') ?>
                                                            </a>
                                                            <div class="error" ng-bind="errors.profilePic"></div>
                                                        </div>
                                                    </div>
                                                    <div class="w-col w-col-6 w-col-stack">
                                                        <div class="padding-left-50">
                                                            <label for="name-5">
                                                                <?php // _ehtml('Tell us about yourself in 140 characters') ?>
                                                                <?php _ehtml('About you') ?>
                                                            </label>
                                                            <textarea class="creator-data-entry w-input" id="field"
                                                                      maxlength="140" name="field" ng-model="user.bio"
                                                                      placeholder="<?php _ehtml('Tell us about yourself and your work') ?>"></textarea>
                                                            <div class="error" ng-bind="errors.bio"></div>
                                                            <br/>

                                                            <label
                                                                    for="email-9"><?php _ehtml('Your email address') ?>
                                                                *</label>
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 9" id="email-9" maxlength="256"
                                                                   placeholder="<?php _ehtml('Your email address') ?>"
                                                                   name="email-9" ng-model="user.email"
                                                                   required="required" type="text">
                                                            <div class="error" ng-bind="errors.email"></div>
                                                            <br/>

                                                            <label for="email-10">
                                                                <?php _ehtml('Social media links') ?>
                                                            </label>
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 10" id="email-10" maxlength="256"
                                                                   name="email-10" placeholder="Facebook"
                                                                   ng-model="user.links.facebook" type="text">
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 3" id="email-3" maxlength="256"
                                                                   name="email-3" placeholder="Twitter"
                                                                   ng-model="user.links.twitter" type="text">
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 5" id="email-5" maxlength="256"
                                                                   name="email-5" placeholder="Github"
                                                                   ng-model="user.links.github" type="text">
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 4" id="email-4" maxlength="256"
                                                                   name="email-4" placeholder="Google +"
                                                                   ng-model="user.links.googleplus" type="text">
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 4" maxlength="256" type="text"
                                                                   placeholder="<?php _ehtml('Personal Website') ?>"
                                                                   ng-model="user.links.other" name="email-4">
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
                                <form ng-submit="submitStep2()">
                                    <div class="tabbed-nav-buttons w-clearfix">
                                        <input type="submit" class="tab-button-3 tab-button-next w-button"
                                               ng-value="loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Next') ?>'"
                                               ng-disabled="loading"
                                               value="<?php _ehtml('Next') ?>"/>
                                        <button type="button" class="tab-button-2 tab-button-next w-button"
                                                ng-bind="loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Save') ?>'"
                                                ng-click="submitStep2({proceed: false})"
                                                ng-disabled="loading"><?php _ehtml('Save') ?>
                                        </button>
                                        <a class="previous tab-button-1 tab-button-next w-button"
                                           ng-click="currentTab='step1'"><?php _ehtml('Previous') ?></a>
                                    </div>
                                    <div class="w-row">
                                        <div class="creator-col w-col w-col-4 w-col-stack">
                                            <h2>2 - <?php _ehtml('Your location & work') ?></h2>
                                            <?php echo $leftSideText ?>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8 w-col-stack">
                                            <div class="w-form">
                                                <div class="w-row">
                                                    <div class="w-col w-col-6">
                                                        <div class="padding-right-50">
                                                            <h2 class="edit-h2"><?php _ehtml('Where are you based?') ?></h2>
                                                            <label
                                                                    for="email-7"><?php _ehtml('Country') ?></label>
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 7" id="email-7" maxlength="256"
                                                                   name="email-7"
                                                                   placeholder="<?php _ehtml('Your country') ?>"
                                                                   ng-model="user.countryName"
                                                                   type="text">
                                                            <label for="email-8">
                                                                <?php _ehtml('City') ?>
                                                            </label>
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Email 8" id="email-8" maxlength="256"
                                                                   name="email-8"
                                                                   placeholder="<?php _ehtml('Your city') ?>"
                                                                   ng-model="user.cityName"
                                                                   type="text">
                                                            <br/>
                                                        </div>
                                                    </div>
                                                    <div class="w-col w-col-6">
                                                        <div class="padding-left-50">
                                                            <?php /*
                                                            <h2 class="edit-h2"><?php _ehtml('Your skills') ?></h2>
                                                            <p><?php _ehtml('Would you like to offer your support to other DSI organisations and projects?') ?></p>
                                                            <label
                                                                    for="name-6"><?php _ehtml('What skills do you have?') ?></label>
                                                            <div class="customSelect2">
                                                                <select class="select2 creator-data-entry end w-input"
                                                                        id="skillsSelect" style="width:100%;border:0"
                                                                        multiple data-tags="true"
                                                                        data-placeholder="<?php _ehtml('Write your skills') ?>">
                                                                    <?php foreach ($skills AS $skill) { ?>
                                                                        <option value="<?php echo $skill->getName() ?>"
                                                                            <?php if (in_array($skill->getName(), $userSkills)) echo 'selected' ?>><?php
                                                                            echo show_input($skill->getName())
                                                                            ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <br/><br/>
                                                            <br>
                                                            <label for="name-7"><?php _ehtml('Your work') ?></label>
                                                            */ ?>
                                                            <h2 class="edit-h2">
                                                                <?php _ehtml('Your work') ?></h2>
                                                            <label for="name-8">
                                                                <?php _ehtml('Where do you work?') ?>
                                                            </label>
                                                            <input class="creator-data-entry w-input"
                                                                   data-name="Name 7" id="name-7" maxlength="256"
                                                                   name="name-7"
                                                                   placeholder="<?php _ehtml('Your place of work') ?>"
                                                                   ng-model="user.company"
                                                                   type="text">


                                                            <label for="name-8">
                                                                <?php _ehtml("What’s your role?") ?>
                                                            </label>
                                                            <input class="creator-data-entry end w-input"
                                                                   data-name="Name 8" id="name-8" maxlength="256"
                                                                   ng-model="user.jobTitle"
                                                                   name="name-8"
                                                                   placeholder="<?php _ehtml('Your job title') ?>"
                                                                   type="text">
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
                                <form ng-submit="submitStep3()">
                                    <div class="tabbed-nav-buttons w-clearfix">
                                        <input type="submit" class="tab-button-4 tab-button-next w-button"
                                               ng-value="loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Finish') ?>'"
                                               ng-disabled="loading"
                                               value="<?php _ehtml('Finish') ?>"/>
                                        <a class="previous tab-button-2 tab-button-next w-button"
                                           ng-click="currentTab='step2'"><?php _ehtml('Previous') ?></a>
                                    </div>
                                    <div class="w-row">
                                        <div class="creator-col w-col w-col-4 w-col-stack">
                                            <h2>3 - <?php _ehtml('Your projects & organisations') ?></h2>
                                            <?php echo $leftSideText ?>
                                        </div>
                                        <div class="creator-col creator-col-right w-col w-col-8 w-col-stack">
                                            <div class="w-form">
                                                <div class="w-row">
                                                    <div class="w-col w-col-6">
                                                        <div class="padding-right-50">
                                                            <h2 class="edit-h2"><?php _ehtml('Projects') ?></h2>
                                                            <p><?php _ehtml('Add DSI projects that you are involved with here.') ?></p>
                                                            <div id="projectsSelectBox" class="designSelectBox">
                                                                <select
                                                                        class="select2-withDesign creator-data-entry end w-input"
                                                                        id="projectsSelect" style="width:100%;border:0"
                                                                        multiple
                                                                        data-placeholder="<?php _ehtml('Select projects') ?>"
                                                                >
                                                                    <option></option>
                                                                    <?php foreach ($projects AS $project) { ?>
                                                                        <option value="<?php echo $project->getId() ?>"
                                                                                data-url="<?php echo $urlHandler->project($project) ?>"
                                                                                data-country="<?php echo $project->getCountryName() ?>"
                                                                                data-type="project"
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
                                                            <h2 class="edit-h2"><?php _ehtml('Organisations') ?></h2>
                                                            <p><?php _ehtml('Add DSI organisations that you are involved with here.') ?></p>
                                                            <div id="organisationsSelectBox" class="designSelectBox">
                                                                <select
                                                                        class="select2-withDesign creator-data-entry end w-input"
                                                                        id="organisationsSelect"
                                                                        style="width:100%;border:0"
                                                                        multiple
                                                                        data-placeholder="<?php _ehtml('Select organisations') ?>">
                                                                    <option></option>
                                                                    <?php foreach ($organisations AS $organisation) { ?>
                                                                        <option
                                                                                value="<?php echo $organisation->getId() ?>"
                                                                                data-url="<?php echo $urlHandler->organisation($organisation) ?>"
                                                                                data-country="<?php echo $organisation->getCountryName() ?>"
                                                                                data-type="organisation"
                                                                            <?php if (in_array($organisation->getId(), $userOrganisations)) echo 'selected' ?>><?php
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
                    (object.text.substring(0, 26)) +
                    (object.text.length > 26 ? '...' : '') +
                    '</h3>' +
                    '<div class="involved-tag">' +
                    '<a href="' + url + '" target="_blank">View</a>' +
                    '</div>' +
                    '</span>'
                );
            };

            $('select.select2').select2();

			$('select.select2-withDesign').each(function(){
				var self = $(this);
				self.select2({
					templateResult: formatResult,
					templateSelection: formatSelection
				});
				self.on('select2:select', function (e) {
					self.parent()
                        .find('input')
                        .attr("placeholder", self.data('placeholder'));
				});
            });
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>