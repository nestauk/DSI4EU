<?php
require __DIR__ . '/header.php';
/** @var $loggedInUser \DSI\Entity\User */
/** @var $caseStudies \DSI\Entity\CaseStudy[] */
?>
    <div class="creator page-header">
        <div class="container-wide header">
            <h1 class="light page-h1">Case studies</h1>
            <?php if ($loggedInUser) { ?>
                <a class="button button-bottom-right w-button" href="<?php echo \DSI\Service\URL::addCaseStudy() ?>">
                    Add case study +
                </a>
            <?php } ?>
        </div>
    </div>

    <div class="case-study-grid container-wide">
        <div class="case-studies-row case-studies-row-grid w-row">
            <?php foreach ($caseStudies AS $i => $caseStudy) { ?>
                <div class="case-study-col-<?php echo $i % 3 + 1 ?> w-col w-col-4">
                    <div class="onloadone" data-ix="fadeinuponload-<?php echo $i % 3 + 2 ?>">
                        <div class="case-study-card" data-ix="case-study-card-overlay"
                             style="background-image: url('<?php echo \DSI\Entity\Image::CASE_STUDY_CARD_BG_URL . $caseStudy->getCardImage() ?>');">
                            <div class="case-study-card-overlay"
                                 style="background-color:<?php echo $caseStudy->getCardColour() ?>"></div>
                            <div class="case-study-card-info">
                                <img class="case-study-card-logo" width="75"
                                     src="<?php echo \DSI\Entity\Image::CASE_STUDY_LOGO_URL . $caseStudy->getLogo() ?>">
                                <div class="case-study-card-p">
                                    <?php echo show_input($caseStudy->getIntroCardText()) ?>
                                </div>
                                <a class="case-study-card-read-more"
                                   href="<?php echo \DSI\Service\URL::caseStudy($caseStudy) ?>">
                                    See the case study
                                </a>
                            </div>
                            <div class="case-study-card-label w-clearfix">
                                <div class="case-study-card-name">
                                    <?php echo show_input(substr($caseStudy->getTitle(), 0, 15)) ?>
                                    <?php if (strlen($caseStudy->getTitle()) > 15) echo '...' ?>
                                </div>
                                <div class="case-study-card-name country">
                                    <?php echo show_input($caseStudy->getCountryName()) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php /*
        <div class="case-studies-row case-studies-row-grid w-row">
            <div class="case-study-col-1 w-col w-col-4">
                <div class="onloadone" data-ix="fadeinuponload-3">
                    <div class="case-study-card" data-ix="case-study-card-overlay">
                        <div class="case-study-card-overlay"></div>
                        <div class="case-study-card-info">
                            <img class="case-study-card-logo" src="images/arduino.png" width="75">
                            <div class="case-study-card-p">In 2005, Massimo Banzi, an Italian engineer and designer,
                                started the Arduino project to enable students at the Interaction Design Institute
                                Ivrea (IDII) to build electronic devices using an open-source hardware board.
                            </div>
                            <a class="case-study-card-read-more" href="#">See the case study</a>
                        </div>
                        <div class="case-study-card-label w-clearfix">
                            <div class="case-study-card-name">Arduino</div>
                            <div class="case-study-card-name country">Switzerland</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="case-study-col-2 w-col w-col-4">
                <div class="onloadtwo" data-ix="fadeinuponload">
                    <div class="case-study-card tor" data-ix="case-study-card-overlay">
                        <div class="case-study-card-overlay tor"></div>
                        <div class="case-study-card-info">
                            <img class="case-study-card-logo" src="images/tor.png" width="75">
                            <div class="case-study-card-p">The Onion Router project (TOR) is a non-profit
                                organisation that conducts research and development into online privacy and
                                anonymity.&nbsp;It has developed software tools designed to stop people – including
                                government agencies and corporations – learning web users location or tracking their
                                browsing habits.
                            </div>
                            <a class="case-study-card-read-more" href="#">See the case study</a>
                        </div>
                        <div class="case-study-card-label w-clearfix">
                            <div class="case-study-card-name">The Onion Router P...</div>
                            <div class="case-study-card-name country">Germany</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="case-study-col-3 w-col w-col-4">
                <div class="onloadthree" data-ix="fadeinuponload-2">
                    <div class="case-study-card communia" data-ix="case-study-card-overlay">
                        <div class="case-study-card-overlay communia"></div>
                        <div class="case-study-card-info">
                            <img class="case-study-card-logo" src="images/communia.png" width="75">
                            <div class="case-study-card-p">COMMUNIA – The European Thematic Network on the Digital
                                Public Domain, is an international association based in Brussels. The COMMUNIA
                                association is built on the eponymous COMMUNIA Project Thematic Network, funded by
                                the European Commission from 2007 to 2011, which issued the Public Domain Manifesto
                                and gathered over 50 members..
                            </div>
                            <a class="case-study-card-read-more" href="#">See the case study</a>
                        </div>
                        <div class="case-study-card-label w-clearfix">
                            <div class="case-study-card-name">Communia</div>
                            <div class="case-study-card-name country">Italy</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="case-studies-row case-studies-row-grid w-row">
            <div class="case-study-col-1 w-col w-col-4">
                <div class="onloadone" data-ix="fadeinuponload-5">
                    <div class="case-study-card" data-ix="case-study-card-overlay">
                        <div class="case-study-card-overlay"></div>
                        <div class="case-study-card-info">
                            <img class="case-study-card-logo" src="images/arduino.png" width="75">
                            <div class="case-study-card-p">In 2005, Massimo Banzi, an Italian engineer and designer,
                                started the Arduino project to enable students at the Interaction Design Institute Ivrea
                                (IDII) to build electronic devices using an open-source hardware board.
                            </div>
                            <a class="case-study-card-read-more" href="#">See the case study</a>
                        </div>
                        <div class="case-study-card-label w-clearfix">
                            <div class="case-study-card-name">Arduino</div>
                            <div class="case-study-card-name country">Switzerland</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="case-study-col-2 w-col w-col-4">
                <div class="onloadtwo" data-ix="fadeinuponload-2">
                    <div class="case-study-card tor" data-ix="case-study-card-overlay">
                        <div class="case-study-card-overlay tor"></div>
                        <div class="case-study-card-info">
                            <img class="case-study-card-logo" src="images/tor.png" width="75">
                            <div class="case-study-card-p">The Onion Router project (TOR) is a non-profit organisation
                                that conducts research and development into online privacy and anonymity.&nbsp;It has
                                developed software tools designed to stop people – including government agencies and
                                corporations – learning web users location or tracking their browsing habits.
                            </div>
                            <a class="case-study-card-read-more" href="#">See the case study</a>
                        </div>
                        <div class="case-study-card-label w-clearfix">
                            <div class="case-study-card-name">The Onion Router P...</div>
                            <div class="case-study-card-name country">Germany</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="case-study-col-3 w-col w-col-4">
                <div class="onloadthree" data-ix="fadeinuponload-3">
                    <div class="case-study-card communia" data-ix="case-study-card-overlay">
                        <div class="case-study-card-overlay communia"></div>
                        <div class="case-study-card-info">
                            <img class="case-study-card-logo" src="images/communia.png" width="75">
                            <div class="case-study-card-p">COMMUNIA – The European Thematic Network on the Digital
                                Public Domain, is an international association based in Brussels. The COMMUNIA
                                association is built on the eponymous COMMUNIA Project Thematic Network, funded by the
                                European Commission from 2007 to 2011, which issued the Public Domain Manifesto and
                                gathered over 50 members..
                            </div>
                            <a class="case-study-card-read-more" href="#">See the case study</a>
                        </div>
                        <div class="case-study-card-label w-clearfix">
                            <div class="case-study-card-name">Communia</div>
                            <div class="case-study-card-name country">Italy</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="case-studies-row case-studies-row-grid w-row">
            <div class="case-study-col-1 w-col w-col-4">
                <div class="onloadone" data-ix="fadeinuponload-3">
                    <div class="case-study-card" data-ix="case-study-card-overlay">
                        <div class="case-study-card-overlay"></div>
                        <div class="case-study-card-info">
                            <img class="case-study-card-logo" src="images/arduino.png" width="75">
                            <div class="case-study-card-p">In 2005, Massimo Banzi, an Italian engineer and designer,
                                started the Arduino project to enable students at the Interaction Design Institute Ivrea
                                (IDII) to build electronic devices using an open-source hardware board.
                            </div>
                            <a class="case-study-card-read-more" href="#">See the case study</a>
                        </div>
                        <div class="case-study-card-label w-clearfix">
                            <div class="case-study-card-name">Arduino</div>
                            <div class="case-study-card-name country">Switzerland</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="case-study-col-2 w-col w-col-4">
                <div class="onloadtwo" data-ix="fadeinuponload-4">
                    <div class="case-study-card tor" data-ix="case-study-card-overlay">
                        <div class="case-study-card-overlay tor"></div>
                        <div class="case-study-card-info">
                            <img class="case-study-card-logo" src="images/tor.png" width="75">
                            <div class="case-study-card-p">The Onion Router project (TOR) is a non-profit organisation
                                that conducts research and development into online privacy and anonymity.&nbsp;It has
                                developed software tools designed to stop people – including government agencies and
                                corporations – learning web users location or tracking their browsing habits.
                            </div>
                            <a class="case-study-card-read-more" href="#">See the case study</a>
                        </div>
                        <div class="case-study-card-label w-clearfix">
                            <div class="case-study-card-name">The Onion Router P...</div>
                            <div class="case-study-card-name country">Germany</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="case-study-col-3 w-col w-col-4">
                <div class="onloadthree" data-ix="fadeinuponload-5">
                    <div class="case-study-card communia" data-ix="case-study-card-overlay">
                        <div class="case-study-card-overlay communia"></div>
                        <div class="case-study-card-info">
                            <img class="case-study-card-logo" src="images/communia.png" width="75">
                            <div class="case-study-card-p">COMMUNIA – The European Thematic Network on the Digital
                                Public Domain, is an international association based in Brussels. The COMMUNIA
                                association is built on the eponymous COMMUNIA Project Thematic Network, funded by the
                                European Commission from 2007 to 2011, which issued the Public Domain Manifesto and
                                gathered over 50 members..
                            </div>
                            <a class="case-study-card-read-more" href="#">See the case study</a>
                        </div>
                        <div class="case-study-card-label w-clearfix">
                            <div class="case-study-card-name">Communia</div>
                            <div class="case-study-card-name country">Italy</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="case-studies-row case-studies-row-grid w-row">
            <div class="case-study-col-1 w-col w-col-4">
                <div class="onloadone" data-ix="fadeinuponload-3">
                    <div class="case-study-card" data-ix="case-study-card-overlay">
                        <div class="case-study-card-overlay"></div>
                        <div class="case-study-card-info">
                            <img class="case-study-card-logo" src="images/arduino.png" width="75">
                            <div class="case-study-card-p">In 2005, Massimo Banzi, an Italian engineer and designer,
                                started the Arduino project to enable students at the Interaction Design Institute Ivrea
                                (IDII) to build electronic devices using an open-source hardware board.
                            </div>
                            <a class="case-study-card-read-more" href="#">See the case study</a>
                        </div>
                        <div class="case-study-card-label w-clearfix">
                            <div class="case-study-card-name">Arduino</div>
                            <div class="case-study-card-name country">Switzerland</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="case-study-col-2 w-col w-col-4">
                <div class="onloadtwo" data-ix="fadeinuponload-4">
                    <div class="case-study-card tor" data-ix="case-study-card-overlay">
                        <div class="case-study-card-overlay tor"></div>
                        <div class="case-study-card-info">
                            <img class="case-study-card-logo" src="images/tor.png" width="75">
                            <div class="case-study-card-p">The Onion Router project (TOR) is a non-profit organisation
                                that conducts research and development into online privacy and anonymity.&nbsp;It has
                                developed software tools designed to stop people – including government agencies and
                                corporations – learning web users location or tracking their browsing habits.
                            </div>
                            <a class="case-study-card-read-more" href="#">See the case study</a>
                        </div>
                        <div class="case-study-card-label w-clearfix">
                            <div class="case-study-card-name">The Onion Router P...</div>
                            <div class="case-study-card-name country">Germany</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="case-study-col-3 w-col w-col-4">
                <div class="onloadthree" data-ix="fadeinuponload-5">
                    <div class="case-study-card communia" data-ix="case-study-card-overlay">
                        <div class="case-study-card-overlay communia"></div>
                        <div class="case-study-card-info">
                            <img class="case-study-card-logo" src="images/communia.png" width="75">
                            <div class="case-study-card-p">COMMUNIA – The European Thematic Network on the Digital
                                Public Domain, is an international association based in Brussels. The COMMUNIA
                                association is built on the eponymous COMMUNIA Project Thematic Network, funded by the
                                European Commission from 2007 to 2011, which issued the Public Domain Manifesto and
                                gathered over 50 members..
                            </div>
                            <a class="case-study-card-read-more" href="#">See the case study</a>
                        </div>
                        <div class="case-study-card-label w-clearfix">
                            <div class="case-study-card-name">Communia</div>
                            <div class="case-study-card-name country">Italy</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        */ ?>
    </div>

<?php require __DIR__ . '/footer.php' ?>