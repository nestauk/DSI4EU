<?php
/** @var $urlHandler \DSI\Service\URL */

require __DIR__ . '/header.php'
?>

    <div class="content-block">
        <div class="w-row">
            <div class="w-col w-col-8 w-col-stack">
                <h1 class="content-h1">The DSI4EU project</h1>
                <p class="intro">Across Europe there is a growing movement of people developing inspiring digital solutions to social challenges. We call this digital social innovation (DSI).</p>
                <p>These range from social networks for those living with chronic health conditions, to online platforms for citizen participation in policymaking, to using open data to create more transparency about public spending.</p>
                <p>This site is a community for digital social innovators in Europe. People, projects and organisations interested in DSI can use the site to showcase their work, learn about DSI, find collaborators for projects and find information on events and funding opportunities.</p>
                <p>This DSI project is a continuation of a 2012-2014 project that mapped and defined the DSI community in Europe. Alongside the platform, we will be carrying out research, recommending policy, developing a sustainability toolkit and holding various events across Europe.</p>
                <!-- <p>Newsletter section</p> -->
                <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="http://digitalsocial.eu/open-data-research-and-resources">
                    <div class="login-li long menu-li readmore-li">Read more about previous research into mapping and
                        defining DSI in Europe
                    </div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
                <p class="bold-p"><strong>Specifically the project:</strong>
                </p>
                <ul>
                    <li>Creates an online resource on digitalsocial.eu that enables those interested in, and working
                        on, digital social innovation in Europe to learn about it, showcase their work, find new
                        collaborators and learn about events and new funding opportunities.
                    </li>
                    <li>
                        Engages the existing communities of digital social innovators in the network.
                    </li>
                    <li>
                        Carries out research on how to grow DSI in Europe.
                    </li>
                    <li>Uses insights about the European DSI network to develop a set of recommendations about how
                        policymakers and funders can best support, engage with and make the most of DSI and ensure it
                        can continue to grow in the future.
                    </li>
                    <li class="li-bottom">
                        Provides a set of tools that supports people and organisations interested in
                        developing digital solutions that address social challenges.
                    </li>
                </ul>
                <p>DSI4EU is supported by the European Union and funded under the Horizon 2020 Programme. Grant
                    agreement no 688192.</p>
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