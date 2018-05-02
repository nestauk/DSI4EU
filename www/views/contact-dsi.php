<?php
/** @var $urlHandler Services\URL */

require __DIR__ . '/header.php';
?>

    <div class="content-block">
        <div class="w-row">
            <div class="w-col w-col-8">
                <h1 class="content-h1"><?php _ehtml('Contact DSI4EU') ?></h1>
                <p class="intro">
                    <?php _ehtml('If you have got any questions about the DSI4EU research and website') ?>
                </p>
                <p>
                    <?php echo sprintf(
                        _html('If you have any feedback on the website'),
                        '<a href="' . $urlHandler->feedback() . '">' . _html('feedback form') . '</a>'
                    ) ?>
                </p>
                <p>
                    <?php _e('To keep in touch with the project and DSI in Europe, follow us on Twitter @DSI4EU and sign up to our newsletter.') ?>
                </p>
            </div>
            <?php require __DIR__ . '/partialViews/about-dsi.php' ?>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>