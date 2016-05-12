<?php
require __DIR__ . '/header.php';
/** @var $organisation \DSI\Entity\Organisation */
/** @var $canUserRequestMembership bool */
/** @var $isOwner bool */
/** @var $organisationTypes \DSI\Entity\OrganisationType[] */
/** @var $organisationSizes \DSI\Entity\OrganisationSize[] */
?>
    <script src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/OrganisationController.js"></script>

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
                    <div class="info-card map">
                        <h3 class="info-h card-h map">
                            <span ng-bind="organisation.name"><?php echo $organisation->getName() ?></span>'s location
                        </h3>
                        <div class="map-overlay-address" style="position:static">
                            <div class="overlay-address">
                                <?php /*
                                Nesta,<br>
                                1 Plough Place,<br>
                                London,<br>
                                EC4A 1DE
                                */ ?>

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
                    <div class="info-card">
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
                                        <a href="#" class="w-inline-block partner-link">
                                            <div class="list-item">
                                                <div class="partner-title">Make things do stuff</div>
                                                <div class="no-of-projects">3 Organisations</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">Digital Social Innovation</div>
                                                <div class="no-of-projects">14 Organisations</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">D-CENT</div>
                                                <div class="no-of-projects">7 Organisations</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">Civic Exchange</div>
                                                <div class="no-of-projects">6 Organisations</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">Investment and support for social
                                                    technology
                                                    start-ups
                                                </div>
                                                <div class="no-of-projects">5 Organisations</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">The Civic Crowd</div>
                                                <div class="no-of-projects">1 Organisations</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">Commons4EU</div>
                                                <div class="no-of-projects">6 Organisations</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div data-w-tab="Tab 2" class="w-tab-pane">
                                    <div class="list-items">
                                        <a href="#" class="w-inline-block partner-link">
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">Waag Society</div>
                                                <div class="no-of-projects">3 Projects</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">Esade</div>
                                                <div class="no-of-projects">3 Projects</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">Forum Virium Helsinki</div>
                                                <div class="no-of-projects">3 Projects</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">Arduino</div>
                                                <div class="no-of-projects">1 Project</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">Attendal - Creative Business Innovation
                                                </div>
                                                <div class="no-of-projects">1 Project</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">Bethnal Green Ventures</div>
                                                <div class="no-of-projects">1 Project</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">Manchester Digital Development Agency
                                                </div>
                                                <div class="no-of-projects">1 Project</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">City of Amsterdam</div>
                                                <div class="no-of-projects">1 Project</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">Barcelona &nbsp;Media</div>
                                                <div class="no-of-projects">1 Project</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">IMMI (International Modern Media
                                                    Institute)
                                                </div>
                                                <div class="no-of-projects">1 Project</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">Dyne.org</div>
                                                <div class="no-of-projects">1 Project</div>
                                            </div>
                                        </a>
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