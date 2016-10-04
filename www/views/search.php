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
        <div class="dashboard-widgets w-row">
            <div class="notification-col w-col w-col-3">
                <div class="dashboard-widget">
                    <h3 class="card-h3 search-h3">Case studies</h3>
                    <?php if ($caseStudies) { ?>
                        <?php foreach ($caseStudies AS $caseStudy) { ?>
                            <a class="full-page-result search-result-link"
                               href="<?php echo $urlHandler->caseStudy($caseStudy) ?>">
                                <?php echo show_input($caseStudy->getTitle()) ?>
                            </a>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="nothingfound">No case studies found</p>
                    <?php } ?>
                </div>
            </div>
            <div class="w-col w-col-3">
                <div class="dashboard-widget">
                    <h3 class="card-h3 search-h3">Blogs</h3>
                    <?php if ($blogPosts) { ?>
                        <?php foreach ($blogPosts AS $post) { ?>
                            <a class="full-page-result search-result-link"
                               href="<?php echo $urlHandler->blogPost($post) ?>">
                                <?php echo show_input($post->getTitle()) ?>
                            </a>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="nothingfound">No blog posts found</p>
                    <?php } ?>
                </div>
            </div>
            <div class="w-col w-col-3">
                <div class="dashboard-widget">
                    <h3 class="card-h3 search-h3">Projects</h3>
                    <?php if ($projects) { ?>
                        <?php foreach ($projects AS $project) { ?>
                            <a class="full-page-result search-result-link"
                               href="<?php echo $urlHandler->project($project) ?>">
                                <?php echo show_input($project->getName()) ?>
                            </a>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="nothingfound">No projects found</p>
                    <?php } ?>
                </div>
            </div>
            <div class="w-col w-col-3">
                <div class="dashboard-widget">
                    <h3 class="card-h3 search-h3">Organisations</h3>
                    <?php if ($organisations) { ?>
                        <?php foreach ($organisations AS $organisation) { ?>
                            <a class="full-page-result search-result-link"
                               href="<?php echo $urlHandler->organisation($organisation) ?>">
                                <?php echo show_input($organisation->getName()) ?>
                            </a>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="nothingfound">No organisations found</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>