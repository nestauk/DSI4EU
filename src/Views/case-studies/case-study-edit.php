<?php
$angularModules['fileUpload'] = true;
\DSI\Service\JsModules::setTinyMCE(true);
\DSI\Service\JsModules::setJqueryUI(true);

require __DIR__ . '/../header.php';
/** @var $loggedInUser \DSI\Entity\User */
/** @var $caseStudy \DSI\Entity\CaseStudy */
/** @var $urlHandler \Services\URL */
/** @var $tags \Models\Tag[] */
?>
    <div ng-controller="CaseStudyEditController" data-casestudyid="<?php echo $caseStudy->getId() ?>">
        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8">
                    <h1 class="content-h1">Edit case study</h1>
                    <p class="intro">Case studies are listed in the case studies section</p>
                </div>
                <div class="sidebar w-col w-col-4">
                    <h1 class="content-h1">Actions</h1>
                    <a class="sidebar-link" href="<?php echo $urlHandler->addCaseStudy() ?>"><span
                                class="green">-&nbsp;</span>Add new case study</a>
                    <a class="sidebar-link" href="<?php echo $urlHandler->caseStudy($caseStudy) ?>"><span class="green">-&nbsp;</span>Cancel</a>
                </div>
            </div>
        </div>

        <div class="content-directory">
            <div class="container-wide step-window">
                <div class="w-form">
                    <form id="email-form-2" name="email-form-2" ng-submit="save()">
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
                                <label>Page introduction:</label>
                                <textarea class="creator-data-entry w-input" name="field"
                                          placeholder="This text appears at the top of the case study page"
                                          id="pageIntro"><?php echo $caseStudy->getIntroPageText() ?></textarea>
                                <br/>
                                <label>Page overview:</label>
                                <textarea class="creator-data-entry end long-description w-input"
                                          placeholder="This is the main body text"
                                          id="mainText"><?php echo $caseStudy->getMainText() ?></textarea>

                                <br/>
                                <h2 class="edit-h2">Project</h2>
                                <select class="creator-data-entry w-input" ng-model="caseStudy.projectID">
                                    <option value="0"> - None selected -</option>
                                    <option ng-repeat="option in caseStudy.projects" value="{{option.id}}">
                                        {{option.name}}
                                    </option>
                                </select>

                                <br/>
                                <h2 class="edit-h2">Organisation</h2>
                                <select class="creator-data-entry w-input" ng-model="caseStudy.organisationID">
                                    <option value="0"> - None selected -</option>
                                    <option ng-repeat="option in caseStudy.organisations" value="{{option.id}}">
                                        {{option.name}}
                                    </option>
                                </select>

                                <br>
                                <h2 class="edit-h2">Info Box</h2>

                                <label for="name-3">Info Text:</label>
                                <textarea class="creator-data-entry w-input" ng-model="caseStudy.infoText"></textarea>

                                <label for="name-9">External link</label>
                                <input class="creator-data-entry w-input" data-name="Name 9" id="name-9" maxlength="256"
                                       ng-model="caseStudy.url"
                                       name="name-9" placeholder="https://example.org" type="text">

                                <label for="name-8">Link text</label>
                                <input class="creator-data-entry end w-input" data-name="Name 8" id="name-8"
                                       maxlength="256" name="name-8"
                                       ng-model="caseStudy.buttonLabel"
                                       placeholder="This text will appear on the button for the above link"
                                       type="text">
                            </div>
                            <div class="form-col-right w-col w-col-6">
                                <h2 class="edit-h2">Tags</h2>
                                <?php foreach ($tags AS $tag) { ?>
                                    <div class="w-radio">
                                        <label class="w-form-label">
                                            <input class="w-radio-input" type="checkbox"
                                                   ng-model="caseStudy.tags[<?= $tag->getId() ?>]"
                                                   value="1" ng-true-value="1" ng-false-value="0">
                                            <?= $tag->getName() ?>
                                        </label>
                                    </div>
                                <?php } ?>

                                <br>
                                <h2 class="edit-h2">Case study visuals</h2>
                                <div class="w-row">
                                    <?php /* <div class="w-col w-col-4">
                                        <label for="name-8">Add logo</label>
                                        <img class="story-image-upload"
                                             style="max-height:140px;max-width:140px"
                                             src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg"
                                             ng-src="{{logo.image ? logo.image : 'https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg'}}">
                                        <button type="button" class="w-button dsi-button story-image-upload"
                                                style="color:red" ng-show="logo.image" ng-cloak
                                                ng-click="logo.image = null">
                                            Remove image
                                        </button>
                                        <a class="w-button dsi-button story-image-upload" href="#"
                                           ngf-select="logo.upload($file, $invalidFiles)"
                                           ng-bind="logo.loading ? 'Loading...' : 'Upload image'">Upload image
                                        </a>
                                        <div style="color:red" ng-show="logo.errorMsg.file" ng-cloak>
                                            {{logo.errorMsg.file}}
                                        </div>
                                    </div> */ ?>
                                    <div class="w-col w-col-4">
                                        <label for="name-8">Add card background</label>
                                        <img class="story-image-upload"
                                             style="max-height:140px;max-width:140px"
                                             src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg"
                                             ng-src="{{cardImage.image ? cardImage.image : 'https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg'}}">
                                        <button type="button" class="w-button dsi-button story-image-upload"
                                                style="color:red" ng-show="cardImage.image" ng-cloak
                                                ng-click="cardImage.image = null">
                                            Remove image
                                        </button>
                                        <a class="w-button dsi-button story-image-upload" href="#"
                                           ngf-select="cardImage.upload($file, $invalidFiles)"
                                           ng-bind="cardImage.loading ? 'Loading...' : 'Upload image'">Upload image
                                        </a>
                                        <div style="color:red" ng-show="cardImage.errorMsg.file" ng-cloak>
                                            {{cardImage.errorMsg.file}}
                                        </div>
                                    </div>
                                    <?php /* <div class="w-col w-col-4">
                                        <label for="name-8">Add header image</label>
                                        <img class="story-image-upload"
                                             style="max-height:140px;max-width:140px"
                                             src="https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg"
                                             ng-src="{{headerImage.image ? headerImage.image : 'https://d3e54v103j8qbb.cloudfront.net/img/image-placeholder.svg'}}">
                                        <button type="button" class="w-button dsi-button story-image-upload"
                                                style="color:red" ng-show="headerImage.image" ng-cloak
                                                ng-click="headerImage.image = null">
                                            Remove image
                                        </button>
                                        <a class="w-button dsi-button story-image-upload" href="#"
                                           ngf-select="headerImage.upload($file, $invalidFiles)"
                                           ng-bind="headerImage.loading ? 'Loading...' : 'Upload image'">Upload image
                                        </a>
                                        <div style="color:red" ng-show="headerImage.errorMsg.file" ng-cloak>
                                            {{headerImage.errorMsg.file}}
                                        </div>
                                    </div> */ ?>
                                </div>

                                <?php /*
                                <label for="name-8">Card Colour</label>
                                <p class="end">
                                    Choose a colour here that will appear solid onRollover but will appear
                                    with a 50% opacity at all other times
                                </p>
                                <input class="creator-data-entry end w-input" style="width:100px;height:100px"
                                       value="#000000"
                                       ng-model="caseStudy.cardColour"
                                       type="color">
    `                           */ ?>

                                <h2 class="edit-h2">Publication</h2>
                                <label for="name-8">Publish your case study</label>
                                <div class="w-radio">
                                    <label class="w-form-label">
                                        <input class="w-radio-input" name="isPublished" type="radio"
                                               ng-model="caseStudy.isPublished" value="1">
                                        Yes
                                    </label>
                                </div>
                                <div class="w-radio">
                                    <label class="w-form-label">
                                        <input class="w-radio-input" name="isPublished" type="radio"
                                               ng-model="caseStudy.isPublished" value="0">
                                        No
                                    </label>
                                </div>

                                <br/>
                                <label for="name-8">Publish on first page</label>
                                <div class="w-radio">
                                    <label class="w-form-label">
                                        <input class="w-radio-input" name="positionOnHomePage" type="radio"
                                               ng-model="caseStudy.positionOnHomePage" value="0">
                                        Do not publish on first page
                                    </label>
                                </div>
                                <div class="w-radio">
                                    <label class="w-form-label">
                                        <input class="w-radio-input" name="positionOnHomePage" type="radio"
                                               ng-model="caseStudy.positionOnHomePage" value="1">
                                        Publish on 1st position
                                    </label>
                                </div>
                                <div class="w-radio">
                                    <label class="w-form-label">
                                        <input class="w-radio-input" name="positionOnHomePage" type="radio"
                                               ng-model="caseStudy.positionOnHomePage" value="2">
                                        Publish on 2nd position
                                    </label>
                                </div>
                                <div class="w-radio">
                                    <label class="w-form-label">
                                        <input class="w-radio-input" name="positionOnHomePage" type="radio"
                                               ng-model="caseStudy.positionOnHomePage" value="3">
                                        Publish on 3rd position
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="tabbed-nav-buttons w-clearfix">
                            <input type="submit" class="tab-button-2 tab-button-next w-button" value="Save"/>
                            <a href="<?php echo $urlHandler->caseStudy($caseStudy) ?>"
                               class="tab-button-2 tab-button-next w-button">View case study</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/CaseStudyEditController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <script>
        $(function () {
            tinymce.init({
                selector: '#mainText, #pageIntro',
                statusbar: false,
                height: 500,
                plugins: "autoresize autolink lists link preview paste textcolor colorpicker image imagetools media",
                autoresize_bottom_margin: 5,
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

<?php require __DIR__ . '/../footer.php' ?>