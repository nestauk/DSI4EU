<?php
/** @var $urlHandler Services\URL */
\Services\View::setPageTitle('Partners - DSI4EU');
require __DIR__ . '/header.php' ?>

    <div class="content-block">
        <div class="w-row">
            <div class="w-col w-col-8">
                <h1 class="content-h1"><?php _ehtml('Project partners') ?></h1>
                <p class="intro"><?php _ehtml('DSI4EU is a project delivered by:') ?></p>
                <div class="partner-block w-row">
                    <div class="w-col w-col-3">
                        <img class="partner-logo-colour"
                             src="<?php echo SITE_RELATIVE_PATH ?>/images/nesta-colour-308px.png">
                    </div>
                    <div class="w-col w-col-9">
                        <p>
                            <?php echo str_replace(
                                'Nesta',
                                '<strong>Nesta</strong>',
                                _html("Nesta is the UK's innovation foundation. We help people and organisations bring great ideas to life. [...]")
                            ); ?>
                        </p>
                        <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                           href="http://www.nesta.org.uk/" target="_blank">
                            <div class="login-li long menu-li readmore-li">Nesta.org.uk</div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </a>
                    </div>
                </div>
                <div class="partner-block w-row">
                    <div class="w-col w-col-3">
                        <img class="partner-logo-colour" src="<?php echo SITE_RELATIVE_PATH ?>/images/waag.png">
                    </div>
                    <div class="w-col w-col-9">
                        <p>
                            <?php echo str_replace(
                                'Waag Society',
                                '<strong>Waag Society</strong>',
                                _html('Waag Society — the institute for art, science and technology — is a pioneer in the field of digital media. [...]')
                            ); ?>
                        </p>
                        <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                           href="http://waag.org" target="_blank">
                            <div class="login-li long menu-li readmore-li">waag.org</div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </a>
                    </div>
                </div>
                <div class="partner-block w-row">
                    <div class="w-col w-col-3">
                        <img class="partner-logo-colour" src="<?php echo SITE_RELATIVE_PATH ?>/images/supsi.png">
                    </div>
                    <div class="w-col w-col-9">
                        <p>
                            <?php echo str_replace(
                                'SUPSI',
                                '<strong>SUPSI</strong>',
                                _html('The Laboratory of Visual Culture (LCV) is the design research unit of SUPSI, [...]')
                            ); ?>
                        </p>
                        <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                           href="http://supsi.ch" target="_blank">
                            <div class="login-li long menu-li readmore-li">supsi.ch</div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </a>
                    </div>
                </div>
                <h3><?php _ehtml('DSI4EU is part of the CAPS community') ?></h3>
                <p>
                    <?php echo str_replace(
                        'DSI4EU',
                        '<strong>DSI4EU</strong>',
                        _html("DSI4EU is one of a number Collective Awareness Platforms (CAPS) initiatives aimed at [...]")
                    ); ?>
                </p>
                <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="https://ec.europa.eu/digital-single-market/en/collective-awareness" target="_blank">
                    <div class="login-li long menu-li readmore-li"><?php _ehtml('Find out more about CAPS') ?></div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
                <p>
                    <?php echo str_replace(
                        'CAPSSI',
                        '<strong>CAPSSI</strong>',
                        _html("CAPSSI is an initiative which aims to design and pilot online platforms creating awareness of [...]")
                    ); ?>
                </p>
                <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="https://capssi.eu/" target="_blank">
                    <div class="login-li long menu-li readmore-li">Capssi.eu</div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
                <p>
                    <?php echo str_replace(
                        'ChiC',
                        '<strong>ChiC</strong>',
                        _html("ChiC - The coordination and support action ChiC (Coordinating high impact for CAPS) [...]")
                    ); ?>
                </p>
                <a class="log-in-link long next-page read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="<?php echo $urlHandler->openDataResearchAndResources() ?>">
                    <div class="login-li long menu-li readmore-li"><?php _ehtml('Explore our open data, research & resources') ?></div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
            </div>
            <div class="sidebar w-col w-col-4 w-col-stack">
                <?php require __DIR__ . '/partialViews/about-dsi.php' ?>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>