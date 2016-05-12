<?php
require __DIR__ . '/header.php';
/** @var $userID int */
/** @var $user \DSI\Entity\User */
?>
    <script type="text/javascript">
        profileUserID = '<?php echo $userID?>';
    </script>
    <script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH?>/js/controllers/UserController.js"></script>
    <div ng-controller="UserController as ctrl">

        <div class="w-container body-container">
            <div class="profile-main">
                <div class="profile-header">
                    <div
                        class="public-profile-name">
                        <?php echo $user->getFirstName() ?>
                        <?php echo $user->getLastName() ?>
                    </div>
                    <div class="public-profile-position email"><a
                            href="mailto:<?php echo $user->getEmail() ?>?subject=DSI%20email%20contact"><?php echo $user->getEmail() ?></a>
                    </div>
                    <?php /* <div class="public-profile-position">DSI Digital Product Manager</div> */ ?>
                    <?php if ($user->getLocation()) { ?>
                        <div class="w-clearfix location"><img width="60" src="<?php echo SITE_RELATIVE_PATH?>/images/pin.png" class="location-pin">
                            <div class="location-info"><?php echo $user->getLocation() ?></div>
                        </div>
                    <?php } ?>
                    <div class="profile-img-main"
                         style="background-image: url('<?php echo SITE_RELATIVE_PATH?>/images/users/profile/<?php echo $user->getProfilePicOrDefault() ?>');"></div>
                    <div class="profile-blur el-blur"></div>
                    <div class="profile-overlay"></div>
                </div>
                <div class="public-profile-content">
                    <div data-duration-in="300" data-duration-out="100" data-easing="ease-in-out" class="w-tabs">
                        <div class="w-tab-menu w-clearfix public-profile-tabs-menu">
                            <a data-w-tab="Tab 1" class="w-tab-link w-inline-block public-profile-tab">
                                <div>Organisations</div>
                            </a>
                            <a data-w-tab="Tab 2" class="w-tab-link w-inline-block public-profile-tab">
                                <div>Projects</div>
                            </a>
                            <a data-w-tab="Tab 4" class="w-tab-link w-inline-block w--current public-profile-tab">
                                <div>Profile</div>
                            </a>
                        </div>
                        <div class="w-tab-content public-profile-tabs-content">
                            <div data-w-tab="Tab 1" class="w-tab-pane">
                                <div class="project-list-card">
                                    <div class="w-row profile-project-list">
                                        <div class="w-col w-col-3">
                                            <div class="joined-on">
                                                <div class="joined">Joined organisation on:</div>
                                                <div><strong>30th March 2016</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-col w-col-9 w-clearfix profile-project-detail"><img
                                                src="<?php echo SITE_RELATIVE_PATH?>/images/nesta-6a9b5fe999e8323b379ccc0d8e70290f.png">
                                            <p class="profile-project-descr org">Lorem ipsum dolor sit amet, consectetur
                                                adipiscing elit. Suspendisse varius enim in eros elementum
                                                tristique...</p>
                                            <div class="w-clearfix profile-project-team-members"><img width="40"
                                                                                                      height="40"
                                                                                                      src="<?php echo SITE_RELATIVE_PATH?>/images/Screen Shot 2016-03-29 at 15.15.30.png"
                                                                                                      class="team-member-small"><img
                                                    width="40" height="40" src="<?php echo SITE_RELATIVE_PATH?>/images/meghan-benton-wv-250x250.jpg"
                                                    class="team-member-small"><img width="40" height="40"
                                                                                   src="<?php echo SITE_RELATIVE_PATH?>/images/Screen Shot 2016-03-29 at 14.50.51.png"
                                                                                   class="team-member-small"><img
                                                    width="40"
                                                    height="40"
                                                    src="<?php echo SITE_RELATIVE_PATH?>/images/images.jpg"
                                                    class="team-member-small">
                                            </div>
                                            <a href="#" class="view-project">View organisation</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="project-list-card">
                                    <div class="w-row profile-project-list">
                                        <div class="w-col w-col-3">
                                            <div class="joined-on">
                                                <div class="joined">Joined project on:</div>
                                                <div><strong>30th March 2016</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-col w-col-9 w-clearfix profile-project-detail"><img
                                                src="<?php echo SITE_RELATIVE_PATH?>/images/waag-f1d052f43133268eaf2e13090a0b4bf1.png">
                                            <p class="profile-project-descr org">Lorem ipsum dolor sit amet, consectetur
                                                adipiscing elit. Suspendisse varius enim in eros &nbsp;tristique...em
                                                ipsum
                                                dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in
                                                eros
                                                &nbsp;tristique...em ipsum dolor sit amet, consectetur adipiscing elit.
                                                Suspendisse varius enim in eros &nbsp;tristique...em ipsum dolor sit
                                                amet,
                                                consectetur adipiscing elit. Suspendisse varius enim in eros &nbsp;tristique...em
                                                ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius
                                                enim
                                                in eros &nbsp;tristique...em ipsum dolor sit amet, consectetur
                                                adipiscing
                                                elit. Suspendisse varius enim in eros &nbsp;tristique...</p>
                                            <div class="w-clearfix profile-project-team-members"><img width="40"
                                                                                                      height="40"
                                                                                                      src="<?php echo SITE_RELATIVE_PATH?>/images/Screen Shot 2016-03-29 at 14.50.51.png"
                                                                                                      class="team-member-small"><img
                                                    width="40" height="40" src="<?php echo SITE_RELATIVE_PATH?>/images/images.jpg"
                                                    class="team-member-small">
                                            </div>
                                            <a href="#" class="view-project">View organisation</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="project-list-card">
                                    <div class="w-row profile-project-list">
                                        <div class="w-col w-col-3">
                                            <div class="joined-on">
                                                <div class="joined">Joined project on:</div>
                                                <div><strong>30th March 2016</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-col w-col-9 w-clearfix profile-project-detail"><img
                                                src="<?php echo SITE_RELATIVE_PATH?>/images/future-everything-2f261cf2d078264179fd82b21e5927b7.png">
                                            <p class="profile-project-descr org">Lorem ipsum dolor sit amet, consectetur
                                                adipiscing elit. Suspendisse varius enim in eros elementum
                                                tristique...</p>
                                            <div class="w-clearfix profile-project-team-members"><img width="40"
                                                                                                      height="40"
                                                                                                      src="<?php echo SITE_RELATIVE_PATH?>/images/Screen Shot 2016-03-29 at 15.15.30.png"
                                                                                                      class="team-member-small"><img
                                                    width="40" height="40" src="<?php echo SITE_RELATIVE_PATH?>/images/meghan-benton-wv-250x250.jpg"
                                                    class="team-member-small"><img width="40" height="40"
                                                                                   src="<?php echo SITE_RELATIVE_PATH?>/images/Screen Shot 2016-03-29 at 14.50.51.png"
                                                                                   class="team-member-small">
                                            </div>
                                            <a href="#" class="view-project">View organisation</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div data-w-tab="Tab 2" class="w-tab-pane">
                                <div class="add-project-block">
                                    <div class="w-row join">
                                        <div class="w-col w-col-9">
                                            <div class="w-form">
                                                <form id="email-form-3" name="email-form-3" data-name="Email Form 3"
                                                      class="w-clearfix">
                                                    <input id="Add-existing-project" type="text"
                                                           placeholder="Join existing project"
                                                           name="Add-existing-project"
                                                           data-name="Add existing project" class="w-input add-project">
                                                    <input type="submit" value="Join" data-wait="Searching for project"
                                                           wait="Searching for project" class="w-button add-btn">
                                                </form>
                                                <div class="w-form-done">
                                                    <p>Thank you! Your submission has been received!</p>
                                                </div>
                                                <div class="w-form-fail">
                                                    <p>Oops! Something went wrong while submitting the form</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-col w-col-3 btn-col"><a href="#" class="w-button btn">Create new&nbsp;project</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="project-list-card">
                                    <div class="w-row profile-project-list">
                                        <div class="w-col w-col-3">
                                            <div class="joined-on">
                                                <div class="joined">Joined project on:</div>
                                                <div><strong>30th March 2016</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-col w-col-9 w-clearfix profile-project-detail">
                                            <h3 class="project-summary-h3">DSI4EU</h3>
                                            <p class="profile-project-descr">Lorem ipsum dolor sit amet, consectetur
                                                adipiscing elit. Suspendisse varius enim in eros elementum
                                                tristique...</p>
                                            <div class="w-clearfix profile-project-team-members"><img width="40"
                                                                                                      height="40"
                                                                                                      src="<?php echo SITE_RELATIVE_PATH?>/images/Screen Shot 2016-03-29 at 15.15.30.png"
                                                                                                      class="team-member-small"><img
                                                    width="40" height="40" src="<?php echo SITE_RELATIVE_PATH?>/images/meghan-benton-wv-250x250.jpg"
                                                    class="team-member-small"><img width="40" height="40"
                                                                                   src="<?php echo SITE_RELATIVE_PATH?>/images/Screen Shot 2016-03-29 at 14.50.51.png"
                                                                                   class="team-member-small"><img
                                                    width="40"
                                                    height="40"
                                                    src="<?php echo SITE_RELATIVE_PATH?>/images/images.jpg"
                                                    class="team-member-small">
                                            </div>
                                            <a href="#" class="view-project">View project</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="project-list-card">
                                    <div class="w-row profile-project-list">
                                        <div class="w-col w-col-3">
                                            <div class="joined-on">
                                                <div class="joined">Joined project on:</div>
                                                <div><strong>30th March 2016</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-col w-col-9 w-clearfix profile-project-detail">
                                            <h3 class="project-summary-h3">Open Data Challenge Series</h3>
                                            <p class="profile-project-descr">Lorem ipsum dolor sit amet, consectetur
                                                adipiscing elit. Suspendisse varius enim in eros elementum
                                                tristique...</p>
                                            <div class="w-clearfix profile-project-team-members"><img width="40"
                                                                                                      height="40"
                                                                                                      src="<?php echo SITE_RELATIVE_PATH?>/images/Screen Shot 2016-03-29 at 14.50.51.png"
                                                                                                      class="team-member-small"><img
                                                    width="40" height="40" src="<?php echo SITE_RELATIVE_PATH?>/images/images.jpg"
                                                    class="team-member-small">
                                            </div>
                                            <a href="#" class="view-project">View project</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="project-list-card">
                                    <div class="w-row profile-project-list">
                                        <div class="w-col w-col-3">
                                            <div class="joined-on">
                                                <div class="joined">Joined project on:</div>
                                                <div><strong>30th March 2016</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-col w-col-9 w-clearfix profile-project-detail">
                                            <h3 class="project-summary-h3">RaspberryPi</h3>
                                            <p class="profile-project-descr">Lorem ipsum dolor sit amet, consectetur
                                                adipiscing elit. Suspendisse varius enim in eros elementum
                                                tristique...</p>
                                            <div class="w-clearfix profile-project-team-members"><img width="40"
                                                                                                      height="40"
                                                                                                      src="<?php echo SITE_RELATIVE_PATH?>/images/Screen Shot 2016-03-29 at 15.15.30.png"
                                                                                                      class="team-member-small"><img
                                                    width="40" height="40" src="<?php echo SITE_RELATIVE_PATH?>/images/meghan-benton-wv-250x250.jpg"
                                                    class="team-member-small"><img width="40" height="40"
                                                                                   src="<?php echo SITE_RELATIVE_PATH?>/images/Screen Shot 2016-03-29 at 14.50.51.png"
                                                                                   class="team-member-small">
                                            </div>
                                            <a href="#" class="view-project">View project</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="project-list-card">
                                    <div class="w-row profile-project-list">
                                        <div class="w-col w-col-3">
                                            <div class="joined-on">
                                                <div class="joined">Joined project on:</div>
                                                <div><strong>30th March 2016</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-col w-col-9 w-clearfix profile-project-detail">
                                            <h3 class="project-summary-h3">Nesta project</h3>
                                            <p class="profile-project-descr">Lorem ipsum dolor sit amet, consectetur
                                                adipiscing elit. Suspendisse varius enim in eros elementum
                                                tristique...</p>
                                            <div class="w-clearfix profile-project-team-members"><img width="40"
                                                                                                      height="40"
                                                                                                      src="<?php echo SITE_RELATIVE_PATH?>/images/Screen Shot 2016-03-29 at 15.15.30.png"
                                                                                                      class="team-member-small"><img
                                                    width="40" height="40"
                                                    src="<?php echo SITE_RELATIVE_PATH?>/images/Screen Shot 2016-03-29 at 14.50.51.png"
                                                    class="team-member-small"><img width="40" height="40"
                                                                                   src="<?php echo SITE_RELATIVE_PATH?>/images/images.jpg"
                                                                                   class="team-member-small">
                                            </div>
                                            <a href="#" class="view-project">View project</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="project-list-card">
                                    <div class="w-row profile-project-list">
                                        <div class="w-col w-col-3">
                                            <div class="joined-on">
                                                <div class="joined">Joined project on:</div>
                                                <div><strong>30th March 2016</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-col w-col-9 w-clearfix profile-project-detail">
                                            <h3 class="project-summary-h3">Another random project</h3>
                                            <p class="profile-project-descr">Lorem ipsum dolor sit amet, consectetur
                                                adipiscing elit. Suspendisse varius enim in eros elementum
                                                tristique...</p>
                                            <div class="w-clearfix profile-project-team-members"><img width="40"
                                                                                                      height="40"
                                                                                                      src="<?php echo SITE_RELATIVE_PATH?>/images/Screen Shot 2016-03-29 at 15.15.30.png"
                                                                                                      class="team-member-small"><img
                                                    width="40" height="40" src="<?php echo SITE_RELATIVE_PATH?>/images/meghan-benton-wv-250x250.jpg"
                                                    class="team-member-small"><img width="40" height="40"
                                                                                   src="<?php echo SITE_RELATIVE_PATH?>/images/Screen Shot 2016-03-29 at 14.50.51.png"
                                                                                   class="team-member-small"><img
                                                    width="40"
                                                    height="40"
                                                    src="<?php echo SITE_RELATIVE_PATH?>/images/images.jpg"
                                                    class="team-member-small">
                                            </div>
                                            <a href="#" class="view-project">View project</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div data-w-tab="Tab 4" class="w-tab-pane w--tab-active public-profile-tabs-content">
                                <div class="w-row profile-top-section">
                                    <div class="w-col w-col-4 left-profile-column">
                                        <div class="profile-left">
                                            <div class="profile-info-left">
                                                <h3 class="info-h">My links</h3>
                                                <ul class="w-list-unstyled contact-links">
                                                    <li class="social-contact-li" ng-repeat="link in links">
                                                        <div>
                                                            <div class="delete" ng-click="removeLink(link)">-</div>
                                                            <a href="{{link}}"
                                                               target="_blank"
                                                               class="w-inline-block w-clearfix">
                                                                <img
                                                                    ng-src="<?php echo SITE_RELATIVE_PATH?>/images/{{getUrlIcon(link)}}"
                                                                    class="social-small"
                                                                    title="{{link}}">
                                                                <div class="social-label" title="{{link}}">
                                                                    <span ng-bind="link | limitTo: 27"></span><span>{{link.length > 27 ? '...' : ''}}</span>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </li>
                                                    <li class="social-contact-li">
                                                        <div class="skill add-entry" ng-click="addLinks = !addLinks">
                                                            +
                                                        </div>
                                                        <form id="email-form" name="email-form"
                                                              class="w-clearfix add-skill-section"
                                                              ng-submit="addLink()"
                                                              ng-show="addLinks">
                                                            <input data-tags="true"
                                                                   data-placeholder="Type/Paste a link"
                                                                   id="Add-link" name="Add-link"
                                                                   class="w-input add-skill"
                                                                   style="width:100px"
                                                                   ng-model="newLink"
                                                            />
                                                            <input type="submit" value="Add"
                                                                   class="w-button add-skill-btn">
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-col w-col-8">
                                        <h3 class="info-h">Bio</h3>
                                        <div data-ix="edit" class="profile-tab-text">
                                            <div class="edit">edit</div>
                                            <div class="update">update</div>
                                            <div class="w-form">
                                                <form id="email-form-2" name="email-form-2" data-name="Email Form 2">
                                                <textarea id="Bio" class="readjustTextarea"
                                                          placeholder="Add a short biography" name="Bio"
                                                          data-name="Bio"
                                                          class="w-input profile-text"
                                                          ng-model="user.bio"
                                                          ng-blur="updateUserBio()"
                                                ><?php echo nl2br(show_input($user->getBio())) ?></textarea>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="w-clearfix">
                                            <h3 class="info-h">My languages</h3>
                                            <div class="w-clearfix">
                                                <div class="skill" ng-repeat="lang in languages">
                                                    <div class="delete" ng-click="removeLanguage(lang)">-</div>
                                                    <div ng-bind="lang"></div>
                                                </div>
                                                <div class="skill add-entry" ng-click="addLanguage = !addLanguage">+
                                                </div>

                                                <div class="w-form" style="float:left;margin-top:-17px"
                                                     ng-show="addLanguage">
                                                    <form id="email-form" name="email-form" data-name="Email Form"
                                                          class="w-clearfix add-skill-section"
                                                          ng-submit="addLanguages()">
                                                        <select data-placeholder="Select your language"
                                                                id="Add-language" name="Add-language"
                                                                class="w-input add-language"
                                                                style="width:200px">
                                                            <option></option>
                                                        </select>
                                                        <input type="submit" value="Add" class="w-button add-skill-btn">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="profile-info-right">
                                    <div class="w-clearfix skllls">
                                        <h3 class="info-h">My skills</h3>
                                        <div class="w-clearfix skills-container">
                                            <div class="skill" ng-repeat="skill in skills">
                                                <div class="delete" ng-click="removeSkill(skill)">-</div>
                                                <div ng-bind="skill"></div>
                                            </div>
                                            <div class="skill add-entry" ng-click="addSkill = !addSkill">+</div>

                                            <div class="w-form" style="float:left;margin-top:-17px" ng-show="addSkill">
                                                <form id="email-form" name="email-form" data-name="Email Form"
                                                      class="w-clearfix add-skill-section" ng-submit="addSkills()">
                                                    <select data-tags="true"
                                                            data-placeholder="Type your skill"
                                                            id="Add-skill" name="Add-skill" class="w-input add-skill"
                                                            style="width:200px">
                                                        <option></option>
                                                    </select>
                                                    <input type="submit" value="Add" data-wait="Please wait..."
                                                           class="w-button add-skill-btn">
                                                </form>
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