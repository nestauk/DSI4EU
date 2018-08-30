<?php
/** @var $urlHandler Services\URL */
/** @var $homePageCaseStudies \DSI\Entity\CaseStudy[] */

require __DIR__ . '/../header.php'
?>

    <div class="cookies-policy-controller content-block">
        <div class="w-row">
            <div class="w-col w-col-8 w-col-stack">
                <h1 class="content-h1">What is DSI?</h1>

                <p class="intro">
                    Digital social innovation brings together people and digital technologies to tackle social and
                    environmental challenges.
                </p>
                <p class="separator">
                    Across the world, thousands of people, projects and organisations are using digital technologies to
                    tackle social and environmental challenges in fields ranging from
                    <a href="http://digitalsocial.eu/cluster/1">healthcare</a>,
                    <a href="http://digitalsocial.eu/cluster/2">education and employment</a> to
                    <a href="http://digitalsocial.eu/cluster/3">democratic participation</a>,
                    <a href="https://digitalsocial.eu/cluster/5/migration-and-integration">migration</a> and
                    <a href="https://digitalsocial.eu/clusters/4">the environment</a>.
                    These initiatives use a broad
                    range of established and emerging technologies - including collaborative platforms, open data,
                    citizen sensing, digital fabrication, open hardware, blockchain, machine learning, and augmented and
                    virtual reality - to empower citizens to collaborate and deliver social impact. They have the
                    potential to transform the way our public services operate, revitalise civic life and allow citizens
                    to become direct participants in tackling social challenges.
                </p>

                <p>We call this field <strong>digital social innovation</strong> (DSI), a term which overlaps
                    significantly with other terms including tech for good, social tech and civic tech. </p>

                <p>DSI projects are characterised by:</p>
                <ul>
                    <li>the <strong>primacy of social or environmental impact</strong> over financial return;</li>
                    <li>a dedication to <strong>openness, collaboration and citizen empowerment</strong>.</li>
                </ul>

                <p>The broad aims of DSI are to:</p>
                <ul>
                    <li>harness digital technologies to <strong>improve lives</strong> and <strong>reorient
                            technology</strong> towards more social ends;
                    </li>
                    <li><strong>empower citizens</strong> to take more control over their lives, and to use their
                        collective knowledge and skills to positive effect;
                    </li>
                    <li>Increase the <strong>accountability and transparency</strong> of government, business and civil
                        society;
                    </li>
                    <li>foster and promote <strong>alternatives to the dominant technological and business
                            models</strong> &mdash; alternatives which are open and collaborative rather than closed and
                        competitive;
                    </li>
                    <li>use technology to create a <strong>more environmentally sustainable</strong> society</li>
                </ul>
                <p>To see examples of DSI in practice, take a look at our library of
                    <a href="<?= $urlHandler->caseStudies() ?>">case studies</a> of innovation from across and
                    beyond Europe and explore our <a href="https://digitalsocial.eu/viz/">data visualisation</a> of DSI
                    projects and organisations. For a deeper look into the field and the most exciting opportunities and
                    most pressing challenges, check out our report
                    <a href="http://bit.ly/DSINext"><em>What next for digital social innovation?</em></a>.
                </p>
                <p>If you&rsquo;re working on DSI, <a href="<?= $urlHandler->register() ?>">register now</a> and get
                    your project or organisation onto the map in just a few minutes.</p>
            </div>
        </div>
        <div class="w-row">
            <br>
            <h3 class="centered title"><?php _ehtml('Case Studies') ?></h3>
            <?php foreach ($homePageCaseStudies AS $i => $caseStudy) { ?>
                <div class="w-col w-col-4">
                    <a class="case-study-ind w-inline-block" data-ix="scaleimage"
                       href="<?php echo $urlHandler->caseStudy($caseStudy) ?>">
                        <div class="case-study-img-container">
                            <div class="_<?php echo $i % 3 + 1 ?> case-study-img"
                                 style="background-image: url('<?php echo \DSI\Entity\Image::CASE_STUDY_CARD_BG_URL . $caseStudy->getCardImage() ?>');"></div>
                        </div>
                        <h3 class="case-study-card-h3"><?php echo show_input($caseStudy->getTitle()) ?></h3>
                        <p class="cradp"><?php echo show_input($caseStudy->getIntroCardText()) ?></p>
                        <div class="log-in-link read-more w-clearfix" data-ix="log-in-arrow">
                            <div class="login-li menu-li readmore-li"><?php _ehtml('Read more') ?></div>
                            <img class="login-arrow"
                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

<?php require __DIR__ . '/../footer.php' ?>