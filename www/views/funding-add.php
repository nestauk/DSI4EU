<?php
require __DIR__ . '/header.php';
/** @var $fundingTypes \DSI\Entity\FundingType[] */
/** @var $fundingTargets \DSI\Entity\FundingTarget[] */
/** @var $fundingSources \DSI\Entity\FundingSource[] */
/** @var $countries \DSI\Entity\Country[] */
/** @var $loggedInUser \DSI\Entity\User */
?>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <div ng-controller="FundingAddController">
        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8">
                    <h1 class="content-h1">Add a new funding opportunity</h1>
                    <p class="intro"></p>
                </div>
                <div class="sidebar w-col w-col-4">
                    <h1 class="content-h1">Actions</h1>
                    <a class="sidebar-link" href="<?php echo $urlHandler->funding() ?>"><span
                            class="green">-&nbsp;</span>Cancel</a>
                </div>
            </div>
        </div>
        <div class="content-directory">
            <div class="container-wide step-window">
                <div class="w-form">
                    <form id="email-form-2" name="email-form-2" ng-submit="add()">
                        <div class="w-row">
                            <div class="form-col-left w-col w-col-6">
                                <h2 class="edit-h2">Funding text</h2>

                                <label for="name-2">Funding title:</label>
                                <input class="creator-data-entry w-input" id="name-2" maxlength="256"
                                       ng-model="funding.title"
                                       name="name-2" placeholder="Funding title" type="text">
                                <div class="error" ng-bind="errors.title"></div>

                                <label for="name-3">Funding link:</label>
                                <input class="creator-data-entry w-input" data-name="Name 3" maxlength="256"
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

                                <br/>
                                <label for="name-9">Type of funding or support: </label>
                                <select class="select2 creator-data-entry end w-input"
                                        id="fundingTypeID"
                                        data-placeholder="Select type of funding or support">
                                    <option></option>
                                    <?php foreach ($fundingTypes AS $type) { ?>
                                        <option value="<?php echo $type->getId() ?>">
                                            <?php echo show_input($type->getTitle()) ?>
                                        </option>
                                    <?php } ?>
                                </select>

                                <br/><br/>
                                <label for="name-9">Target organisations and projects: </label>
                                <br/>
                                <?php foreach ($fundingTargets AS $target) { ?>
                                    <label>
                                        <input type="checkbox" name="target[]" value="<?php echo $target->getId() ?>"/>
                                        <?php echo show_input($target->getTitle()) ?>
                                    </label>
                                <?php } ?>

                                <br/>
                                <label for="name-9">Source: </label>
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
                                <label for="name-8">Country: </label>
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
                    <input type="submit" class="tab-button-2 tab-button-next w-button" value="Save"/>
                    <a class="tab-button-2 tab-button-next w-button" href="<?php echo $urlHandler->funding() ?>">
                        Back to funding</a>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/FundingAddController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <script>
        $(function () {
            $('select.select2').select2();

            $("#closingDate").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                yearRange: "-10:+20"
            });
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>