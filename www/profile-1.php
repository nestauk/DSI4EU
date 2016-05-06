<?php require __DIR__ . '/header.php' ?>
    <script type="text/javascript">
        profileUserID = '<?php echo $userID?>';
    </script>
    <script type="text/javascript" src="/js/controllers/UserController.js"></script>
    <script type="text/javascript" src="/js/services/UserSkillsService.js"></script>
    <div ng-controller="UserController">

        <div class="w-container body-container">
            <div class="profile-main">
                <div class="profile-header">
                    <div class="public-profile-name">DanielMPettifer</div>
                    <div class="public-profile-position">DSI Digital Product Manager</div>
                    <div class="w-row public-profile-location">
                        <div class="w-col w-col-2"><img width="60" src="/images/pin.png" class="location-pin">
                        </div>
                        <div class="w-col w-col-10 location-right">
                            <div class="location-info">London, England</div>
                        </div>
                    </div>
                    <div class="profile-img-main"></div>
                    <div class="profile-blur el-blur"></div>
                    <div class="profile-overlay"></div>
                </div>
                <div class="profile-left">
                    <div class="languages"><img src="/images/United Kingdom(Great Britain).png" class="language"><img
                            src="/images/Turkey.png" class="language">
                    </div>
                    <div class="w-row public-profile-social">
                        <div class="w-col w-col-3 soc-col">
                            <a href="#" class="w-inline-block profile-social-link"><img
                                    src="/images/social-1_square-github.svg" class="social-small">
                            </a>
                        </div>
                        <div class="w-col w-col-3 soc-col">
                            <a href="#" class="w-inline-block profile-social-link"><img
                                    src="/images/social-1_square-facebook.svg" class="social-small">
                            </a>
                        </div>
                        <div class="w-col w-col-3 soc-col">
                            <a href="#" class="w-inline-block profile-social-link"><img
                                    src="/images/social-1_square-google-plus.svg" class="social-small">
                            </a>
                        </div>
                        <div class="w-col w-col-3 soc-col">
                            <a href="#" class="w-inline-block profile-social-link"><img
                                    src="/images/social-1_square-twitter.svg" class="social-small">
                            </a>
                        </div>
                    </div>
                    <div class="public-profile-side-info"><a href="#">www.mysite.com</a>
                    </div>
                    <div class="public-profile-side-info"><a href="#">www.another-site.com</a>
                    </div>
                    <div class="w-clearfix skllls">
                        <div class="w-clearfix skills-container">
                            <div class="skill" ng-repeat="skill in skills">
                                <div class="delete" ng-click="removeSkill(skill)">-</div>
                                <div ng-bind="skill"></div>
                            </div>
                        </div>
                        <div>
                            <div class="w-form">
                                <form class="w-clearfix" ng-submit="addSkills()">
                                    <input id="Add-skill" type="text" placeholder="Add skill" name="Add-skill"
                                           data-name="Add skill" class="w-input add-skill" ng-model="newSkill">
                                    <input type="submit" value="Add" data-wait="Please wait..."
                                           class="w-button add-skill-btn">
                                </form>
                                <div class="w-form-done">
                                    <p>Thank you! Your submission has been received!</p>
                                </div>
                                <div class="w-form-fail">
                                    <p>Oops! Something went wrong while submitting the form</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                            <div data-w-tab="Tab 1" class="w-tab-pane public-profile-tabs-content"></div>
                            <div data-w-tab="Tab 2" class="w-tab-pane public-profile-tabs-content">
                                <div class="profile-project-list-container">
                                    <div class="project-list-card">
                                        <div class="w-row profile-project-list">
                                            <div class="w-col w-col-3">
                                                <div class="joined-on">
                                                    <div class="joined">Joined project on:</div>
                                                    <div><strong>30th March 2016</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="w-col w-col-9 profile-project-detail">
                                                <h3 class="project-summary-h3">DSI4EU</h3>
                                                <p class="profile-project-descr">Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing elit. Suspendisse varius enim in eros elementum
                                                    tristique...</p>
                                                <div class="w-clearfix profile-project-team-members"><img width="40"
                                                                                                          height="40"
                                                                                                          src="/images/Screen Shot 2016-03-29 at 15.15.30.png"
                                                                                                          class="team-member-small"><img
                                                        width="40" height="40"
                                                        src="/images/meghan-benton-wv-250x250.jpg"
                                                        class="team-member-small"><img width="40" height="40"
                                                                                       src="/images/Screen Shot 2016-03-29 at 14.50.51.png"
                                                                                       class="team-member-small"><img
                                                        width="40" height="40" src="/images/images.jpg"
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
                                            <div class="w-col w-col-9 profile-project-detail">
                                                <h3 class="project-summary-h3">Open Data Challenge Series</h3>
                                                <p class="profile-project-descr">Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing elit. Suspendisse varius enim in eros elementum
                                                    tristique...</p>
                                                <div class="w-clearfix profile-project-team-members"><img width="40"
                                                                                                          height="40"
                                                                                                          src="/images/Screen Shot 2016-03-29 at 14.50.51.png"
                                                                                                          class="team-member-small"><img
                                                        width="40" height="40" src="/images/images.jpg"
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
                                            <div class="w-col w-col-9 profile-project-detail">
                                                <h3 class="project-summary-h3">RaspberryPi</h3>
                                                <p class="profile-project-descr">Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing elit. Suspendisse varius enim in eros elementum
                                                    tristique...</p>
                                                <div class="w-clearfix profile-project-team-members"><img width="40"
                                                                                                          height="40"
                                                                                                          src="/images/Screen Shot 2016-03-29 at 15.15.30.png"
                                                                                                          class="team-member-small"><img
                                                        width="40" height="40"
                                                        src="/images/meghan-benton-wv-250x250.jpg"
                                                        class="team-member-small"><img width="40" height="40"
                                                                                       src="/images/Screen Shot 2016-03-29 at 14.50.51.png"
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
                                            <div class="w-col w-col-9 profile-project-detail">
                                                <h3 class="project-summary-h3">Nesta project</h3>
                                                <p class="profile-project-descr">Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing elit. Suspendisse varius enim in eros elementum
                                                    tristique...</p>
                                                <div class="w-clearfix profile-project-team-members"><img width="40"
                                                                                                          height="40"
                                                                                                          src="/images/Screen Shot 2016-03-29 at 15.15.30.png"
                                                                                                          class="team-member-small"><img
                                                        width="40" height="40"
                                                        src="/images/Screen Shot 2016-03-29 at 14.50.51.png"
                                                        class="team-member-small"><img width="40" height="40"
                                                                                       src="/images/images.jpg"
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
                                            <div class="w-col w-col-9 profile-project-detail">
                                                <h3 class="project-summary-h3">Another random project</h3>
                                                <p class="profile-project-descr">Lorem ipsum dolor sit amet, consectetur
                                                    adipiscing elit. Suspendisse varius enim in eros elementum
                                                    tristique...</p>
                                                <div class="w-clearfix profile-project-team-members"><img width="40"
                                                                                                          height="40"
                                                                                                          src="/images/Screen Shot 2016-03-29 at 15.15.30.png"
                                                                                                          class="team-member-small"><img
                                                        width="40" height="40"
                                                        src="/images/meghan-benton-wv-250x250.jpg"
                                                        class="team-member-small"><img width="40" height="40"
                                                                                       src="/images/Screen Shot 2016-03-29 at 14.50.51.png"
                                                                                       class="team-member-small"><img
                                                        width="40" height="40" src="/images/images.jpg"
                                                        class="team-member-small">
                                                </div>
                                                <a href="#" class="view-project">View project</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div data-w-tab="Tab 4" class="w-tab-pane w--tab-active public-profile-tabs-content">
                                <div class="w-row">
                                    <div class="w-col w-col-3 public-profile-content-label">
                                        <h3 class="info-h">Bio</h3>
                                    </div>
                                    <div class="w-col w-col-9 public-profile-content-block">
                                        <div data-ix="edit" class="profile-tab-text">
                                            <div class="edit">edit</div>
                                            <div class="update">update</div>
                                            <div class="w-form">
                                                <form id="email-form-2" name="email-form-2" data-name="Email Form 2">
                                                <textarea id="Bio" placeholder="Add a short biography" name="Bio"
                                                          data-name="Bio" data-ix="update"
                                                          class="w-input profile-text"></textarea>
                                                </form>
                                                <div class="w-form-done">
                                                    <p>Thank you! Your submission has been received!</p>
                                                </div>
                                                <div class="w-form-fail">
                                                    <p>Oops! Something went wrong while submitting the form</p>
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