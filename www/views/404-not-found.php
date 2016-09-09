<?php
use \DSI\Service\URL;

require __DIR__ . '/header.php';
?>
    <div>
        <div class="w-section page-header">
            <div class="container-wide header">
                <h1 class="page-h1 light">Page Not Found</h1>
            </div>
        </div>

        <div class="container-wide archive">
            <div class="w-row dashboard-widgets">
                <div class="w-col w-col-12 w-col-stack notification-col">
                    <p>The page you are looking for could not be found.</p>
                    <p>It may have been moved, or removed altogether.</p>
                    <p>Please use the links or the search feature under the menu to find your way back to the website.</p>
                </div>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        jQuery(function ($) {
            $('.notification-list').on('mouseenter', '.notification-interaction-actions', function () {
                $('.notification-interaction', $(this)).css('opacity', 1);
            }).on('mouseleave', '.notification-interaction-actions', function () {
                $('.notification-interaction', $(this)).css('opacity', 0);
            })
        })
    </script>

<?php require __DIR__ . '/footer.php' ?>