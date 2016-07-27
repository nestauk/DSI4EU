<?php
$pageTitle = 'Explore DSI';
require __DIR__ . '/header.php';

$totalProjects = (new \DSI\Repository\ProjectRepository())->countAll();
$totalOrganisations = (new \DSI\Repository\OrganisationRepository())->countAll();
?>

    <div class="w-section page-header">
        <div class="w-clearfix container-wide header">
            <h1 class="page-h1 light">Terms of use</h1>
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

<?php require __DIR__ . '/footer.php' ?>