<?php
/** @var $loggedInUser \DSI\Entity\User */
/** @var $isHomePage bool */
/** @var $angularModules string[] */
/** @var $pageTitle string[] */
use \DSI\Service\URL;
use \DSI\Service\Sysctl;

?>
<meta charset="utf-8">
<title><?php echo isset($pageTitle) ? show_input($pageTitle) : 'Digitalsocial.eu' ?></title>
<meta property="og:title" content="<?php echo isset($pageTitle) ? show_input($pageTitle) : 'Digitalsocial.eu' ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="generator" content="Webflow">
<link rel="stylesheet" type="text/css"
      href="<?php echo SITE_RELATIVE_PATH ?>/lib/ionicons/css/ionicons.min.css?v=<?php echo Sysctl::$version ?>">
<link rel="stylesheet" type="text/css"
      href="<?php echo SITE_RELATIVE_PATH ?>/css/normalize.css?v=<?php echo Sysctl::$version ?>">
<link rel="stylesheet" type="text/css"
      href="<?php echo SITE_RELATIVE_PATH ?>/css/components.css?v=<?php echo Sysctl::$version ?>">
<link rel="stylesheet" type="text/css"
      href="<?php echo SITE_RELATIVE_PATH ?>/css/dsi4eu.css?v=<?php echo Sysctl::$version ?>">
<link rel="stylesheet" type="text/css"
      href="<?php echo SITE_RELATIVE_PATH ?>/css/custom.css?v=<?php echo Sysctl::$version ?>">

<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js"></script>
<script>
    WebFont.load({
        google: {
            families: ["Open Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic"]
        }
    });
</script>
<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/modernizr.js?v=<?php echo Sysctl::$version ?>"></script>

<link rel="shortcut icon" type="image/x-icon" href="<?php echo SITE_RELATIVE_PATH ?>/images/ico-small.png">
<link rel="apple-touch-icon" href="<?php echo SITE_RELATIVE_PATH ?>/images/ico-large.png">

<script src="<?php echo SITE_RELATIVE_PATH ?>/js/lib/isotope/isotope.pkgd.min.js"></script>

<?php /** jQuery */ ?>
<script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH ?>/js/lib/jquery/jquery.min.js"></script>

<?php /** SweetAlert */ ?>
<script
    src="<?php echo SITE_RELATIVE_PATH ?>/lib/sweetalert-master/dist/sweetalert.min.js?v=<?php echo Sysctl::$version ?>"></script>
<link rel="stylesheet" type="text/css"
      href="<?php echo SITE_RELATIVE_PATH ?>/lib/sweetalert-master/dist/sweetalert.css?v=<?php echo Sysctl::$version ?>">
<link rel="stylesheet" type="text/css"
      href="<?php echo SITE_RELATIVE_PATH ?>/css/sweet.css?v=<?php echo Sysctl::$version ?>">

<?php /** Select2 */ ?>
<link href="<?php echo SITE_RELATIVE_PATH ?>/lib/select2/select2.min.css" rel="stylesheet"/>
<script src="<?php echo SITE_RELATIVE_PATH ?>/lib/select2/select2.full.min.js"></script>

<script>
    var SITE_RELATIVE_PATH = '<?php echo SITE_RELATIVE_PATH?>';
    var angularDependencies = [];
    var angularAppName = 'DSIApp';
</script>

<style>
    .bg-blur {
        -webkit-backdrop-filter: saturate(180%) blur(5px);
        backdrop-filter: saturate(180%) blur(5px);
    }

    .blur-20 {
        -webkit-backdrop-filter: saturate(180%) blur(20px);
        backdrop-filter: saturate(180%) blur(20px);
    }

    .el-blur {
        -webkit-filter: blur(25px);
        -moz-filter: blur(25px);
        -o-filter: blur(25px);
        -ms-filter: blur(25px);
        filter: blur(25px);
    }

    .login-field.error {
        height: 50px;
        border-style: none none solid;
        border-width: 1px;
        border-color: #000 #000 #FF3030;
        background-color: #fff;
        text-align: center;
    }

    /*  .w-input::-webkit-input-placeholder { /* WebKit browsers
        color:    #4CADDE;
    }
    .w-input:-moz-placeholder { /* Mozilla Firefox 4 to 18
        color:    #4CADDE;
    }
    .w-input::-moz-placeholder { /* Mozilla Firefox 19+
        color:    #4CADDE;
    }
    .w-input:-ms-input-placeholder { /* Internet Explorer 10+
        color:    #4CADDE;
    }
    /* keep modals centered */
    .modal-container {
        display: table;
        height: 100%;
        position: absolute;
        overflow: hidden;
        width: 100%;
    }

    .modal-helper {
        #position: absolute;
        #top: 50%;
        display: table-cell;
        vertical-align: middle;
    }

    .modal-content {
        #position: relative;
        #top: -50%;
        margin: 0 auto;
        width: 600px;
    }

    /* absolute centre divs */
    .ab-fab {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>

<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/angular.min.js?v=<?php echo Sysctl::$version ?>"></script>

<?php if (isset($angularModules['fileUpload'])) { ?>
    <?php /** ngFileUpload */ ?>
    <script
        src="<?php echo SITE_RELATIVE_PATH ?>/js/lib/ng-file-upload-bower/ng-file-upload-shim.min.js?v=<?php echo Sysctl::$version ?>"></script>
    <!-- for no html5 browsers support -->
    <script
        src="<?php echo SITE_RELATIVE_PATH ?>/js/lib/ng-file-upload-bower/ng-file-upload.min.js?v=<?php echo Sysctl::$version ?>"></script>
    <script>angularDependencies.push('ngFileUpload');</script>
<?php } ?>

<?php if (isset($angularModules['animate'])) { ?>
    <script type="text/javascript"
            src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-animate.js"></script>
    <script>angularDependencies.push('ngAnimate');</script>
<?php } ?>

<?php if (isset($angularModules['pagination'])) { ?>
    <link rel="stylesheet" type="text/css"
          href="<?php echo SITE_RELATIVE_PATH ?>/lib/bootstrap-pagination/bootstrap-pagination.css?v=<?php echo Sysctl::$version ?>">
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/lib/bootstrap-pagination/ui-bootstrap-tpls-0.2.0.js?v=<?php echo Sysctl::$version ?>"></script>
    <script>angularDependencies.push('ui.bootstrap');</script>
<?php } ?>

<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/DSIApp.js?v=<?php echo Sysctl::$version ?>"></script>