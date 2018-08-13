<?php

use \Models\CaseStudy;

require __DIR__ . '/../header.php';
/** @var $loggedInUser \DSI\Entity\User */
/** @var $caseStudies CaseStudy[] */
/** @var $userCanManageCaseStudies bool */
/** @var $urlHandler Services\URL */
/** @var $tags \Models\Tag[] */
?>

    <div ng-controller="CaseStudiesController"
         data-jsonurl="<?php echo $urlHandler->caseStudies('json') ?>">

        <div class="content-block">
            <div class="intro-row w-row">
                <div class="w-col w-col-8 w-col-stack">
                    <h1 class="content-h1"><?php _ehtml('Case Studies') ?></h1>
                    <p class="intro"><?php _ehtml('What does digital social innovation look like in practice?') ?></p>
                    <p><?php _ehtml('Here you can find examples of digital social innovation to inform and inspire you.') ?></p>
                </div>
                <div class="sidebar w-col w-col-4 w-col-stack">
                    <?php if ($userCanManageCaseStudies) { ?>
                        <h1 class="content-h1"><?php _ehtml('Actions') ?></h1>
                        <a class="sidebar-link" href="<?php echo $urlHandler->addCaseStudy() ?>">
                            <span class="green">-&nbsp;</span>
                            <?php _ehtml('Add new case study') ?>
                        </a>
                    <?php } ?>
                </div>
            </div>

            <div class="w-row">
                <div class="filter-col-left w-col w-col-4">
                    <div class="filter-bar info-card">
                        <div class="w-form">
                            <form id="email-form" name="email-form">
                                <h3 class="sidebar-h3"><?php _ehtml('Filter case studies') ?></h3>
                                <?php foreach ($tags as $tag) { ?>
                                    <div class="filter-checkbox w-checkbox">
                                        <label class="w-form-label">
                                            <input class="w-checkbox-input" type="checkbox" name="tagID"
                                                   value="<?= $tag->getId() ?>">
                                            <?= _html($tag->{\Models\Tag::Name}) ?>
                                        </label>
                                    </div>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="w-col w-col-8 case-study-row-p w-row">
                    <?php foreach ($caseStudies AS $i => $caseStudy) { ?>
                        <div class="w-col w-col-6 js-case-study" data-tags='<?= $caseStudy->caseStudyTagIDs() ?>'>
                            <a class="case-study-ind w-inline-block" data-ix="scaleimage"
                               href="<?php echo $urlHandler->caseStudyModel($caseStudy) ?>">
                                <div class="case-study-img-container">
                                    <div class="_<?php echo ($i % 3) + 1 ?> case-study-img"
                                         style="background-image: url('<?php echo \DSI\Entity\Image::CASE_STUDY_CARD_BG_URL . $caseStudy->{CaseStudy::CardImage} ?>');"></div>
                                </div>
                                <h3 class="case-study-card-h3">
                                    <?php echo show_input($caseStudy->{CaseStudy::Title}) ?>
                                    <?php if ($userCanManageCaseStudies AND !$caseStudy->{CaseStudy::IsPublished}) {
                                        echo ' <span style="color:red">(' . _html('Unpublished') . ')</span>';
                                    } ?>
                                </h3>
                                <p class="cradp">
                                    <?php echo show_input($caseStudy->{CaseStudy::IntroCardText}) ?>
                                </p>
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
        </div>
    </div>

    <script type="text/javascript"
            src="/js/controllers/CaseStudiesController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/../footer.php' ?>