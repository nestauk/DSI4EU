<?php
require __DIR__ . '/header.php';
/** @var $loggedInUser \DSI\Entity\User */
/** @var $caseStudy \DSI\Entity\CaseStudy */
?>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <div ng-controller="CaseStudyEditController" data-casestudyid="<?php echo $caseStudy->getId() ?>">
        <div class="header-section-grey w-section">
            <div class="container-wide">
                <h1 class="header-centre">Edit case study</h1>
            </div>
        </div>
        <div class="section-white w-section">
            <div class="container-wide step-window">
                <form id="email-form-2" name="email-form-2" ng-submit="save()">
                    <div class="w-form">
                        <div class="w-row">
                            <div class="form-col-left w-col w-col-6">
                                <h2 class="edit-h2">Case study text</h2>
                                <label for="name-2">Case study project title:</label>
                                <input class="creator-data-entry w-input" id="name-2" maxlength="256"
                                       ng-model="caseStudy.title"
                                       name="name-2" placeholder="Project Title" type="text">
                                <div class="error" ng-bind="errors.title"></div>
                                <label for="name-3">Intro card text:</label>
                                <input class="creator-data-entry w-input" data-name="Name 3" id="name-3" maxlength="256"
                                       ng-model="caseStudy.introCardText"
                                       name="name-3" placeholder="This text appears on the grid card" type="text">
                                <label for="field">Page intro text:</label>
                                <textarea class="creator-data-entry w-input" id="field" maxlength="5000" name="field"
                                          ng-model="caseStudy.introPageText"
                                          placeholder="This text appears at the top of the case study page"></textarea>
                                <label>Main page text</label>
                                <textarea class="creator-data-entry end long-description w-input"
                                          id="mainText"
                                          placeholder="This is the main body text"><?php echo $caseStudy->getMainText() ?></textarea>
                                <h2 class="edit-h2">Duration of project</h2>
                                <label for="name-4">Project start date</label>
                                <input class="creator-data-entry w-input" data-name="Name 4" id="projectStartDate"
                                       maxlength="256"
                                       ng-model="caseStudy.projectStartDate"
                                       name="name-4" placeholder="When did the project start" type="text">
                                <label for="name-5">Project end date (leave this blank for ongoing projects)</label>
                                <input class="creator-data-entry end w-input" data-name="Name 5" id="projectEndDate"
                                       maxlength="256"
                                       ng-model="caseStudy.projectEndDate"
                                       name="name-5" placeholder="When did/will the project end?" type="text">
                                <h2 class="edit-h2">Location of project</h2>

                                <div ng-cloak>
                                    <div>
                                        <label class="story-label" for="country">Which country in the project
                                            based?</label>
                                        <select id="edit-country" data-placeholder="Select country"
                                                style="width:400px;background:transparent">
                                            <option></option>
                                        </select>
                                    </div>
                                    <div ng-show="regionsLoaded">
                                        <br/>
                                        <label class="story-label" for="city">In which city?</label>
                                        <select
                                            data-tags="true" id="edit-countryRegion"
                                            data-placeholder="Type the city"
                                            style="width:400px;background:transparent">
                                        </select>
                                    </div>
                                    <div ng-show="regionsLoading">
                                        Loading...
                                    </div>
                                </div>

                            </div>
                            <div class="form-col-right w-col w-col-6">
                                <h2 class="edit-h2">Link to project or external link</h2>
                                <label for="name-9">Link to project page or external URL</label>
                                <input class="creator-data-entry w-input" data-name="Name 9" id="name-9" maxlength="256"
                                       ng-model="caseStudy.url"
                                       name="name-9" placeholder="https://example.org" type="text">
                                <label for="name-8">Button text</label>
                                <input class="creator-data-entry end w-input" data-name="Name 8" id="name-8"
                                       maxlength="256" name="name-8"
                                       ng-model="caseStudy.buttonLabel"
                                       placeholder="This text will appear on the button for the above link"
                                       type="text">
                                <h2 class="edit-h2">Case study visuals</h2>
                                <div class="w-row">
                                    <div class="w-col w-col-4">
                                        <label for="name-8">Add logo</label>
                                        <img class="story-image-upload"
                                             style="max-height:140px;max-width:140px"
                                             src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg"
                                             ng-src="{{logo.image}}">
                                        <a class="w-button dsi-button story-image-upload" href="#"
                                           ngf-select="logo.upload($file, $invalidFiles)"
                                           ng-bind="logo.loading ? 'Loading...' : 'Upload image'">Upload image
                                        </a>
                                        <div style="color:red" ng-show="logo.errorMsg.file" ng-cloak>
                                            {{logo.errorMsg.file}}
                                        </div>
                                    </div>
                                    <div class="w-col w-col-4">
                                        <label for="name-8">Add card background</label>
                                        <img class="story-image-upload"
                                             style="max-height:140px;max-width:140px"
                                             src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg"
                                             ng-src="{{cardImage.image}}">
                                        <a class="w-button dsi-button story-image-upload" href="#"
                                           ngf-select="cardImage.upload($file, $invalidFiles)"
                                           ng-bind="cardImage.loading ? 'Loading...' : 'Upload image'">Upload image
                                        </a>
                                        <div style="color:red" ng-show="cardImage.errorMsg.file" ng-cloak>
                                            {{cardImage.errorMsg.file}}
                                        </div>
                                    </div>
                                    <div class="w-col w-col-4">
                                        <label for="name-8">Add header image</label>
                                        <img class="story-image-upload"
                                             style="max-height:140px;max-width:140px"
                                             src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg"
                                             ng-src="{{headerImage.image}}">
                                        <a class="w-button dsi-button story-image-upload" href="#"
                                           ngf-select="headerImage.upload($file, $invalidFiles)"
                                           ng-bind="headerImage.loading ? 'Loading...' : 'Upload image'">Upload image
                                        </a>
                                        <div style="color:red" ng-show="headerImage.errorMsg.file" ng-cloak>
                                            {{headerImage.errorMsg.file}}
                                        </div>
                                    </div>
                                </div>
                                <label for="name-8">Card Colour</label>
                                <p class="end">
                                    Choose a colour here that will appear solid onRollover but will appear
                                    with a 50% opacity at all other times
                                </p>
                                <input class="creator-data-entry end w-input" style="width:100px;height:100px"
                                       value="#000000"
                                       ng-model="caseStudy.cardColour"
                                       type="color">
                                <h2 class="edit-h2">Publication</h2>
                                <label for="name-8">Publish your case study</label>
                                <div class="w-radio">
                                    <label class="w-form-label">
                                        <input class="w-radio-input" name="isPublished"
                                               type="radio"
                                               ng-model="caseStudy.isPublished"
                                               value="1">
                                        Yes
                                    </label>
                                </div>
                                <div class="w-radio">
                                    <label class="w-form-label">
                                        <input class="w-radio-input" name="isPublished"
                                               type="radio"
                                               ng-model="caseStudy.isPublished"
                                               value="0">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tabbed-nav-buttons w-clearfix">
                        <input type="submit" class="tab-button-2 tab-button-next w-button" value="Save and continue"/>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/CaseStudyEditController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <script>
        $(function () {
            tinymce.init({
                selector: '#mainText',
                statusbar: false,
                height: 500,
                plugins: "autoresize autolink lists link preview paste textcolor colorpicker image imagetools media",
                autoresize_bottom_margin: 0,
                autoresize_max_height: 500,
                menubar: false,
                toolbar1: 'styleselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | preview',
                image_advtab: true,
                paste_data_images: false
            });

            $("#projectStartDate").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+20"
            });
            $("#projectEndDate").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+20"
            });
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>