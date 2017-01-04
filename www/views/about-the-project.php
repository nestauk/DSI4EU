<?php
/** @var $urlHandler \DSI\Service\URL */

require __DIR__ . '/header.php'
?>

    <div class="content-block">
        <div class="w-row">
            <div class="w-col w-col-8 w-col-stack">
                <h1 class="content-h1">The DSI4EU project</h1>
                <p class="intro">Across Europe there is a growing movement of people developing inspiring digital solutions to social challenges. We call this digital social innovation (DSI).</p>
                <p>These digital solutions have developed thanks to big advances in technology, such as the open source and open data movements, low-cost open hardware, crowdsourcing and Internet of Things (IoT). By empowering citizens and engaging them in civic action, they provide new ways of building social movements, delivering public services and creating social impact in fields as diverse as healthcare, education, democracy, environment, transport and housing.</p>
                <p>We believe that DSI has the potential to dramatically improve the way our public services, communities and businesses work. The Digital Social Innovation for Europe (DSI4EU) project seeks to make this potential a reality.</p>
               <!--  <p>This DSI project is a continuation of a 2012-2014 project that mapped and defined the DSI community in Europe. Alongside the platform, we will be carrying out research, recommending policy, developing a sustainability toolkit and holding various events across Europe.</p>
                <!-- <p>Newsletter section</p> 
                <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="http://digitalsocial.eu/open-data-research-and-resources">
                    <div class="login-li long menu-li readmore-li">Read more about previous research into mapping and
                        defining DSI in Europe
                    </div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a> -->
                <p>At the heart of the project is the digitalsocial.eu platform, which has four main functions.</p>
                <p class="bold-p"><strong>Users can:</strong>
                </p>
                <ul>
                    <li>showcase their work and tag their organisations as part of networks like research alliances or membership bodies. 
                    </li>
                    <li>explore the DSI community and search organisations and projects to understand what else is going on in the world of DSI, who they need to be speaking to, and who might be a potential partner or collaborator. 
                    </li>
                    <li>identify funding and support opportunities, along with DSI-related events, across Europe. They can also submit and promote their own event to the DSI community. 
                    </li>
                    <li class="li-bottom">learn about what is happening across Europe through case studies and blogs focusing on DSI pioneers and their projects, and current trends. 
                    </li>
                   
                </ul>
                <p>All of the data on the digitalsocial.eu platform is open and accessible, and all the source code behind the platform is available open-source on GitHub. The digitalsocial.eu platform is free to use. </p>
                <p>Alongside the digitalsocial.eu platform, the DSI4EU project is:</p>
                <ul>
                    <li>researching what the DSI landscape in  Europe looks like, understanding routes to growth and scale, and exploring how DSI projects and organisations understand their impact;
                    </li>
                    <li>holding a number of events across Europe to bring together the DSI community and influence policy;
                    </li>
                    <li>developing a set of policy recommendations to support DSI; and
                    </li>
                    <li class="li-bottom">creating a set of tools to support digital social innovators, especially those involved in the open hardware movement.
                    </li>
                   
                </ul>
                <a class="log-in-link long next-page read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="<?php echo $urlHandler->partners() ?>">
                    <div class="login-li long menu-li readmore-li">Find out about our partners</div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
            </div>
            <?php require __DIR__ . '/partialViews/about-dsi.php' ?>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>