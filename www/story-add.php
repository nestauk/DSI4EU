<?php require __DIR__ . '/header.php' ?>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/StoryAddController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

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

    <div ng-controller="AddStoryController">

        <div class="w-section page-header">
            <div class="container-wide header">
                <h1 class="page-h1 light">Add a new story</h1>
            </div>
        </div>

        <div class="container-wide">
            <div class="body-content add-story">
                <div class="w-form">
                    <form class="w-clearfix" ng-submit="addStory()">
                        <div class="w-row">
                            <div class="w-col w-col-6">
                                <label class="story-label" for="Title">Title</label>
                                <div style="color:red" ng-cloak ng-show="errors.title" ng-bind="errors.title"></div>
                                <input class="w-input story-form" maxlength="256" ng-model="title"
                                       placeholder="Add the title of your story" type="text">

                                <label class="story-label" for="published-on">Published on</label>
                                <div onclick="$('#datePublished').datepicker('show');">
                                    <span style="font-size:30px" class="ion-ios-calendar-outline"></span>
                                    <input id="datePublishedReadable" style="border:0;font-size:18px;margin-left:10px"/>
                                </div>
                                <input type="text" style="display:none" id="datePublished" maxlength="256"
                                       ng-model="datePublished">
                                <br/><br/>

                                <label class="story-label" for="Title">Card grid image</label>

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

                                <label class="story-label" for="Title">Main story image</label>

                                <img ng-show="mainImage" class="story-image-upload"
                                     ng-src="{{mainImage}}">
                                <a ngf-select="uploadMainImage($file, $invalidFiles)" accept="image/*"
                                   class="w-button dsi-button story-image-upload" href="#">Upload image</a>

                                <div class="update-profile-image"
                                     ng-show="mainImageUpload.f.progress > 0 && mainImageUpload.f.progress < 100">
                                    <div style="font-size:smaller">
                                        <span ng-bind="{{mainImageUpload.errFile.name}}"></span>
                                        <span ng-bind="{{mainImageUpload.errFile.$error}}"></span>
                                        <span ng-bind="{{mainImageUpload.errFile.$errorParam}}"></span>
                                        <span class="progress" ng-show="mainImageUpload.f.progress >= 0">
                                            <div style="width:{{mainImageUpload.f.progress}}%"
                                                 ng-bind="mainImageUpload.f.progress + '%'"></div>
                                        </span>
                                    </div>
                                    <div style="color:red" ng-bind="{{mainImageUpload.errorMsg.file}}"></div>
                                </div>

                                <label class="story-label" for="Story-wysiwyg">Category</label>
                                <?php foreach ($categories AS $category) { ?>
                                    <div class="w-checkbox">
                                        <label class="w-form-label">
                                            <input class="w-checkbox-input" name="categoryID" type="radio"
                                                   ng-model="categoryID" value="<?php echo $category->getId() ?>">
                                            <?php echo $category->getName() ?>
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="w-col w-col-6">
                                <label class="story-label">Your story</label>
                                <textarea id="newStory" class="w-input story-form"></textarea>

                                <br/><br/>

                                <label class="story-label" for="Story-wysiwyg">Published</label>
                                <div class="w-checkbox">
                                    <label>
                                        <input class="w-checkbox-input" value="1"
                                               name="published" ng-model="isPublished" type="radio">
                                        Published
                                    </label>
                                </div>
                                <div class="w-checkbox">
                                    <label>
                                        <input class="w-checkbox-input" value="0"
                                               name="published" ng-model="isPublished" type="radio">
                                        Unpublished
                                    </label>
                                </div>
                            </div>
                        </div>
                        <input class="dsi-button post-story w-button"
                               type="submit" value="Save"
                               ng-value="loading ? 'Loading...' : 'Save'"
                               ng-disabled="loading">

                        <a href="<?php echo $urlHandler->blogPosts() ?>"
                           class="w-button dsi-button post-story cancel">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#newStory',
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
        $(function () {
            $("#datePublished").datepicker({
                dateFormat: 'yy-mm-dd',
                altField: "#datePublishedReadable",
                altFormat: "DD, d MM yy"
            }).datepicker("setDate", new Date());
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>