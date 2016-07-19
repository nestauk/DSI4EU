<?php
require __DIR__ . '/header.php';
/** @var $term string */
/** @var $stories \DSI\Entity\Story[] */
/** @var $projects \DSI\Entity\Project[] */
/** @var $organisations \DSI\Entity\Organisation[] */
?>
    <div class="w-section page-header stories-header">
        <div class="container-wide header">
            <h1 class="page-h1 light">SEARCH RESULTS FOR: <?php echo show_input($term) ?></h1>
        </div>
    </div>

    <?php require(__DIR__ . '/partialViews/search-results.php'); ?>

    <div class="container-wide archive">
        <div class="w-row dashboard-widgets">
            <div class="w-col w-col-4 w-col-stack notification-col">
                <div class="dashboard-widget">
                    <h3 class="card-h3">Stories</h3>
                    <?php if ($stories) { ?>
                        <?php foreach ($stories AS $story) { ?>
                            <a class="search-result-link full-page-result"
                               href="<?php echo \DSI\Service\URL::story($story->getId(), $story->getTitle()) ?>">
                                <?php echo show_input($story->getTitle()) ?>
                            </a>
                        <?php } ?>
                    <?php } else { ?>
                        No stories found
                    <?php } ?>
                </div>
            </div>
            <div class="w-col w-col-4 w-col-stack">
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
                        No projects found
                    <?php } ?>
                </div>
            </div>
            <div class="w-col w-col-4 w-col-stack">
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
                        No organisations found
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>