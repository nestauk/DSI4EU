<?php
require __DIR__ . '/header.php';
/** @var $term string */
/** @var $caseStudies \DSI\Entity\CaseStudy[] */
/** @var $blogPosts \DSI\Entity\Story[] */
/** @var $projects \DSI\Entity\Project[] */
/** @var $organisations \DSI\Entity\Organisation[] */
?>
<style>
    p.nothingfound{
        padding:0 20px;
    }
</style>
    <div class="w-section page-header stories-header">
        <div class="container-wide header">
            <h1 class="page-h1 light">SEARCH RESULTS FOR: <?php echo show_input($term) ?></h1>
        </div>
    </div>

    <div class="container-wide archive">
        <div class="w-row dashboard-widgets">
            <div class="w-col w-col-3 w-col-stack">
                <div class="dashboard-widget">
                    <h3 class="card-h3">Case Studies</h3>
                    <?php if ($caseStudies) { ?>
                        <?php foreach ($caseStudies AS $caseStudy) { ?>
                            <a class="search-result-link full-page-result"
                               href="<?php echo \DSI\Service\URL::caseStudy($caseStudy) ?>">
                                <?php echo show_input($caseStudy->getTitle()) ?>
                            </a>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="nothingfound">No case studies found</p>
                    <?php } ?>
                </div>
            </div>
            <div class="w-col w-col-3 w-col-stack">
                <div class="dashboard-widget">
                    <h3 class="card-h3">Blog posts</h3>
                    <?php if ($blogPosts) { ?>
                        <?php foreach ($blogPosts AS $post) { ?>
                            <a class="search-result-link full-page-result"
                               href="<?php echo \DSI\Service\URL::story($post) ?>">
                                <?php echo show_input($post->getTitle()) ?>
                            </a>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="nothingfound">No blog posts found</p>
                    <?php } ?>
                </div>
            </div>
            <div class="w-col w-col-3 w-col-stack">
                <div class="dashboard-widget">
                    <h3 class="card-h3">Projects</h3>
                    <?php if ($projects) { ?>
                        <?php foreach ($projects AS $project) { ?>
                            <a class="search-result-link full-page-result"
                               href="<?php echo \DSI\Service\URL::project($project) ?>">
                                <?php echo show_input($project->getName()) ?>
                            </a>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="nothingfound">No projects found</p>
                    <?php } ?>
                </div>
            </div>
            <div class="w-col w-col-3 w-col-stack">
                <div class="dashboard-widget">
                    <h3 class="card-h3">Organisations</h3>
                    <?php if ($organisations) { ?>
                        <?php foreach ($organisations AS $organisation) { ?>
                            <a class="search-result-link full-page-result"
                               href="<?php echo \DSI\Service\URL::organisation($organisation) ?>">
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