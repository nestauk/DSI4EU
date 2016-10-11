<?php
/** @var $angularModules string[] */
/** @var $pageTitle string[] */
/** @var $loggedInUser \DSI\Entity\User */
use \DSI\Service\Sysctl;

?>
<meta charset="utf-8">
<title><?php echo isset($pageTitle) ? show_input($pageTitle) : 'Digitalsocial.eu' ?></title>
<meta name="google-site-verification" content="al4Vt4vNA7eWC2OyIEa0C8vjDBFl5UZWZODih8wy3r4"/>
<meta property="og:title" content="<?php echo isset($pageTitle) ? show_input($pageTitle) : 'Digitalsocial.eu' ?>">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">

<?php /** Select2 */ ?>
<link href="<?php echo SITE_RELATIVE_PATH ?>/lib/select2/select2.min.css" rel="stylesheet"/>

<link rel="stylesheet" type="text/css"
      href="<?php echo SITE_RELATIVE_PATH ?>/lib/ionicons/css/ionicons.min.css?<?php Sysctl::echoVersion() ?>">
<link rel="stylesheet" type="text/css"
      href="<?php echo SITE_RELATIVE_PATH ?>/css/normalize.css?<?php Sysctl::echoVersion() ?>">
<link rel="stylesheet" type="text/css"
      href="<?php echo SITE_RELATIVE_PATH ?>/css/components.css?<?php Sysctl::echoVersion() ?>">
<link rel="stylesheet" type="text/css"
      href="<?php echo SITE_RELATIVE_PATH ?>/css/dsi4eu.css?<?php Sysctl::echoVersion() ?>">

<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js"></script>
<script>
    WebFont.load({
        google: {
            families: ["Montserrat:400,700", "Open Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic"]
        }
    });
</script>
<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/modernizr.js?<?php Sysctl::echoVersion() ?>"></script>

<link rel="shortcut icon" type="image/x-icon" href="<?php echo SITE_RELATIVE_PATH ?>/images/ico-small.png">
<link rel="apple-touch-icon" href="<?php echo SITE_RELATIVE_PATH ?>/images/ico-large.png">
<link href="<?php echo SITE_RELATIVE_PATH ?>/images/ico-small.png" rel="shortcut icon" type="image/x-icon">
<link href="<?php echo SITE_RELATIVE_PATH ?>/images/ico-large.png" rel="apple-touch-icon">

<script src="<?php echo SITE_RELATIVE_PATH ?>/js/lib/isotope/isotope.pkgd.min.js"></script>

<?php /** jQuery */ ?>
<script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH ?>/js/lib/jquery/jquery.min.js"></script>

<?php /** SweetAlert */ ?>
<script
    src="<?php echo SITE_RELATIVE_PATH ?>/lib/sweetalert-master/dist/sweetalert.min.js?<?php Sysctl::echoVersion() ?>"></script>
<link rel="stylesheet" type="text/css"
      href="<?php echo SITE_RELATIVE_PATH ?>/lib/sweetalert-master/dist/sweetalert.css?<?php Sysctl::echoVersion() ?>">
<link rel="stylesheet" type="text/css"
      href="<?php echo SITE_RELATIVE_PATH ?>/css/sweet.css?<?php Sysctl::echoVersion() ?>">

<?php /** Select2 */ ?>
<script src="<?php echo SITE_RELATIVE_PATH ?>/lib/select2/select2.full.js"></script>

<script>
    var SITE_RELATIVE_PATH = '<?php echo SITE_RELATIVE_PATH?>';
    var angularDependencies = [];
    var angularAppName = 'DSIApp';
</script>

<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/angular.min.js?<?php Sysctl::echoVersion() ?>"></script>

<?php if (isset($angularModules['fileUpload'])) { ?>
    <?php /** ngFileUpload */ ?>
    <script
        src="<?php echo SITE_RELATIVE_PATH ?>/js/lib/ng-file-upload-bower/ng-file-upload-shim.min.js?<?php Sysctl::echoVersion() ?>"></script>
    <!-- for no html5 browsers support -->
    <script
        src="<?php echo SITE_RELATIVE_PATH ?>/js/lib/ng-file-upload-bower/ng-file-upload.min.js?<?php Sysctl::echoVersion() ?>"></script>
    <script>angularDependencies.push('ngFileUpload');</script>
<?php } ?>

<?php if (isset($angularModules['animate'])) { ?>
    <script type="text/javascript"
            src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-animate.js"></script>
    <script>angularDependencies.push('ngAnimate');</script>
<?php } ?>

<?php if (isset($angularModules['pagination'])) { ?>
    <link rel="stylesheet" type="text/css"
          href="<?php echo SITE_RELATIVE_PATH ?>/lib/bootstrap-pagination/bootstrap-pagination.css?<?php Sysctl::echoVersion() ?>">
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/lib/bootstrap-pagination/ui-bootstrap-tpls-0.2.0.js?<?php Sysctl::echoVersion() ?>"></script>
    <script>angularDependencies.push('ui.bootstrap');</script>
<?php } ?>

<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/DSIApp.js?<?php Sysctl::echoVersion() ?>"></script>

<link rel="stylesheet" type="text/css"
      href="<?php echo SITE_RELATIVE_PATH ?>/css/custom.css?<?php Sysctl::echoVersion() ?>">

<?php if ($loggedInUser) { ?>
    <style>
        .hero-cta {
            display: none
        }
    </style>
<?php } ?>
