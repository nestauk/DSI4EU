<?php
/** @var $urlHandler \DSI\Service\URL */

require __DIR__ . '/header.php'
?>

    <div class="content-block">
        <div class="w-row">
            <div class="w-col w-col-8 w-col-stack">
                <h1 class="content-h1">The DSI4EU project</h1>
                <p class="intro">Digital technologies and the internet have transformed many
                    areas of business – from Google and Amazon to Airbnb and Kickstarter. Huge sums of public money have
                    supported digital innovation in business, as well as in fields ranging from the military to
                    espionage. But there has been much less systematic support for innovations that use digital
                    technology to address social challenges.</p>
                <p>Across Europe a growing movement of tech entrepreneurs are developing
                    inspiring digital solutions to social challenges. These range from social networks for those living
                    with chronic health conditions, to online platforms for citizen participation in policymaking, to
                    using open data to create more transparency about public spending. We call this digital social
                    innovation (DSI)We have set up digitalsocial.eu &nbsp;to build a network of digital social
                    innovation in Europe. People and projects interested in DSI can use the site to showcase their work,
                    learn about DSI , find collaborators for future projects &nbsp;and find information on events and
                    funding opportunities for DSI.</p>
                <a class="log-in-link long read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="http://www.nesta.org.uk/project/digital-social-innovation">
                    <div class="login-li long menu-li readmore-li">Read more about previous research into mapping and
                        defining DSI in Europe
                    </div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
                <p class="bold-p"><strong>Specifically the project:</strong>
                </p>
                <ul>
                    <li>Creates an online resource on www.digitalsocial.eu that enables those interested in, and working
                        on, digital social innovation in Europe to learn about DSI, showcase their work, find new
                        collaborators and learn about events and new funding opportunities.
                    </li>
                    <li>Engages the existing communities of digital social innovators in the network. Carries out
                        research on how to grow DSI in Europe.
                    </li>
                    <li>Uses insights about the European DSI network to develop a set of recommendations about how
                        policymakers and funders can best support, engage with and make the most of DSI and ensure it
                        can continue to grow in the future.
                    </li>
                    <li class="li-bottom">Develops a set of tools that supports people and organisations interested in
                        develop digital solutions that address social challenges.
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