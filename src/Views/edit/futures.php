<?php

use \Models\Resource;

/** @var $urlHandler Services\URL */
/** @var $title \Models\Text */
/** @var $description \Models\Text */
/** @var $content \Models\Text */
/** @var $resources Resource[] */

\DSI\Service\JsModules::setTinyMCE(true);
\DSI\Service\JsModules::setMasonry(true);
$angularModules['fileUpload'] = true;

require __DIR__ . '/../header.php';
?>

<div class="edit-open-data-controller" ng-controller="EditFuturesController">
    <div class="content-block">
        <div class="w-row">
            <div class="w-col w-col-8">
                <?php /* <h1 class="content-h1"><?php _ehtml('Open data, research & resources') ?></h1> */ ?>
                <h1 class="content-h1"><?php _ehtml('Futures') ?></h1>

                <div>
                    <label>Title</label>
                    <input class="w-input" id="title"
                           value="<?= show_input($title->getCopy()) ?>" />
                    <div class="error" ng-bind="errors.title"></div>
                </div>

                <p class="intro"></p>

                <div>
                    <label>Description</label>
                    <input class="w-input" id="description"
                           value="<?= show_input($description->getCopy()) ?>" />
                    <div class="error" ng-bind="errors.description"></div>
                </div>

                <p class="intro"></p>

                <div>
                    <label>Content</label>
                    <textarea class="creator-data-entry end long-description w-input editor"
                              id="content"
                              data-placeholder="Cluster paragraph"><?= show_input($content->getCopy()) ?></textarea>
                    <div class="error" ng-bind="errors.content"></div>
                </div>

                <div style="text-align:right">
                    <a href="<?= $urlHandler->futures() ?>"
                       class="w-button dsi-button creat-button"
                       style="width:auto;background:white;display: inline-block;">
                        <?= __('Back') ?>
                    </a>

                    <button type="button" class="w-button dsi-button creat-button"
                            style="width:auto;display: inline-block;"
                            ng-click="saveTexts()"
                            ng-bind="loading ? '<?= __('Loading...') ?>' : '<?= __('Save page details') ?>'">
                    </button>
                </div>
            </div>
            <div class="sidebar w-col w-col-4 w-col-stack">
                <?php require __DIR__ . '/../partialViews/about-dsi.php' ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"
        src="/js/controllers/EditFuturesController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<script>
	$(function() {
		tinymce.init({
			selector: '.editor',
			statusbar: false,
			height: 500,
			plugins: "autoresize autolink lists link preview paste textcolor colorpicker image imagetools media",
			autoresize_bottom_margin: 5,
			autoresize_max_height: 500,
			menubar: false,
			toolbar1: 'styleselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | preview',
			image_advtab: true,
			paste_data_images: false,
			init_instance_callback: function(inst) {
				inst.execCommand('mceAutoResize');
			}
		});
	});
</script>

<style>
    .grid-item {
        width: 33.3333333%;
    }

    @media screen and (max-width: 767px) {
        .grid-item {
            width: 100%;
        }
    }
</style>

<script>
	$(function() {
		if( $(window).width() > 767 ) {
			$('.grid').masonry({
				itemSelector: '.grid-item',
				horizontalOrder: true
			});
		}
	})
</script>

<?php require __DIR__ . '/../footer.php' ?>
