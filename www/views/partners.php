<?php
/** @var $urlHandler \DSI\Service\URL */
require __DIR__ . '/header.php' ?>

    <div class="content-block">
        <div class="w-row">
            <div class="w-col w-col-8">
                <h1 class="content-h1">Project partners</h1>
                <p class="intro">DSI4EU is a project delivered by:</p>
                <div class="partner-block w-row">
                    <div class="w-col w-col-3">
                        <img class="partner-logo-colour" src="<?php echo SITE_RELATIVE_PATH ?>/images/nesta.png">
                    </div>
                    <div class="w-col w-col-9">
                        <p><strong>Nesta</strong> is the UK's innovation foundation. We help people and organisations
                            bring great ideas to life. We do this by providing investments and grants and mobilising
                            research, networks and skills. We are an independent charity and our work is enabled by an
                            endowment from the National Lottery. Nesta is a registered charity in England and Wales
                            1144091 and Scotland SC042833. </p>
                        <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                           href="http://nesta.org.uk" target="_blank">
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
                        <p><strong>Waag Society</strong> — the institute for art, science and technology — is a pioneer
                            in
                            the field of digital media. Over the past 22 years, the foundation has developed into an
                            institution of international stature, a platform for artistic research and experimentation,
                            and has become both a catalyst for events and a breeding ground for cultural and social
                            innovation. Waag Society explores emerging technologies, and provides art and culture with a
                            central role in the designing of new applications for novel advances in science and
                            technology. The organisation concerns itself not only with technologies related to the
                            Internet, but also with those related to biotechnology and the cognitive sciences.</p>
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
                            The Laboratory of Visual Culture (LCV) is the design research unit of SUPSI, the University
                            of Applied Sciences and Arts of Southern Switzerland. The laboratory applies interactive
                            design methods and a human-centred design perspective to develop research projects focusing
                            on people's experiences mediated by the internet and digital technologies. LCV researches
                            and develops educational models that explore the convergence of technology and design via
                            prototyping and that promote the integration of bottom-up innovation models and
                            community-driven approaches in design research and education. LCV leads social innovation
                            projects related to environmental sustainability and energy consumption, and international
                            programs on interaction design, physical computing, open design, DIY electronics, digital
                            fabrication, data visualisation, interactive cinema and computational design.
                        </p>
                        <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                           href="http://supsi.ch" target="_blank">
                            <div class="login-li long menu-li readmore-li">supsi.ch</div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </a>
                    </div>
                </div>
                <h3>DSI4EU is part of the CAPS community</h3>
                <p><strong>DSI4EU</strong> is one of a number Collective Awareness Platforms (CAPS) initiatives aimed at
                    supporting Digital Social Innovation in Europe and collaborates with the CHIC and CAPSSI initiatives
                    on developing this agenda.</p>
                <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="https://ec.europa.eu/digital-single-market/en/collective-awareness" target="_blank">
                    <div class="login-li long menu-li readmore-li">Find out more about CAPS</div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
                <p><strong>CAPSSI</strong> is an initiative which aims to design and pilot online platforms creating
                    awareness of sustainability problems and offering collaborative solutions based on innovative
                    networks of people, ideas, services and technologies enabling new forms of social innovation. CAPSSI
                    supports environment-aware efforts, grassroots processes and practices to share knowledge, achieve
                    changes in lifestyle, production and consumption patterns, and set up more participatory democratic
                    processes on a pan-European scale.</p>
                <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="https://capssi.eu/" target="_blank">
                    <div class="login-li long menu-li readmore-li">Capssi.eu</div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
                <p><strong>ChiC</strong> - The coordination and support action ChiC (Coordinating high impact for CAPS)
                    is &nbsp;a small but powerful and experienced consortium which aims to empower the community of
                    CAPSSI projects and promote their outcomes and results.</p>
                <a class="log-in-link long next-page read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="<?php echo $urlHandler->openDataResearchAndResources() ?>">
                    <div class="login-li long menu-li readmore-li">Explore our open data, research &amp; resources</div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
            </div>
            <?php require __DIR__ . '/partialViews/about-dsi.php' ?>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>