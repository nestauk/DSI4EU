<?php
require __DIR__ . '/header.php';
/** @var $organisation \DSI\Entity\Organisation */
/** @var $canUserRequestMembership bool */
/** @var $isOwner bool */
/** @var $organisationTypes \DSI\Entity\OrganisationType[] */
/** @var $organisationSizes \DSI\Entity\OrganisationSize[] */
?>
    <script src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/OrganisationController.js"></script>
    <style>
        .card-city {
            position: static;
            float: left;
            margin-left: 0;
            padding-left: 0;
        }
    </style>

    <div
        ng-controller="OrganisationController"
        data-organisationid="<?php echo $organisation->getId() ?>"
        class="w-section project-section">

        <div class="w-container body-container project">
            <div class="w-row project-info">
                <div class="w-col w-col-6">
                    <div class="info-card">
                        <?php /*
                        <div class="org-brand"><img src="<?php echo SITE_RELATIVE_PATH?>/images/nesta-logo.jpg">
                        </div>
                        */ ?>
                        <?php if ($isOwner) { ?>
                            <h3 class="info-h card-h">
                                <input
                                    type="text"
                                    ng-model="organisation.name"
                                    ng-blur="updateBasic()"
                                    value="<?php echo show_input($organisation->getName()) ?>"
                                    style="background:transparent;border:0"/>
                            </h3>
                        <?php } else { ?>
                            <h3 class="info-h card-h"><?php echo show_input($organisation->getName()) ?></h3>
                        <?php } ?>

                        <p class="project-summary">
                            <?php if ($isOwner) { ?>
                                <textarea
                                    class="readjustTextarea"
                                    placeholder="Please type a description"
                                    ng-model="organisation.description"
                                    ng-blur="updateBasic()"
                                    style="min-height:150px;border:0;width:100%">
                                        <?php echo show_input($organisation->getDescription()) ?>
                                    </textarea>
                            <?php } else { ?>
                                <?php echo nl2br(show_input($organisation->getDescription())) ?>
                            <?php } ?>
                        </p>
                        <h3 class="org-detail-h3">Type of organisation</h3>
                        <div class="org-detail-p">
                            <?php if ($isOwner) { ?>
                                <select style="border:0" ng-change="updateBasic()"
                                        ng-model="organisation.organisationTypeId">
                                    <option value="0">
                                        - Please select -
                                    </option>
                                    <?php foreach ($organisationTypes AS $organisationType) { ?>
                                        <option
                                            <?php if ($organisationType->getId() == $organisation->getOrganisationTypeId()) echo 'selected' ?>
                                            value="<?php echo $organisationType->getId() ?>">
                                            <?php echo $organisationType->getName() ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            <?php } else { ?>
                                <?php if ($organisation->getOrganisationType()) { ?>
                                    <?php echo $organisation->getOrganisationType()->getName() ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <h3 class="org-detail-h3">Size</h3>
                        <div class="org-detail-p">
                            <?php if ($isOwner) { ?>
                                <select style="border:0" ng-change="updateBasic()"
                                        ng-model="organisation.organisationSizeId">
                                    <option value="0">
                                        - Please select -
                                    </option>
                                    <?php foreach ($organisationSizes AS $organisationSize) { ?>
                                        <option
                                            <?php if ($organisationSize->getId() == $organisation->getOrganisationSizeId()) echo 'selected' ?>
                                            value="<?php echo $organisationSize->getId() ?>">
                                            <?php echo $organisationSize->getName() ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            <?php } else { ?>
                                <?php if ($organisation->getOrganisationSize()) { ?>
                                    <?php echo $organisation->getOrganisationSize()->getName() ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="info-card">
                        <h3 class="info-h card-h">
                            <div style="float:left">
                                Members
                            </div>
                            <?php if ($isOwner) { ?>
                                <div class="add-item-block" ng-click="addingMember = !addingMember"
                                     style="float:right;margin-right:20px">
                                    <div class="add-item">+</div>
                                </div>
                            <?php } ?>
                            <div style="clear:both"></div>
                        </h3>

                        <form ng-show="addingMember" ng-submit="addMember()">
                            <label>
                                Select new member:
                                <select data-tags="true"
                                        data-placeholder="Select new member"
                                        id="Add-member"
                                        style="width:150px">
                                    <option></option>
                                </select>
                                <input type="submit" value="Add" class="w-button add-skill-btn">
                            </label>

                            <div style="color:red;padding:12px 0 10px 100px;">
                                <div style="color:orange">
                                    <div ng-show="addOrganisationMember.loading">Loading...</div>
                                </div>
                                <div style="color:green">
                                    <div ng-show="addOrganisationMember.success"
                                         ng-bind="addOrganisationMember.success"></div>
                                </div>
                                <div
                                    ng-show="addOrganisationMember.errors.email"
                                    ng-bind="addOrganisationMember.errors.email"></div>
                                <div
                                    ng-show="addOrganisationMember.errors.member"
                                    ng-bind="addOrganisationMember.errors.member"></div>
                            </div>
                        </form>

                        <div style="clear:both"></div>

                        <div class="project-owner">
                            <a href="<?php echo SITE_RELATIVE_PATH ?>/profile/<?php echo $organisation->getOwner()->getId() ?>"
                               class="w-inline-block owner-link">
                                <img width="50" height="50"
                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $organisation->getOwner()->getProfilePicOrDefault() ?>"
                                     class="project-creator-img">
                                <div class="creator-name"><?php echo $organisation->getOwner()->getFirstName() ?></div>
                                <div class="project-creator-text">
                                    <?php echo $organisation->getOwner()->getLastName() ?>
                                </div>
                            </a>
                        </div>
                        <div class="w-row contributors">
                            <div class="w-col w-col-6 contributor-col" ng-repeat="member in organisation.members">
                                <a href="<?php echo SITE_RELATIVE_PATH ?>/profile/{{member.id}}"
                                   class="w-inline-block contributor">
                                    <img width="40" height="40"
                                         ng-src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/{{member.profilePic}}"
                                         class="contributor-small-img">
                                    <div class="contributor-name" ng-bind="member.firstName"></div>
                                    <div class="contributor-position" ng-bind="member.lastName"></div>
                                </a>
                                <?php if ($isOwner) { ?>
                                    <div class="delete" style="display:block" ng-click="removeMember(member)">-
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <?php if ($canUserRequestMembership) { ?>
                            <div class="join-organisation">
                                <div ng-hide="requestToJoin.requestSent">
                                    <a href="#" ng-hide="requestToJoin.loading" class="w-button btn btn-join"
                                       style="position: static;"
                                       ng-click="sendRequestToJoin()">
                                        Request to join
                                    </a>
                                    <button ng-show="requestToJoin.loading" class="w-button btn btn-join"
                                            style="background-color: #ec388e;position: static;">
                                        Sending Request...
                                    </button>
                                </div>
                                <button ng-show="requestToJoin.requestSent" class="w-button btn btn-join"
                                        style="position: static;">
                                    Request Sent
                                </button>
                            </div>
                        <?php } ?>
                    </div>

                    <?php if ($isOwner) { ?>
                        <div class="info-card" style="min-height: 0;" ng-show="organisation.memberRequests.length > 0">
                            <h3 class="info-h card-h">
                                Member Requests
                            </h3>

                            <div class="w-row contributors">
                                <div class="w-col w-col-6 contributor-col"
                                     ng-repeat="member in organisation.memberRequests">
                                    <a href="<?php echo SITE_RELATIVE_PATH ?>/profile/{{member.id}}"
                                       class="w-inline-block contributor">
                                        <img width="40" height="40"
                                             ng-src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/{{member.profilePic}}"
                                             class="contributor-small-img">
                                        <div class="contributor-name" ng-bind="member.firstName"></div>
                                        <div class="contributor-position" ng-bind="member.lastName"></div>
                                    </a>
                                    <div style="margin-left:30px">
                                        <a href="#" title="Approve Member Request" class="add-item"
                                           ng-click="approveRequestToJoin(member)"
                                           style="background-color: green">+</a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="#" title="Reject Member Request" class="add-item"
                                           ng-click="rejectRequestToJoin(member)"
                                           style="background-color: red">-</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="info-card map">
                        <h3 class="info-h card-h map">
                            <span ng-bind="organisation.name"><?php echo $organisation->getName() ?></span>'s location
                        </h3>

                        <div class="map-overlay-address" style="position:static">
                            <div style="margin:10px 10px 0 10px;padding:0">
                                <img src="<?php echo SITE_RELATIVE_PATH ?>/images/pin.png" class="card-pin"
                                     style="position:static;float:left;margin-top:7px;margin-right:10px">
                                <?php if ($isOwner) { ?>
                                    <div class="card-city" style="text-shadow: 0 0 0 #000">
                                        <form ng-submit="saveCountryRegion()">
                                            <select id="Edit-country"
                                                    data-placeholder="Select country"
                                                    style="width:150px;background:transparent">
                                                <option></option>
                                            </select>
                                            <span ng-show="regionsLoaded">
                                                <select
                                                    data-tags="true"
                                                    id="Edit-countryRegion"
                                                    data-placeholder="Type the city"
                                                    style="width:150px;background:transparent">
                                                </select>
                                            </span>
                                            <span ng-show="regionsLoading">
                                                Loading...
                                            </span>
                                            <span ng-show="regionsLoaded">
                                                <input
                                                    ng-hide="savingCountryRegion.loading || savingCountryRegion.saved"
                                                    type="submit" value="Save" class="w-button add-skill-btn">
                                                <button
                                                    ng-show="savingCountryRegion.loading && !savingCountryRegion.saved"
                                                    type="button" class="w-button add-skill-btn">Saving...
                                                </button>
                                                <input
                                                    ng-show="!savingCountryRegion.loading && savingCountryRegion.saved"
                                                    type="submit" value="Saved" class="w-button add-skill-btn">
                                            </span>
                                        </form>
                                    </div>
                                <?php } else { ?>
                                    <div class="card-city">
                                        <?php if ($organisation->getCountryRegion()) { ?>
                                            <?php echo $organisation->getCountryRegion()->getName() ?>,
                                            <?php echo $organisation->getCountry()->getName() ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <div style="clear:both"></div>
                            </div>

                            <div class="overlay-address">
                                <?php if ($isOwner) { ?>
                                    <textarea
                                        class="readjustTextarea"
                                        placeholder="Please type the address"
                                        ng-model="organisation.address"
                                        ng-blur="updateBasic()"
                                        style="min-height:80px;border:0;width:100%;background: transparent">
                                        <?php echo show_input($organisation->getAddress()) ?>
                                    </textarea>
                                <?php } else { ?>
                                    <?php echo nl2br(show_input($organisation->getAddress())) ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="info-card" style="min-height:0">
                        <h3 class="info-h card-h">This organisation is tagged under:</h3>
                        <div class="w-clearfix tags-block">
                            <div class="skill" ng-repeat="tag in organisation.tags">
                                <?php if ($isOwner) { ?>
                                    <div class="delete" ng-click="removeTag(tag)">-</div>
                                <?php } ?>
                                <div ng-bind="tag"></div>
                            </div>
                            <?php if ($isOwner) { ?>
                                <div class="add-item-block" ng-click="addingTag = !addingTag">
                                    <div class="add-item">+</div>
                                </div>

                                <div class="w-form" style="float:left"
                                     ng-show="addingTag">
                                    <form class="w-clearfix add-skill-section"
                                          ng-submit="addTag()">
                                        <select data-tags="true"
                                                data-placeholder="Type your skill"
                                                id="Add-tag"
                                                class="w-input add-language"
                                                style="width:200px;display:inline">
                                            <option></option>
                                        </select>
                                        <input type="submit" value="Add" class="w-button add-skill-btn">
                                    </form>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="w-col w-col-6">
                    <div class="info-card">
                        <div data-duration-in="300" data-duration-out="100" class="w-tabs">
                            <div class="w-tab-menu">
                                <a data-w-tab="Tab 1" class="w-tab-link w-inline-block w--current org-tab">
                                    <div>Projects</div>
                                </a>
                                <a data-w-tab="Tab 2" class="w-tab-link w-inline-block org-tab">
                                    <div>Partner organisations</div>
                                </a>
                            </div>
                            <div class="w-tab-content">
                                <div data-w-tab="Tab 1" class="w-tab-pane w--tab-active">
                                    <div class="list-items">
                                        <div class="w-inline-block partner-link">
                                            <?php if ($isOwner) { ?>
                                                <form method="post" class="list-item" ng-submit="addProject()">
                                                    <div class="partner-title" style="width:100%">
                                                        <input type="text" style="line-height:25px;"
                                                               ng-model="newProjectName" placeholder="Project Name"/>
                                                        <input ng-hide="addNewProject.loading" type="submit"
                                                               value="Create Project" class="w-button add-skill-btn">
                                                        <input ng-show="addNewProject.loading" type="button"
                                                               value="Loading..." class="w-button add-skill-btn">
                                                    </div>
                                                </form>
                                            <?php } ?>
                                            <div class="list-item"
                                                 ng-repeat="project in organisation.organisationProjects">
                                                <div class="partner-title">
                                                    <a ng-href="{{project.url}}" ng-bind="project.name"></a>
                                                </div>
                                                <div class="no-of-projects">
                                                    <span ng-bind="project.organisationsCount"></span>
                                                    Organisation<span
                                                        ng-bind="project.organisationsCount > 1 ? 's' : ''"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div data-w-tab="Tab 2" class="w-tab-pane">
                                    <div class="list-items">
                                        <div class="w-inline-block partner-link">
                                            <div class="w-clearfix list-item"
                                                 ng-repeat="partnerOrganisation in organisation.partnerOrganisations">
                                                <a href="{{partnerOrganisation.url}}" class="partner-title"
                                                   ng-bind="partnerOrganisation.name"></a>
                                                <div class="no-of-projects">
                                                    <span ng-bind="partnerOrganisation.commonProjects"></span>
                                                    Project<span
                                                        ng-bind="partnerOrganisation.commonProjects == 1 ? '' : 's'"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require __DIR__ . '/footer.php' ?>