<?php

/** @var $urlHandler URL */
use Services\URL;

?>
<!DOCTYPE html>
<html data-wf-site="56e2e31a1b1f8f784728a08c" data-wf-page="56fbef6ecf591b312d56f8be">
<head>
    <?php require __DIR__ . '/partialViews/head.php' ?>
</head>
<body class="login-body">

<div class="register-controller ab-fab log-in-section">
    <div class="w-row">
        <div class="content-left w-col w-col-12">
            <a href="<?php echo $urlHandler->home() ?>">
                <img class="log-in-logo" src="/images/dark_1.svg">
            </a>
            <h2><?php _ehtml('Accept Policy') ?></h2>

            <div class="form-wrapper w-form">
                <form id="email-form" method="post"
                      action="">

                    <div class="check-box">
                        <label>
                            <input type="checkbox" name="accept-terms" <?= $_POST['accept-terms'] ? 'checked' : '' ?>>
                            I accept that all data provided, with the exception of my email address, will be available
                            publicly on digitalsocial.eu
                        </label>
                        <?php if (isset($errors['accept-terms'])) { ?>
                            <div class="log-in-error">
                                <?php echo show_input($errors['accept-terms']) ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="check-box">
                        We would like to email you updates about the DSI programme including regular newsletters,
                        project updates, new research and publications, invitations to events and the
                        occasional request to take part in research of surveys.
                        <br><br>

                        <label>
                            <input type="checkbox"
                                   name="email-subscription" <?= $_POST['email-subscription'] ? 'checked' : '' ?>>
                            I would like to subscribe to the DSI mailing list
                        </label>

                        <br>
                        You can unsubscribe by clicking the link in our emails, or emailing info@nesta.org.uk. We
                        promise to keep your details safe and secure. We won’t share your details outside of Nesta
                        without your permission. Find out more about how we use personal information in our Privacy
                        Policy.
                    </div>

                    <div class="modal-footer">
                        <div ng-hide="registered">
                            <button type="submit" class="auto ll log-in-link w-clearfix w-inline-block"
                                    data-ix="log-in-arrow" name="register"
                                    style="width:250px;display:block">
                                <span class="login-li menu-li"><?php _ehtml('Continue') ?></span>
                                <img class="login-arrow"
                                     src="/images/ios7-arrow-thin-right.png">
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"
        src="/js/dsi4eu.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>
<!--[if lte IE 9]>
<script src="/js/lib/placeholders/placeholders.min.js"></script>
<![endif]-->

<script type="text/javascript"
        src="/main.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php include(__DIR__ . '/partialViews/googleAnalytics.html'); ?>

</body>
</html>