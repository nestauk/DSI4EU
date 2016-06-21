<?php
require __DIR__ . '/header.php';
$totalProjects = (new \DSI\Repository\ProjectRepository())->countProjects();
$totalOrganisations = (new \DSI\Repository\OrganisationRepository())->countOrganisations();
?>

    <div class="w-section page-header">
        <div class="w-clearfix container-wide header">
            <h1 class="page-h1 light">Explore DSI</h1>
            <div class="dsi4eu-stats">
                So far
                <?php echo number_format($totalOrganisations) ?> Organisations
                have collaborated on
                <?php echo number_format($totalProjects) ?> projects
            </div>
        </div>
    </div>

    <div class="w-section section-grey">
        <div class="container-wide">
            <div class="w-row">
                <div class="w-col w-col-6 intro-col">
                    <h2 class="home-h2">What is the purpose of DSI4EU?</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.</p>
                </div>
                <div class="w-col w-col-6 intro-col">
                    <h2 class="home-h2">What do the data visualisations below show?</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et justo cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="w-section section-white stats">
        <div class="tab-buttons-container"></div>
        <div class="container-wide">
            <div class="w-tabs explore-tabs" data-duration-in="300" data-duration-out="100">
                <div class="w-tab-menu tabs-menu-explore">
                    <a class="w-tab-link w-inline-block tab-button" data-w-tab="Tab 1">
                        <div>100 Organisations</div>
                    </a>
                    <a class="w-tab-link w--current w-inline-block tab-button" data-w-tab="Tab 2">
                        <div>200 Projects</div>
                    </a>
                    <a class="w-tab-link w-inline-block tab-button" data-w-tab="Tab 3">
                        <div>30 Countries</div>
                    </a>
                </div>
                <div class="w-tab-content">
                    <div class="w-tab-pane" data-w-tab="Tab 1">
                        <img src="images/3.png">
                    </div>
                    <div class="w-tab-pane w--tab-active" data-w-tab="Tab 2">
                        <img src="images/2.png">
                    </div>
                    <div class="w-tab-pane" data-w-tab="Tab 3">
                        <img src="images/1.png">
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>