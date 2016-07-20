<?php
require __DIR__ . '/header.php';
/** @var $organisation \DSI\Entity\Organisation */
/** @var $organisationTypes \DSI\Entity\OrganisationType[] */
/** @var $organisationSizes \DSI\Entity\OrganisationSize[] */
?>
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
                                                        <div class="required">*Required</div>
                                                        <input class="creator-data-entry end w-input" data-name="Name"
                                                               id="name" maxlength="256" name="name"
                                                               placeholder="Organisation Name" type="text">
                                                        <label for="email">Organisation URL</label>
                                                        <input class="creator-data-entry end w-input" data-name="Email"
                                                               id="email" maxlength="256" name="email"
                                                               placeholder="Add your organisation&#39;s URL"
                                                               type="text">
                                                        <label for="email-2">Social media links</label>
                                                        <input class="creator-data-entry w-input" data-name="Email 2"
                                                               id="email-2" maxlength="256" name="email-2"
                                                               placeholder="Facebook" type="text">
                                                        <input class="creator-data-entry w-input" data-name="Email 3"
                                                               id="email-3" maxlength="256" name="email-3"
                                                               placeholder="Twitter" type="text">
                                                        <input class="creator-data-entry w-input" data-name="Email 4"
                                                               id="email-4" maxlength="256" name="email-4"
                                                               placeholder="Google plus"
                                                               type="text">
                                                        <input class="creator-data-entry w-input" data-name="Email 5"
                                                               id="email-5" maxlength="256" name="email-5"
                                                               placeholder="Github" type="text">
                                                    </div>
                                                </div>
                                                <div class="w-col w-col-6 w-col-stack">
                                                    <div class="padding-left-50">
                                                        <label for="email">Tag your organisation</label>
                                                        <div class="required">*Required</div>
                                                        <p>Add tags that best describe your project:</p>
                                                        <label for="email">Projects your organisation is involved
                                                            with:</label>
                                                        <p>Add projects your organisation is involved with here. Leave
                                                            this blank if you are not currently involved with any
                                                            projects.</p>
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
                                                               id="name-2" maxlength="256" name="name-2"
                                                               placeholder="When did your organisation start?"
                                                               type="text">
                                                        <h2 class="edit-h2">Size of Organisation</h2>
                                                        <select class="w-select" id="field" name="field">
                                                            <option value="">1-10 people</option>
                                                            <option value="First">10-50 people</option>
                                                            <option value="Second">50-100</option>
                                                            <option value="Third">100-250</option>
                                                            <option value="">250-500</option>
                                                            <option value="">500-1000</option>
                                                        </select>
                                                        <label for="field">Choose the size of your organisation</label>
                                                        <div class="required">*Required</div>
                                                    </div>
                                                </div>
                                                <div class="w-col w-col-6">
                                                    <div class="padding-left-50">
                                                        <h2 class="edit-h2">Where is your Organisation based?</h2>
                                                        <label for="email-7">Which country are you based in?</label>
                                                        <input class="creator-data-entry w-input" data-name="Email 7"
                                                               id="email-7" maxlength="256" name="email-7"
                                                               placeholder="Your country"
                                                               type="text">
                                                        <label for="email-8">and in which city?</label>
                                                        <input class="creator-data-entry w-input" data-name="Email 8"
                                                               id="email-8" maxlength="256" name="email-8"
                                                               placeholder="Your city" type="text">
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
                                            <div class="required">*Required</div>
                                            <p>Please provide a short organisation description. How would you describe
                                                your project in one tweet?
                                                <br>(This will appear on your project card)</p>
                                            <textarea class="creator-data-entry end w-input wide"
                                                      data-name="Project Bio 3" id="project-bio-3" maxlength="5000"
                                                      name="project-bio-3"
                                                      placeholder="Briefly describe your organisation (no more than 140 characters)"></textarea>
                                            <label class="story-label" for="project-bio">Long description</label>
                                            <p>Please provide a longer organisation description. How would you describe
                                                your project.......? (This will appear on your project page)</p>
                                            <textarea class="creator-data-entry long-description w-input wide"
                                                      data-name="Project Bio 4" id="project-bio-4" maxlength="5000"
                                                      name="project-bio-4"
                                                      placeholder="Add an in depth organisation description"></textarea>
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
                                                             src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg">
                                                        <a class="dsi-button story-image-upload w-button" href="#">Upload
                                                            image</a>
                                                    </div>
                                                </div>
                                                <div class="w-col w-col-6 w-col-stack">
                                                    <div class="padding-left-50">
                                                        <label class="story-label" for="Title">Header background
                                                            image</label>
                                                        <p>This will appear as the header background for your
                                                            organisation's page</p>
                                                        <img class="story-image-upload story-image-upload-large"
                                                             src="images/brussels-locations.jpg">
                                                        <a class="dsi-button story-image-upload w-button" href="#">Upload
                                                            image</a>
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

<?php require __DIR__ . '/footer.php' ?>