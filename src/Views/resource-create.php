<?php
/** @var $urlHandler \Services\URL */
$angularModules['fileUpload'] = true;
\DSI\Service\JsModules::setTinyMCE(true);
\DSI\Service\JsModules::setJqueryUI(true);

require __DIR__ . '/header.php'
?>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/OpenResourceAddController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

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

    <div ng-controller="OpenResourceAddController">

        <div class="w-section page-header">
            <div class="container-wide header">
                <h1 class="page-h1 light">Add a new resource</h1>
            </div>
        </div>

        <div class="container-wide">
            <div class="body-content add-story">
                <div class="w-form">
                    <form class="w-clearfix" ng-submit="addResource()">
                        <div class="w-row">
                            <div class="w-col w-col-6">
                                <label class="story-label" for="Title">Title</label>
                                <div style="color:red" ng-cloak ng-show="errors.title" ng-bind="errors.title"></div>
                                <input class="w-input story-form" maxlength="256" ng-model="title"
                                       style="border:1px solid #999;" placeholder="Add the title of your story"
                                       type="text"/>

                                <label class="story-label">Description</label>
                                <textarea ng-model="description" class="w-input story-form"
                                          style="border:1px solid #999;" maxlength="140"></textarea>

                                <label class="story-label" for="Title">Link text</label>
                                <div style="color:red" ng-cloak ng-show="errors.linkText"
                                     ng-bind="errors.linkText"></div>
                                <input class="w-input story-form" maxlength="256" ng-model="linkText"
                                       style="border:1px solid #999;" placeholder="" type="text"/>

                                <label class="story-label" for="Title">Link url</label>
                                <div style="color:red" ng-cloak ng-show="errors.linkUrl" ng-bind="errors.linkUrl"></div>
                                <input class="w-input story-form" maxlength="256" ng-model="linkUrl"
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

                                <input class="dsi-button post-story w-button"
                                       type="submit" value="Save"
                                       ng-value="loading ? 'Loading...' : 'Save'"
                                       ng-disabled="loading">

                                <a href="<?php echo $urlHandler->openDataResearchAndResourcesEdit() ?>"
                                   class="w-button dsi-button post-story cancel">Cancel</a>
                            </div>
                        </div>
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
            autoresize_bottom_margin: 3,
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