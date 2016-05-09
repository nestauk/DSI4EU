<?php
require __DIR__ . '/header.php';
/** @var $userID int */
/** @var $project \DSI\Entity\Project */
?>
    <script src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ProjectController.js"></script>

    <form name="projectForm"
          ng-controller="ProjectController"
          data-projectid="<?php echo $project->getId() ?>">

        <div class="w-section project-section">
            <div class="w-container body-container">
                <div class="project-detail">
                    <div class="project-header">
                        <img src="<?php echo SITE_RELATIVE_PATH ?>/images/pin.png" class="card-pin">
                        <div class="card-city">City, Country</div>
                        <img src="<?php echo SITE_RELATIVE_PATH ?>/images/share-symbol.svg" class="share">

                        <input type="text"
                               class="project-url"
                               ng-model="project.url"
                               ng-blur="updateBasic()"
                               placeholder="Project Page"
                               value="<?php echo $project->getUrl() ?>"
                               style="background:transparent;color:white;width:500px;border:0"/>

                        <div class="project-status"><span class="status-text">Project status:</span>
                            <select ng-model="project.status" ng-change="updateBasic()"
                                    style="background: transparent;border:0">
                                <option value="live">Live</option>
                                <option value="closed">Closed</option>
                            </select>
                            <?php /* <strong class="status-indicator">Live</strong> */ ?>
                        </div>
                    </div>
                    <h1 class="project-h1">
                        <input type="text"
                               ng-model="project.name"
                               ng-blur="updateBasic()"
                               value="<?php echo $project->getName() ?>"
                               style="background:transparent;color:white;width:500px;border:0"/>
                    </h1>
                </div>
                <div class="w-row project-info">
                    <div class="w-col w-col-6">
                        <div class="info-card">
                            <h3 class="info-h card-h">About this project</h3>
                            <p class="project-summary">
                                <textarea ng-model="project.description" ng-blur="updateBasic()"
                                          style="min-height:150px;border:0;width:100%">
                                    <?php echo $project->getDescription() ?>
                                </textarea>
                            </p>
                            <h3 class="card-sub-h">Duration</h3>
                            <div class="duration-p">
                                <div ng-show="projectForm.$valid">
                                    <div ng-show="project.startDate && project.endDate">
                                        This project runs from
                                        <strong>{{getDateFrom(project.startDate)}}</strong> to
                                        <strong>{{getDateFrom(project.endDate)}}</strong>
                                    </div>
                                    <div ng-show="project.startDate && !project.endDate">
                                        This project runs from
                                        <strong>{{getDateFrom(project.startDate)}}</strong>
                                    </div>
                                    <div ng-show="!project.startDate && project.endDate">
                                        This project runs until
                                        <strong>{{getDateFrom(project.endDate)}}</strong>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <div style="float:left;width:40%;margin-left:10%">
                                Start Date
                                <input type="text" placeholder="yyyy-mm-dd" ng-model="project.startDate"
                                       ng-blur="updateBasic()" style="width:130px" ng-pattern="datePattern"/>
                            </div>
                            <div style="float:left;width:40%">
                                End Date
                                <input type="text" placeholder="yyyy-mm-dd" ng-model="project.endDate"
                                       ng-blur="updateBasic()" style="width:130px" ng-pattern="datePattern"/>
                            </div>

                            <div style="clear:both"></div>
                        </div>
                        <div class="info-card">
                            <h3 class="info-h card-h">Organisations involved</h3>
                            <div class="w-row organisation-links">
                                <div class="w-col w-col-4 w-col-stack w-col-small-4">
                                    <a href="#" class="w-inline-block organisation-small"><img width="118"
                                                                                               src="<?php echo SITE_RELATIVE_PATH ?>/images/nesta-6a9b5fe999e8323b379ccc0d8e70290f.png"
                                                                                               class="org-img">
                                    </a>
                                </div>
                                <div class="w-col w-col-4 w-col-stack w-col-small-4">
                                    <a href="#" class="w-inline-block organisation-small"><img width="118"
                                                                                               src="<?php echo SITE_RELATIVE_PATH ?>/images/waag-f1d052f43133268eaf2e13090a0b4bf1.png"
                                                                                               class="org-img">
                                    </a>
                                </div>
                                <div class="w-col w-col-4 w-col-stack w-col-small-4">
                                    <a href="#" class="w-inline-block organisation-small"><img width="118"
                                                                                               src="<?php echo SITE_RELATIVE_PATH ?>/images/future-everything-2f261cf2d078264179fd82b21e5927b7.png"
                                                                                               class="org-img">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="info-card">
                            <h3 class="info-h card-h">Contributors</h3>
                            <div class="project-owner">
                                <a href="#" class="w-inline-block owner-link"><img width="50" height="50"
                                                                                   src="<?php echo SITE_RELATIVE_PATH ?>/images/Screen Shot 2016-03-29 at 15.15.30.png"
                                                                                   class="project-creator-img">
                                    <div class="creator-name">Simon N Jacobs</div>
                                    <div class="project-creator-text">Project creator and Director of Studies</div>
                                </a>
                            </div>
                            <div class="w-row contributors">
                                <div class="w-col w-col-6 contributor-col">
                                    <a href="#" class="w-inline-block contributor"><img width="40" height="40"
                                                                                        src="<?php echo SITE_RELATIVE_PATH ?>/images/Screen Shot 2016-03-29 at 14.50.51.png"
                                                                                        class="contributor-small-img">
                                        <div class="contributor-name">Jacob M Simon</div>
                                        <div class="contributor-position">This is a job title</div>
                                    </a>
                                </div>
                                <div class="w-col w-col-6 contributor-col">
                                    <a href="#" class="w-inline-block contributor"><img width="40" height="40"
                                                                                        src="<?php echo SITE_RELATIVE_PATH ?>/images/Screen Shot 2016-03-29 at 14.50.51.png"
                                                                                        class="contributor-small-img">
                                        <div class="contributor-name">Jacob M Simon</div>
                                        <div class="contributor-position">This is a job title</div>
                                    </a>
                                </div>
                            </div>
                            <div class="w-row contributors">
                                <div class="w-col w-col-6 contributor-col">
                                    <a href="#" class="w-inline-block contributor"><img width="40" height="40"
                                                                                        src="<?php echo SITE_RELATIVE_PATH ?>/images/Screen Shot 2016-03-29 at 14.50.51.png"
                                                                                        class="contributor-small-img">
                                        <div class="contributor-name">Jacob M Simon</div>
                                        <div class="contributor-position">This is a job title</div>
                                    </a>
                                </div>
                                <div class="w-col w-col-6 contributor-col">
                                    <a href="#" class="w-inline-block contributor"><img width="40" height="40"
                                                                                        src="<?php echo SITE_RELATIVE_PATH ?>/images/Screen Shot 2016-03-29 at 14.50.51.png"
                                                                                        class="contributor-small-img">
                                        <div class="contributor-name">Jacob M Simon</div>
                                        <div class="contributor-position">This is a job title</div>
                                    </a>
                                </div>
                            </div>
                            <div class="join-project"><a href="#" class="w-button btn btn-join">Request to join</a>
                            </div>
                        </div>
                        <div class="info-card">
                            <h3 class="info-h card-h">This project is tagged under:</h3>
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
                        <div class="info-card">
                            <h3 class="info-h card-h">Social impact</h3>
                            <div class="impact-block">
                                <h4 class="impact-h4">Areas of society impacted</h4>
                                <div class="w-clearfix tags-block impact">
                                    <div class="skill">Culture and Arts</div>
                                    <div class="skill">Education and Skills</div>
                                    <div class="skill">Work and Employment</div>
                                    <div class="add-item-block">
                                        <div class="add-item">+</div>
                                    </div>
                                </div>
                            </div>
                            <div class="impact-block">
                                <h4 class="impact-h4">Technology focus</h4>
                                <div class="w-clearfix tags-block impact">
                                    <div class="skill">Open Hardware</div>
                                    <div class="add-item-block">
                                        <div class="add-item">+</div>
                                    </div>
                                </div>
                            </div>
                            <div class="impact-block last">
                                <h4 class="impact-h4">Technology method</h4>
                                <div class="w-clearfix tags-block impact">
                                    <div class="skill">Open Hardware</div>
                                    <div class="add-item-block">
                                        <div class="add-item">+</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-col w-col-6">
                        <div class="info-card">
                            <div data-ix="postblockclosed" class="add-post">
                                <div class="w-clearfix post-author new-post"><img width="40" height="40"
                                                                                  src="<?php echo SITE_RELATIVE_PATH ?>/images/XG1hqhM1_400x400.jpg"
                                                                                  class="post-author-img post">
                                    <div class="w-form add-post-form-wrapper">
                                        <form id="email-form" name="email-form" data-name="Email Form"
                                              class="w-clearfix">
                                        <textarea id="new-post" placeholder="What's been happening?" name="new-post"
                                                  data-name="new post" data-ix="addpost"
                                                  class="w-input add-post-form"></textarea>
                                        </form>
                                        <div class="w-form-done">
                                            <p>Thank you! Your submission has been received!</p>
                                        </div>
                                        <div class="w-form-fail">
                                            <p>Oops! Something went wrong while submitting the form</p>
                                        </div>
                                    </div>
                                </div>
                                <a href="#" class="w-button btn-new-post btn">Add post</a>
                            </div>
                            <div class="w-clearfix current-status">
                                <h3 class="status-h3">Latest post</h3>
                                <div class="post-author latest"><img width="40" height="40"
                                                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/XG1hqhM1_400x400.jpg"
                                                                     class="post-author-img">
                                    <div class="post-author-detail latest">DanielMPettifer</div>
                                    <div class="posted-on latest">Posted today at 11:12 gmt</div>
                                </div>
                                <div class="news-content">Our new app has just been published on the apple app store:
                                    <br><a class="status-link"
                                           href="https://geo.itunes.apple.com/us/app/netflix/id363590051?mt=8">https://geo.itunes.apple.com/us/app/netflix/id363590051?mt=8</a>
                                    <br> android version to be released very soon! Follow us to stay up to date!
                                </div>
                            </div>
                            <h3 class="info-h card-h">Previous posts</h3>
                            <div class="w-clearfix project-post">
                                <div class="post-author"><img width="40" height="40"
                                                              src="<?php echo SITE_RELATIVE_PATH ?>/images/XG1hqhM1_400x400.jpg"
                                                              class="post-author-img">
                                    <div class="post-author-detail">DanielMPettifer</div>
                                    <div class="posted-on">Posted yesterday at 08:22 gmt</div>
                                </div>
                                <div class="project-post-detail"><img
                                        src="<?php echo SITE_RELATIVE_PATH ?>/images/wearable-circuit-board-tattoo-644x424.jpg"
                                        class="project-post-image">
                                    <div class="w-richtext project-post-rich-text">
                                        <h4>First human test of implants</h4>
                                        <p>A rich text element can be used with static or dynamic content. For static
                                            content, just drop it into any page and begin editing. For dynamic content,
                                            add
                                            a rich text field to any collection and then connect a rich text element to
                                            that
                                            field in the settings panel. Voila!</p>
                                    </div>
                                </div>
                            </div>
                            <div class="w-clearfix project-post">
                                <div class="post-author"><img width="40" height="40"
                                                              src="<?php echo SITE_RELATIVE_PATH ?>/images/XG1hqhM1_400x400.jpg"
                                                              class="post-author-img">
                                    <div class="post-author-detail">DanielMPettifer</div>
                                    <div class="posted-on">Posted yesterday at 08:22 gmt</div>
                                </div>
                                <div class="project-post-detail">
                                    <div style="padding-top: 56.17021276595745%;" class="w-embed w-video">
                                        <iframe class="embedly-embed"
                                                src="https://cdn.embedly.com/widgets/media.html?src=https%3A%2F%2Fwww.youtube.com%2Fembed%2FWs6AAhTw7RA%3Ffeature%3Doembed&amp;url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DWs6AAhTw7RA%26feature%3Dyoutu.be&amp;image=https%3A%2F%2Fi.ytimg.com%2Fvi%2FWs6AAhTw7RA%2Fhqdefault.jpg&amp;key=c4e54deccf4d4ec997a64902e9a30300&amp;type=text%2Fhtml&amp;schema=youtube"
                                                scrolling="no" frameborder="0" allowfullscreen=""></iframe>
                                    </div>
                                    <div class="w-richtext project-post-rich-text">
                                        <h4>Quantum levitation now included in our base model</h4>
                                        <p>A rich text element can be used with static or dynamic content. For static
                                            content, just drop it into any page and begin editing. For dynamic content,
                                            add
                                            a rich text field to any collection and then connect a rich text element to
                                            that
                                            field in the settings panel. Voila!</p>
                                        <h4>How to customize formatting for each rich text</h4>
                                        <p>Headings, paragraphs, blockquotes, figures, images, and figure captions can
                                            all
                                            be styled after a class is added to the rich text element using the "When
                                            inside
                                            of" nested selector system.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
<?php require __DIR__ . '/footer.php' ?>