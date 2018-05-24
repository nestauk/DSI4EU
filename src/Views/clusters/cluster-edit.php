<?php
require __DIR__ . '/../header.php';
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler \Services\URL */

/** @var $cluster \Models\ClusterLang */

use \DSI\Entity\Image;

?>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <div ng-controller="ClusterEditController"
         data-editurl="<?= $urlHandler->clusterApi($cluster->getClusterId()) ?>"
         data-editimageurl="<?= $urlHandler->clusterImgApi() ?>">

        <div class="modal" style="display:block" ng-if="modals.image" ng-cloak>
            <div class="modal-container">
                <div class="modal-helper">
                    <div class="modal-content" style="height:auto">
                        <h2 class="centered modal-h2">
                            Add new cluster image
                        </h2>
                        <div class="w-form">
                            <form id="email-form-3" name="email-form-3" ng-submit="saveClusterImage()">
                                <a class="dsi-button story-image-upload w-button" href="#"
                                   ng-if="!editingImage.id"
                                   ngf-select="uploadClusterImage($file, $invalidFiles)"
                                   ng-bind="editingImage.loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Select image') ?>'">
                                </a>

                                <div ng-if="editingImage.path" ng-cloak>
                                    <img class="story-image-upload"
                                         ng-src="{{editingImage.path}}">

                                    Link:
                                    <input type="text" ng-model="editingImage.link"/>

                                    <button type="submit" class="dsi-button story-image-upload w-button"
                                            ng-bind="editingImage.loading ? '<?php _ehtml('Loading...') ?>' : '<?php _ehtml('Save cluster image') ?>'">
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="cancel" ng-click="closeImageModal()"><?php _ehtml('Cancel') ?></div>
                    </div>
                </div>
            </div>
        </div>


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
                                       placeholder="Cluster title" type="text"
                                       ng-model="cluster.title">
                                <div class="error" ng-bind="errors.title"></div>

                                <div style="margin-top:50px">
                                    <label>Cluster subtitle:</label>
                                    <input class="creator-data-entry w-input" maxlength="256" name="name-2"
                                           placeholder="Cluster subtitle" type="text"
                                           ng-model="cluster.subtitle">
                                    <div class="error" ng-bind="errors.subtitle"></div>
                                </div>

                                <div style="margin-top:50px">
                                    <label>Cluster paragraph</label>
                                    <textarea class="creator-data-entry end long-description w-input editor"
                                              id="paragraph"
                                              data-placeholder="Cluster paragraph"><?php _ehtml($cluster->getParagraph()) ?></textarea>
                                    <div class="error" ng-bind="errors.paragraph"></div>
                                </div>

                                <div style="margin-top:50px">
                                    <label>Cluster description</label>
                                    <textarea class="creator-data-entry end long-description w-input editor"
                                              id="description"
                                              data-placeholder="Cluster description"><?php _ehtml($cluster->getDescription()) ?></textarea>
                                    <div class="error" ng-bind="errors.description"></div>
                                </div>

                                <div style="margin-top:50px">
                                    <label>Cluster Get In Touch</label>
                                    <textarea class="creator-data-entry end long-description w-input editor"
                                              id="get_in_touch"
                                              data-placeholder="Cluster Get In Touch text"><?php _ehtml($cluster->getGetInTouch()) ?></textarea>
                                    <div class="error" ng-bind="errors.get_in_touch"></div>
                                </div>

                                <div style="margin-top:150px">
                                    <div class="tabbed-nav-buttons w-clearfix">
                                        <input type="submit" class="tab-button-2 tab-button-next w-button"
                                               value="Save"/>
                                        <a href="<?php echo $urlHandler->cluster($cluster->getClusterId()) ?>"
                                           class="tab-button-2 tab-button-next w-button">
                                            Back to cluster</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-col-right w-col w-col-6">
                                <h2 class="edit-h2">Cluster Images</h2>
                                <div ng-repeat="image in cluster.images" ng-cloak style="margin-bottom:10px;">
                                    <img ng-src="{{image.path}}"
                                         ng-click="openEditClusterImage(image)"
                                         style="width:100px;cursor: pointer">
                                    {{image.link}}
                                    <button type="button" style="color:red"
                                            ng-click="deleteClusterImage(image)">X
                                    </button>
                                </div>

                                <h2 ng-if="cluster.images.length < 3">
                                    <a href="#" ng-click="openNewClusterImage($event)">
                                        Add new cluster image
                                    </a>
                                </h2>

                            </div>
                        </div>
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