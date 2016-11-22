<?php
require __DIR__ . '/header.php';
/** @var $term string */
/** @var $caseStudies \DSI\Entity\CaseStudy[] */
/** @var $blogPosts \DSI\Entity\Story[] */
/** @var $projects \DSI\Entity\Project[] */
/** @var $organisations \DSI\Entity\Organisation[] */

if (!isset($urlHandler))
    $urlHandler = new \DSI\Service\URL();

?>
    <style>
        p.nothingfound {
            padding: 0 20px;
        }
    </style>

    <div class="container-wide search-results-list">
        <div class="intro-title">
            <h1 class="content-h1">SEARCH RESULTS FOR: <?php echo show_input($term) ?></h1>
        </div>
        <div class="main-search-results w-row">
            <div class="search-col w-col w-col-3">
                <h2 class="full-menu-h2"><?php _ehtml('News & blogs') ?></h2>
                <?php if ($blogPosts) { ?>
                    <?php foreach ($blogPosts AS $post) { ?>
                        <a class="full-menu-link"
                           href="<?php echo $urlHandler->blogPost($post) ?>">
                            <?php echo show_input($post->getTitle()) ?>
                        </a>
                    <?php } ?>
                <?php } else { ?>
                    <div><?php _ehtml('No blog posts found') ?></div>
                <?php } ?>
            </div>
            <div class="search-col w-col w-col-3">
                <h2 class="full-menu-h2"><?php _ehtml('Projects') ?></h2>
                <?php if ($projects) { ?>
                    <?php foreach ($projects AS $project) { ?>
                        <a class="full-menu-link"
                           href="<?php echo $urlHandler->project($project) ?>">
                            <?php echo show_input($project->getName()) ?>
                        </a>
                    <?php } ?>
                <?php } else { ?>
                    <div><?php _ehtml('No projects found') ?></div>
                <?php } ?>
            </div>
            <div class="search-col w-col w-col-3">
                <h2 class="full-menu-h2"><?php _ehtml('Organisations') ?></h2>
                <?php if ($organisations) { ?>
                    <?php foreach ($organisations AS $organisation) { ?>
                        <a class="full-menu-link"
                           href="<?php echo $urlHandler->organisation($organisation) ?>">
                            <?php echo show_input($organisation->getName()) ?>
                        </a>
                    <?php } ?>
                <?php } else { ?>
                    <div><?php _ehtml('No organisations found') ?></div>
                <?php } ?>
            </div>
            <div class="search-col w-col w-col-3">
                <h2 class="full-menu-h2"><?php _ehtml('Case Studies') ?></h2>
                <?php if ($caseStudies) { ?>
                    <?php foreach ($caseStudies AS $caseStudy) { ?>
                        <a class="full-menu-link"
                           href="<?php echo $urlHandler->caseStudy($caseStudy) ?>">
                            <?php echo show_input($caseStudy->getTitle()) ?>
                        </a>
                    <?php } ?>
                <?php } else { ?>
                    <div><?php _ehtml('No case studies found') ?></div>
                <?php } ?>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>