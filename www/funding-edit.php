<?php
require __DIR__ . '/header.php';
/** @var $fundingSources \DSI\Entity\FundingSource[] */
/** @var $urlHandler \DSI\Service\URL */
/** @var $countries \DSI\Entity\Country[] */
/** @var $loggedInUser \DSI\Entity\User */
/** @var $funding \DSI\Entity\Funding */
?>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <div ng-controller="FundingEditController"
         data-editurl="<?php echo $urlHandler->editFundingJson($funding->getId()) ?>">
        <div class="header-section-grey w-section">
            <div class="container-wide">
                <h1 class="header-centre">Edit funding oportunity</h1>
            </div>
        </div>
        <div class="section-white w-section">
            <div class="container-wide step-window">
                <form id="email-form-2" name="email-form-2" ng-submit="save()">
                    <div class="w-form">
                        <div class="w-row">
                            <div class="form-col-left w-col w-col-6">
                                <h2 class="edit-h2">Funding text</h2>

                                <label for="name-2">Funding title:</label>
                                <input class="creator-data-entry w-input" id="name-2" maxlength="256"
                                       ng-model="funding.title"
                                       name="name-2" placeholder="Funding title" type="text">
                                <div class="error" ng-bind="errors.title"></div>

                                <label for="name-3">Funding link:</label>
                                <input class="creator-data-entry w-input" data-name="Name 3" id="name-3" maxlength="256"
                                       ng-model="funding.url"
                                       name="name-3" placeholder="Funding link" type="text">
                                <div class="error" ng-bind="errors.url"></div>

                                <label>Funding description</label>
                                <textarea class="creator-data-entry end long-description w-input"
                                          id="description"
                                          ng-model="funding.description"
                                          data-placeholder="Funding description"></textarea>
                                <div class="error" ng-bind="errors.description"></div>
                            </div>
                            <div class="form-col-right w-col w-col-6">
                                <h2 class="edit-h2">Funding details</h2>

                                <label for="name-4">Funding closing date</label>
                                <input class="creator-data-entry w-input" data-name="Name 4" id="closingDate"
                                       maxlength="256"
                                       ng-model="funding.closingDate"
                                       name="name-4" placeholder="When is the funding deadline" type="text">
                                <div class="error" ng-bind="errors.closingDate"></div>

                                <label for="name-9">Source</label>
                                <select class="select2 creator-data-entry end w-input"
                                        id="fundingSource" data-tags="true"
                                        data-placeholder="Select source or add new one">
                                    <option></option>
                                    <?php foreach ($fundingSources AS $source) { ?>
                                        <option value="<?php echo $source->getTitle() ?>">
                                            <?php echo show_input($source->getTitle()) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <div class="error" ng-bind="errors.fundingSource"></div>

                                <br/>
                                <label for="name-8">Country</label>
                                <select class="select2 creator-data-entry end w-input"
                                        id="countryID"
                                        data-placeholder="Select country">
                                    <option></option>
                                    <?php foreach ($countries AS $country) { ?>
                                        <?php echo show_input($country->getName()) ?>
                                        <option value="<?php echo $country->getId() ?>">
                                            <?php echo show_input($country->getName()) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <div class="error" ng-bind="errors.country"></div>

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
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/FundingEditController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <script>
        $(function () {
            $('select.select2').select2();

            /*
             tinymce.init({
             selector: '#description',
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
             */

            $("#closingDate").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+20"
            });
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>