<?php
/** @var $urlHandler Services\URL */

require __DIR__ . '/header.php';
?>
    <div ng-controller="TerminateAccountController"
         data-returnurl="<?php echo $urlHandler->home() ?>">
        <div class="w-section page-header">
            <div class="container-wide header">
                <h1 class="page-h1 light">Terminate account</h1>
            </div>
        </div>
    </div>

    <script>
        var translate = new Translate();
        <?php foreach([
            'Are you sure you want to terminate your account?',
            'Yes',
            'Your account has been terminated.',
            'Success!',
        ] AS $translate) { ?>
        translate.set('<?php echo show_input($translate)?>', '<?php _ehtml($translate)?>');
        <?php } ?>
    </script>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/TerminateAccountController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>