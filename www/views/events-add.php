<?php
require __DIR__ . '/header.php';
/** @var $loggedInUser \DSI\Entity\User */
?>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <div ng-controller="EventAddController">
        <div class="header-section-grey w-section">
            <div class="container-wide">
                <h1 class="header-centre"><?php _ehtml('Add an event')?></h1>
            </div>
        </div>
        <div class="section-white w-section">
            <div class="container-wide step-window">
                <form id="email-form-2" name="email-form-2" ng-submit="add()">
                    <div class="w-form">
                        <div class="w-row">
                            <div class="form-col-left w-col w-col-6">
                                <h2 class="edit-h2">Event text</h2>

                                <label for="name-2">Event title:</label>
                                <input class="creator-data-entry w-input" id="name-2" maxlength="256"
                                       ng-model="event.title"
                                       name="name-2" placeholder="Event title" type="text">
                                <div class="error" ng-bind="errors.title"></div>

                                <label for="name-3">Event link:</label>
                                <input class="creator-data-entry w-input" data-name="Name 3" id="name-3" maxlength="256"
                                       ng-model="event.url"
                                       name="name-3" placeholder="Event link" type="text">
                                <div class="error" ng-bind="errors.url"></div>

                                <label>Short description</label>
                                <textarea class="creator-data-entry end long-description w-input"
                                          id="shortDescription"
                                          ng-model="event.shortDescription"
                                          placeholder="Short description"
                                          data-placeholder="Short description"></textarea>
                                <div class="error" ng-bind="errors.shortDescription"></div>

                                <br/>
                                <label>Event description</label>
                                <textarea class="creator-data-entry end long-description w-input"
                                          id="description"
                                          placeholder="Event description"
                                          data-placeholder="Event description"></textarea>
                                <div class="error" ng-bind="errors.description"></div>
                            </div>
                            <div class="form-col-right w-col w-col-6">
                                <h2 class="edit-h2">Event details</h2>

                                <div ng-cloak>
                                    <div>
                                        <label class="story-label">In which country is the event taking place?</label>
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
                                        <div class="log-in-error" ng-show="errors.region"
                                             ng-bind="errors.region"></div>
                                    </div>
                                    <div ng-show="regionsLoading">
                                        Loading...
                                    </div>
                                </div>
                                <br />

                                <label>Address</label>
                                <textarea class="creator-data-entry end long-description w-input"
                                          ng-model="event.address"
                                          placeholder="Event Address"></textarea>
                                <div class="error" ng-bind="errors.address"></div>

                                <label for="name-4">Event start date</label>
                                <input class="creator-data-entry w-input" data-name="Name 4" id="startDate"
                                       maxlength="256"
                                       ng-model="event.startDate"
                                       name="name-4" placeholder="When is the event first day" type="text">
                                <div class="error" ng-bind="errors.startDate"></div>

                                <label for="name-4">Event end date</label>
                                <input class="creator-data-entry w-input" data-name="Name 4" id="endDate"
                                       maxlength="256"
                                       ng-model="event.endDate"
                                       name="name-4" placeholder="When is the event last day" type="text">
                                <div class="error" ng-bind="errors.endDate"></div>

                                <label for="name-4">Contact phone number</label>
                                <input class="creator-data-entry w-input" maxlength="256"
                                       ng-model="event.phoneNumber"
                                       name="name-4" placeholder="Contact phone number" type="text">
                                <div class="error" ng-bind="errors.phoneNumber"></div>

                                <label for="name-4">Contact email address</label>
                                <input class="creator-data-entry w-input" maxlength="256"
                                       ng-model="event.emailAddress"
                                       name="name-4" placeholder="Contact email address" type="text">
                                <div class="error" ng-bind="errors.emailAddress"></div>

                                <label for="name-4">Price</label>
                                <input class="creator-data-entry w-input" maxlength="256"
                                       ng-model="event.price"
                                       name="name-4" placeholder="Price" type="text">
                                <div class="error" ng-bind="errors.price"></div>
                                <i>* Leave empty price for free event</i>
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

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/EventAddController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <script>
        $(function () {
            $('select.select2').select2();

            tinymce.init({
                selector: '#description',
                statusbar: false,
                height: 500,
                plugins: "autoresize autolink lists link preview paste textcolor colorpicker image imagetools media",
                autoresize_bottom_margin: 3,
                autoresize_max_height: 500,
                menubar: false,
                toolbar1: 'styleselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | preview',
                image_advtab: true,
                paste_data_images: false
            });

            $("#startDate, #endDate").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+20"
            });
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>