<?php
/** @var $urlHandler \DSI\Service\URL */

require __DIR__ . '/header.php';
?>

    <div class="content-block">
        <div class="w-row">
            <div class="w-col w-col-8">
                <h1 class="content-h1">Contact DSI4EU</h1>
                <p class="intro">
                    If you have got any questions about the DSI4EU research and website, please don’t
                    hesitate to get in touch. You can drop us an email on contact@digitalsocial.eu.&nbsp;
                </p>
                <p>
                    If you have any feedback on the website please complete the
                    <a href="<?php echo $urlHandler->feedback() ?>">feedback form</a>.
                </p>
                <p>
                    To keep in touch with the project and DSI in Europe, you can
                    <a target="_blank" href="https://twitter.com/dsi4eu">follow DSI4EU on Twitter</a>.
                </p>
            </div>
            <?php require __DIR__ . '/partialViews/about-dsi.php' ?>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>