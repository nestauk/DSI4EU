<?php
/** @var $urlHandler \DSI\Service\URL */

require __DIR__ . '/header.php';
?>

    <div class="content-block">
        <div class="w-row">
            <div class="w-col w-col-8">
                <h1 class="content-h1">Open data, research &amp; Resources</h1>
                <p class="intro">DSI4EU is committed to being open and transparent about our research and the data we
                    capture.</p>
                <p class="p-head">You can read all of our previous and current research publications here. All of these
                    are published under a Creative Commons licence.</p>
                <a class="log-in-link long read-more w-inline-block" data-ix="log-in-arrow" href="#"></a>
            </div>
            <?php require __DIR__ . '/partialViews/about-dsi.php' ?>
        </div>
    </div>
    <div class="content-directory">
        <div class="content">
            <div class="w-row">
                <div class="w-col w-col-4">
                    <a class="resource-card w-inline-block"
                       href="http://www.nesta.org.uk/media_colorbox/5740/media_small/und" target="_blank">
                        <div class="info-card resource">
                            <img class="research-paper-img" src="<?php echo SITE_RELATIVE_PATH ?>/images/reportpdf.png">
                            <h3>Introduction to dsi</h3>
                            <p>Understand what we mean by digital social innovation and what the future looks like</p>
                            <div class="log-in-link long next-page read-more w-clearfix" data-ix="log-in-arrow">
                                <div class="login-li long menu-li readmore-li">Watch video</div>
                                <img class="login-arrow"
                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="w-col w-col-4">
                    <a class="resource-card w-inline-block"
                       href="http://www.nesta.org.uk/sites/default/files/dsireport.pdf" target="_blank">
                        <div class="info-card resource">
                            <img class="research-paper-img" src="<?php echo SITE_RELATIVE_PATH ?>/images/report.png">
                            <h3>DSI Report</h3>
                            <p>Growing a digital social innovation ecosystem for Europe</p>
                            <div class="log-in-link long next-page read-more w-clearfix" data-ix="log-in-arrow">
                                <div class="login-li long menu-li readmore-li">View pdf</div>
                                <img class="login-arrow"
                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="w-col w-col-4">
                    <a class="resource-card w-inline-block" href="#"></a>
                </div>
            </div>
        </div>
    </div>
    <div class="content-block">
        <p class="bold-p"><strong>If you are interested in further exploring the data behind DSI4EU you can:</strong>
        </p>
        <ul>
            <li><p><strong>Access </strong>all the anonymised data we have captured on DSI in Europe via the DSI4EU open
                data set. The data includes information on DSI projects and organisations that have been registered
                on this site.</p>
                <p><strong>Projects data:</strong> <a href="https://digitalsocial.eu/export/projects.json">json</a>, <a href="https://digitalsocial.eu/export/projects.csv">csv</a>, <a href="https://digitalsocial.eu/export/projects.xml">xml</a> </p><p><strong>Organisations data:</strong> <a href="https://digitalsocial.eu/export/organisations.json">json</a>, <a href="https://digitalsocial.eu/export/organisations.csv">csv</a>, <a href="https://digitalsocial.eu/export/organisations.xml">xml</a></p>
            </li>
            <li><strong>Download</strong>&nbsp;the source code. All of the code used to develop this site will be shared
                on a <strong>Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</strong> on
                Github. This will be available in the next few months
            </li>
        </ul>
        <a class="log-in-link long next-page read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
           href="<?php echo $urlHandler->contactDSI() ?>">
            <div class="login-li long menu-li readmore-li">Contact DSI</div>
            <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
        </a>
    </div>

<?php require __DIR__ . '/footer.php' ?>