<?php
/** @var $urlHandler \DSI\Service\URL */

require __DIR__ . '/header.php';
?>
    <div>
        <div class="w-section page-header">
            <div class="container-wide header">
                <h1 class="page-h1 light">Token expired</h1>
            </div>
        </div>

        <div class="container-wide archive">
            <div class="w-row dashboard-widgets">
                <div class="w-col w-col-12 w-col-stack notification-col">
                    <p>The page you are looking for has expired.</p>
                    <p>
                        Please request a new link from your
                        <a href="<?php echo $urlHandler->dashboard() ?>">dashboard</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>