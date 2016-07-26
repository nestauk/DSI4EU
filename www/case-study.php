<?php
require __DIR__ . '/header.php';
/** @var $loggedInUser \DSI\Entity\User */
/** @var $caseStudy \DSI\Entity\CaseStudy */
/** @var $caseStudies \DSI\Entity\CaseStudy[] */
?>

    <div class="header-large-section">
        <div class="header-large"
             style="background-image: linear-gradient(180deg, rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)), url('<?php echo \DSI\Entity\Image::CASE_STUDY_HEADER_URL . $caseStudy->getHeaderImage() ?>');">
            <div class="container-wide container-wide-header-large">
                <h1 class="header-large-h1-centre"
                    data-ix="fadeinuponload"><?php echo show_input($caseStudy->getTitle()) ?></h1>
                <p class="header-large-desc" data-ix="fadeinuponload-2">
                    <?php echo show_input($caseStudy->getIntroCardText()) ?>
                </p>
                <?php if ($loggedInUser) { ?>
                    <a class="button button-bottom-right edit-case-study w-button"
                       href="<?php echo \DSI\Service\URL::caseStudyEdit($caseStudy) ?>">Edit case study +</a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="case-study-main">
        <div class="container-wide">
            <?php if ($caseStudy->getLogo()) { ?>
                <div class="case-study-logo" data-ix="fadeinuponload-3">
                    <img class="case-study-logo-over ab-fab"
                         src="<?php echo \DSI\Entity\Image::CASE_STUDY_LOGO_URL . $caseStudy->getLogo() ?>">
                </div>
            <?php } ?>
            <div class="case-study-single-container w-container">
                <h2 class="centered" data-ix="fadeinuponload-4">Introduction</h2>
                <p class="centered" data-ix="fadeinuponload-5">
                    <?php echo show_input($caseStudy->getIntroPageText()) ?>
                </p>
                <h4 class="case-study-intro-detail centered" data-ix="fadeinuponload-5">
                    <?php echo show_input($caseStudy->getTitle()) ?>
                    <?php if ($caseStudy->getRegion()) { ?>
                        is based in <?php echo $caseStudy->getRegion()->getCountry()->getName() ?> and
                    <?php } ?>
                    has been running since 2005
                </h4>
                <div class="centered tagged">Tagged under: <span class="tag">Technology</span> <span
                        class="tag">Science</span> <span class="tag">Obfuscation</span>
                </div>
                <h2 class="centered" data-ix="fadeinup">Overview</h2>
                <p class="case-study-main-text" data-ix="fadeinup">
                    <?php echo $caseStudy->getMainText() ?>
                </p>
                <?php if ($caseStudy->getUrl()) { ?>
                    <div class="centered url-block" data-ix="fadeinup">
                        <h2>Interested in finding out more?</h2>
                        <a class="button-more w-button" href="<?php echo $caseStudy->getUrl() ?>">
                            <?php echo $caseStudy->getButtonLabel() ?>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="related-case-studies">
        <div class="container-wide">
            <h2 class="reccomendation">More case studies</h2>
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
                                    <?php if ($caseStudy->getRegion()) { ?>
                                        <div class="case-study-card-name country">
                                            <?php echo show_input($caseStudy->getRegion()->getCountry()->getName()) ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>