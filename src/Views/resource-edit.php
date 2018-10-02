<?php

use \Models\Resource;

/** @var $urlHandler \Services\URL */
/** @var $resource \Models\Resource */
/** @var $clusters \Models\Cluster[] */
/** @var $types \Models\TypeOfResource[] */
/** @var $authors \Models\AuthorOfResource[] */

$angularModules['fileUpload'] = true;
\DSI\Service\JsModules::setTinyMCE(true);
\DSI\Service\JsModules::setJqueryUI(true);

require __DIR__ . '/header.php'
?>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/OpenResourceEditController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <style>
        .progress {
            display: inline-block;
            width: 100px;
            border: 3px groove #CCC;
        }

        .progress div {
            font-size: smaller;
            background: orange;
            width: 0;
        }
    </style>

    <div ng-controller="OpenResourceEditController"
         data-loadurl="<?= $urlHandler->openResourceEditApi($resource) ?>">

        <div class="w-section page-header">
            <div class="container-wide header">
                <h1 class="page-h1 light">Edit resource</h1>
            </div>
        </div>

        <div class="container-wide">
            <div class="body-content add-story">
                <div class="w-form" ng-cloak>
                    <div ng-if="!resource.id">
                        Loading resource details...
                    </div>
                    <form class="w-clearfix" ng-submit="saveResource()" ng-if="resource.id">
                        <div class="w-row">
                            <div class="w-col w-col-6">
                                <label class="story-label" for="Title">Title</label>
                                <div style="color:red" ng-cloak ng-show="errors.title" ng-bind="errors.title"></div>
                                <input class="w-input story-form" maxlength="256" ng-model="resource.title"
                                       style="border:1px solid #999;" placeholder="Add the title of your story"
                                       type="text"/>

                                <label class="story-label">Description</label>
                                <textarea ng-model="resource.description" class="w-input story-form"
                                          style="border:1px solid #999;" maxlength="140"></textarea>

                                <label class="story-label" for="Title">Link text</label>
                                <div style="color:red" ng-cloak ng-show="errors.link_text"
                                     ng-bind="errors.link_text"></div>
                                <input class="w-input story-form" maxlength="256" ng-model="resource.link_text"
                                       style="border:1px solid #999;" placeholder="" type="text"/>

                                <label class="story-label" for="Title">Link file</label>
                                <a ngf-select="uploadLinkFile($file, $invalidFiles)" accept="*/*"
                                   class="w-button dsi-button story-image-upload" href="#">Upload file</a>

                                <label class="story-label" for="Title">Link url</label>
                                <div style="color:red" ng-cloak ng-show="errors.link_url"
                                     ng-bind="errors.link_url"></div>
                                <input class="w-input story-form" maxlength="256" ng-model="resource.link_url"
                                       style="border:1px solid #999;" placeholder="" type="text"/>

                                <label class="story-label" for="Title">Resource image</label>
                                <img ng-show="featuredImage" class="story-image-upload"
                                     ng-src="{{featuredImage}}">
                                <a ngf-select="uploadFeaturedImage($file, $invalidFiles)" accept="image/*"
                                   class="w-button dsi-button story-image-upload" href="#">Upload image</a>

                                <div class="update-profile-image"
                                     ng-show="featuredImageUpload.f.progress > 0 && featuredImageUpload.f.progress < 100">
                                    <div style="font-size:smaller">
                                        <span ng-bind="{{featuredImageUpload.errFile.name}}"></span>
                                        <span ng-bind="{{featuredImageUpload.errFile.$error}}"></span>
                                        <span ng-bind="{{featuredImageUpload.errFile.$errorParam}}"></span>
                                        <span class="progress" ng-show="featuredImageUpload.f.progress >= 0">
                                            <div style="width:{{featuredImageUpload.f.progress}}%"
                                                 ng-bind="featuredImageUpload.f.progress + '%'"></div>
                                        </span>
                                    </div>
                                    <div style="color:red" ng-bind="{{featuredImageUpload.errorMsg.file}}"></div>
                                </div>
                            </div>
                            <div class="w-col w-col-6">
                                <label class="story-label" for="Title">Clusters</label>
                                <?php foreach ($clusters AS $cluster) { ?>
                                    <label>
                                        <input type="checkbox" ng-model="resource.clusters[<?= $cluster->getId() ?>]"
                                               value="1" ng-true-value="1" ng-false-value="0"/>
                                        <?= show_input($cluster->clusterLangs()->first()->{\Models\Relationship\ClusterLang::Title}) ?>
                                    </label>
                                <?php } ?>

                                <br>
                                <label class="story-label">Type of Resource</label>
                                <?php foreach ($types AS $type) { ?>
                                    <label>
                                        <input type="checkbox"
                                               ng-model="resource.<?= Resource::Types ?>[<?= $type->getId() ?>]"
                                               value="1" ng-true-value="1" ng-false-value="0"/>
                                        <?= show_input($type->{\Models\TypeOfResource::Name}) ?>
                                    </label>
                                <?php } ?>

                                <br>
                                <div style="color:red"
                                     ng-show="errors.<?= Resource::AuthorID ?>"
                                     ng-bind="errors.<?= Resource::AuthorID ?>"></div>
                                <label class="story-label">Author</label>
                                <select name="author_id" title="author" ng-model="resource.<?= Resource::AuthorID ?>">
                                    <option value=""> - Select an author -</option>

                                    <?php foreach ($authors AS $author) { ?>
                                        <option value="<?= $author->getId() ?>">
                                            <?= _html($author->{\Models\AuthorOfResource::Name}) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <input class="dsi-button post-story w-button"
                               type="submit" value="Save"
                               ng-value="loading ? 'Loading...' : 'Save'"
                               ng-disabled="loading">

                        <a href="<?php echo $urlHandler->openDataResearchAndResourcesEdit() ?>"
                           class="w-button dsi-button post-story cancel">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <input name="image" id="my_image" type="file" style="display:none"/>

    <script>
        tinymce.init({
            selector: '#newStory',
            statusbar: false,
            height: 500,
            plugins: "autoresize autolink lists link preview paste textcolor colorpicker image imagetools media",
            autoresize_bottom_margin: 5,
            autoresize_max_height: 500,
            menubar: false,
            toolbar1: 'styleselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | preview',
            image_advtab: true,
            paste_data_images: false,

            file_browser_callback_types: 'image',
            convert_urls: false,
            file_picker_callback: function (callback, value, meta) {
                if (meta.filetype === 'image') {
                    DSI_Helpers.TinyMCEImageUpload({
                        element: $('#my_image'),
                        uploadUrl: '<?php echo $urlHandler->uploadImage()?>',
                        callback: callback
                    });
                }
            }
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>