<?php
require __DIR__ . '/../header.php';
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler \Services\URL */
/** @var $cluster \Models\ClusterLang */
?>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <div ng-controller="ClusterEditController"
         data-editurl="<?=$urlHandler->clusterApi($cluster->getClusterId())?>">
        <div class="header-section-grey w-section">
            <div class="container-wide">
                <h1 class="header-centre">Edit cluster</h1>
            </div>
        </div>
        <div class="section-white w-section">
            <div class="container-wide step-window">
                <form id="email-form-2" name="email-form-2" ng-submit="save()">
                    <div class="w-form">
                        <div class="w-row">
                            <div class="form-col-left w-col w-col-6">
                                <h2 class="edit-h2">Cluster Details</h2>

                                <label>Cluster title:</label>
                                <input class="creator-data-entry w-input" maxlength="256" name="name-2"
                                       placeholder="Event title" type="text"
                                       ng-model="cluster.title">
                                <div class="error" ng-bind="errors.title"></div>

                                <br/>
                                <label>Cluster description</label>
                                <textarea class="creator-data-entry end long-description w-input editor"
                                          id="description"
                                          data-placeholder="Cluster description"><?php _ehtml($cluster->getDescription()) ?></textarea>
                                <div class="error" ng-bind="errors.description"></div>

                                <br/>
                                <label>Cluster Get In Touch</label>
                                <textarea class="creator-data-entry end long-description w-input editor"
                                          id="get_in_touch"
                                          data-placeholder="Cluster Get In Touch text"><?php _ehtml($cluster->getGetInTouch()) ?></textarea>
                                <div class="error" ng-bind="errors.get_in_touch"></div>
                            </div>
                            <div class="form-col-right w-col w-col-6">
                                <h2 class="edit-h2">Cluster Images</h2>



                            </div>
                        </div>
                    </div>
                    <div class="tabbed-nav-buttons w-clearfix">
                        <input type="submit" class="tab-button-2 tab-button-next w-button" value="Save"/>
                        <a href="<?php echo $urlHandler->cluster($cluster->getClusterId()) ?>"
                           class="tab-button-2 tab-button-next w-button">
                            Back to cluster</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ClusterEditController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <script>
        $(function () {
            $('select.select2').select2();

            tinymce.init({
                selector: '.editor',
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

<?php require __DIR__ . '/../footer.php' ?>