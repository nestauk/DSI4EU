<?php
require __DIR__ . '/header.php';
/** @var $organisation \DSI\Entity\Organisation */
/** @var $canUserRequestMembership bool */
/** @var $isOwner bool */
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
                                    ng-model="organisation.description"
                                    ng-blur="updateBasic()"
                                    style="min-height:150px;border:0;width:100%">
                                        <?php echo show_input($organisation->getDescription()) ?>
                                    </textarea>
                            <?php } else { ?>
                                <?php echo show_input($organisation->getDescription()) ?>
                            <?php } ?>
                        </p>
                        <h3 class="org-detail-h3">Type of organisation</h3>
                        <div class="org-detail-p">Social enterprise or foundation</div>
                        <h3 class="org-detail-h3">Size</h3>
                        <div class="org-detail-p">Between 100-500 staff members</div>
                    </div>
                    <div class="info-card map">
                        <h3 class="info-h card-h map">Nesta's location</h3>
                        <div class="map-overlay-address">
                            <div class="overlay-address">Nesta,
                                <br>1 Plough Place,
                                <br>London,
                                <br>EC4A 1DE
                            </div>
                        </div>
                        <div data-widget-latlng="51.511214,-0.119824" data-widget-style="roadmap" data-widget-zoom="12"
                             class="w-widget w-widget-map map"></div>
                    </div>
                    <div class="info-card">
                        <h3 class="info-h card-h">This organisation is tagged under:</h3>
                        <div class="w-clearfix tags-block">
                            <div class="skill">Here is a very long tag</div>
                            <div class="skill">Short</div>
                            <div class="skill">This tag is going to span several lines and is in fact longer</div>
                            <div class="skill">Hardware</div>
                            <div class="skill">Software</div>
                            <div class="skill">Innovation</div>
                            <div class="skill">Skills</div>
                            <div class="add-item-block">
                                <div class="add-item">+</div>
                            </div>
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
                                                <div class="partner-title">Investment and support for social technology
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
                                                <div class="partner-title">Attendal - Creative Business Innovation</div>
                                                <div class="no-of-projects">1 Project</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">Bethnal Green Ventures</div>
                                                <div class="no-of-projects">1 Project</div>
                                            </div>
                                            <div class="w-clearfix list-item">
                                                <div class="partner-title">Manchester Digital Development Agency</div>
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
                                                <div class="partner-title">IMMI (International Modern Media Institute)
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