<?php
/** @var $urlHandler Services\URL */

require __DIR__ . '/header.php';
?>

    <div class="content-block">
        <div class="w-row">
            <div class="w-col w-col-8">
                <h1 class="content-h1"><?php _ehtml('Open data, research & resources') ?></h1>
                <p class="intro">
                    <?php _ehtml('DSI4EU is committed to being open and transparent') ?>
                </p>
                <p class="p-head">
                    <?php _ehtml('You can read all of our previous and current research publications here.') ?>
                </p>
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
                       href="<?= SITE_RELATIVE_PATH ?>/uploads/What next for digital social innovation Realising the potential of people and technology to tackle social challenges.pdf" target="_blank">
                        <div class="info-card resource">
                            <img class="research-paper-img" src="<?php echo SITE_RELATIVE_PATH ?>/images/what-next.png">
                            <h3>What Next for Digital Social Innnovation</h3>
                            <p>Realising the potential of people and technology to tackle social challenges</p>
                            <div class="log-in-link long next-page read-more w-clearfix" data-ix="log-in-arrow">
                                <div class="login-li long menu-li readmore-li">View pdf</div>
                                <img class="login-arrow"
                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                            </div>
                        </div>
                    </a>
                </div>
                
                
                <div class="w-col w-col-4">
                    <a class="resource-card w-inline-block"
                       href="<?= SITE_RELATIVE_PATH ?>/uploads/digital-social-toolkit.pdf" target="_blank">
                        <div class="info-card resource">
                            <img class="research-paper-img" src="<?php echo SITE_RELATIVE_PATH ?>/images/tools.png">
                            <h3>DSI Toolkit</h3>
                            <p>A collaboratively-developed toolkit to support projects to scale sustainably</p>
                            <div class="log-in-link long next-page read-more w-clearfix" data-ix="log-in-arrow">
                                <div class="login-li long menu-li readmore-li">Read the toolkit</div>
                                <img class="login-arrow"
                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                            </div>
                        </div>
                    </a>
                </div>
                <div class="w-col w-col-4">
                    <a class="resource-card w-inline-block"
                       href="http://www.nesta.org.uk/media_colorbox/5740/media_small/und" target="_blank">
                        <div class="info-card resource">
                            <img class="research-paper-img" src="<?php echo SITE_RELATIVE_PATH ?>/images/reportpdf.png">
                            <h3>Introduction to dsi</h3>
                            <p>Understand what we mean by digital social innovation and what the future looks like</p>
                            <div class="log-in-link long next-page read-more w-clearfix" data-ix="log-in-arrow">
                                <div class="login-li long menu-li readmore-li">Watch the video</div>
                                <img class="login-arrow"
                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                            </div>
                        </div>
                    </a>
                </div>


            </div>
            
            <!-- added second row to resources -->
            
        <div class="w-row">
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

    </div>        
            
            
            
            
            <!-- end of second row -->
        </div>
    </div>
    <div class="content-block">
        <p class="bold-p">
            <strong>
                <?php _ehtml('If you are interested in further exploring the data behind DSI4EU you can:') ?>
            </strong>
        </p>
        <ul>
            <li><p>
                    <?php _ehtml('Access all the anonymised data we have captured on DSI in Europe via the DSI4EU open data set.') ?>
                </p>
                <p><strong><?php _ehtml('Projects data:') ?></strong>
                    <a href="https://digitalsocial.eu/export/projects.json">json</a>,
                    <a href="https://digitalsocial.eu/export/projects.csv">csv</a>,
                    <a href="https://digitalsocial.eu/export/projects.xml">xml</a>
                </p>
                <p><strong><?php _ehtml('Organisations data:') ?></strong>
                    <a href="https://digitalsocial.eu/export/organisations.json">json</a>,
                    <a href="https://digitalsocial.eu/export/organisations.csv">csv</a>,
                    <a href="https://digitalsocial.eu/export/organisations.xml">xml</a>
                </p>
            </li>
            <li>
                <?php _ehtml('Download the source code. All of the code used to develop this site will be shared') ?>
                <a href="https://github.com/nestauk/DSI4EU" target="_blank"><?php _ehtml('Website') ?></a>
                |
                <a href="https://github.com/nestauk/DSI4EU_Dataviz" target="_blank"><?php _ehtml('Data visualisation') ?></a>
            </li>
        </ul>
        <a class="log-in-link long next-page read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
           href="<?php echo $urlHandler->contactDSI() ?>">
            <div class="login-li long menu-li readmore-li"><?php _ehtml('Contact DSI4EU') ?></div>
            <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
        </a>
    </div>

<?php require __DIR__ . '/footer.php' ?>