<script
    src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/CreateProjectOrganisationController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"
    type="text/javascript"></script>

<div ng-controller="CreateProjectOrganisationController" data-langpath="<?php echo \DSI\Service\Translate::getCurrentLangPath()?>">
    <div class="create-project-modal modal">
        <div class="modal-container">
            <div class="modal-helper">
                <div class="modal-content">
                    <h2 class="centered modal-h2"><?php _ehtml('Create project') ?></h2>
                    <div class="w-form">
                        <form id="email-form-3" name="email-form-3" ng-submit="createProject()">
                            <div style="color:red;text-align:center" ng-show="project.errors.name"
                                 ng-bind="project.errors.name"></div>
                            <input class="w-input modal-input" id="name-3" maxlength="256" type="text"
                                   name="name" placeholder="<?php _ehtml('Enter the name of your project') ?>"
                                   ng-model="project.name" ng-class="{error: project.errors.name}">
                            <input class="w-button dsi-button creat-button" type="submit"
                                   value="<?php _ehtml('Create +') ?>"
                                   ng-value="project.loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Create +') ?>'"
                                   ng-disabled="project.loading">
                        </form>
                    </div>
                    <div class="cancel" data-ix="close-nu-modal"><?php _ehtml('Cancel') ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="create-organisation-modal modal">
        <div class="modal-container">
            <div class="modal-helper">
                <div class="modal-content">
                    <h2 class="centered modal-h2"><?php _ehtml('Create organisation') ?></h2>
                    <div class="w-form">
                        <form id="email-form-3" name="email-form-3" ng-submit="createOrganisation()">
                            <div style="color:red;text-align:center" ng-show="organisation.errors.name"
                                 ng-bind="organisation.errors.name"></div>
                            <input class="w-input modal-input" id="name-3" maxlength="256" type="text"
                                   name="name" placeholder="<?php _ehtml('Enter the name of your organisation') ?>"
                                   ng-model="organisation.name"
                                   ng-class="{error: organisation.errors.name}">
                            <input class="w-button dsi-button creat-button" data-wait="Please wait..." type="submit"
                                   value="<?php _ehtml('Create +') ?>"
                                   ng-value="organisation.loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Create +') ?>'"
                                   ng-disabled="organisation.loading">
                        </form>
                    </div>
                    <div class="cancel" data-ix="close-nu-modal"><?php _ehtml('Cancel') ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
