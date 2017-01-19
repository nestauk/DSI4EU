<?php
/** @var $urlHandler \DSI\Service\URL */

require __DIR__ . '/header.php'
?>

    <div class="content-block">
        <div class="w-row">
            <div class="w-col w-col-8 w-col-stack">
                <h1 class="content-h1"><?php _ehtml('The DSI4EU project')?></h1>
                <p class="intro">
                    <?php _ehtml('Across Europe there is a growing movement of people developing inspiring digital solutions to social challenges. We call this digital social innovation (DSI).')?>
                </p>
                <p>
                    <?php _ehtml('These digital solutions have developed thanks to big advances in technology, [...]')?>
                </p>
                <p>
                    <?php _ehtml('We believe that DSI has the potential to dramatically improve the way our public services, [...]')?>
                </p>
                <p>
                    <?php _ehtml('At the heart of the project is the digitalsocial.eu platform, which has four main functions.')?>
                </p>
                <p class="bold-p">
                    <strong><?php _ehtml('Users can:')?></strong>
                </p>
                <ul>
                    <li><?php _ehtml('showcase their work and tag their organisations as part of networks like research alliances or membership bodies.')?>
                    </li>
                    <li><?php _ehtml('explore the DSI community and search organisations and projects to understand [...]')?>
                    </li>
                    <li><?php _ehtml('identify funding and support opportunities, along with DSI-related events, across Europe. They can also submit and promote their own event to the DSI community.')?>
                    </li>
                    <li class="li-bottom"><?php _ehtml('learn about what is happening across Europe through case studies and blogs focusing on DSI pioneers and their projects, and current trends.')?>
                    </li>
                </ul>
                <p>
                    <?php _ehtml('All of the data on the digitalsocial.eu platform is open and accessible, and all the source code behind the platform is available open-source on GitHub. The digitalsocial.eu platform is free to use. ')?>
                </p>
                <p class="bold-p">
                    <strong>
                        <?php _ehtml('Alongside the digitalsocial.eu platform, the DSI4EU project is:')?>
                    </strong>
                </p>
                <ul>
                    <li><?php _ehtml('researching what the DSI landscape in  Europe looks like, understanding routes to growth and scale, and exploring how DSI projects and organisations understand their impact;')?>
                    </li>
                    <li><?php _ehtml('holding a number of events across Europe to bring together the DSI community and influence policy;')?>
                    </li>
                    <li><?php _ehtml('developing a set of policy recommendations to support DSI; and')?>
                    </li>
                    <li class="li-bottom"><?php _ehtml('creating a set of tools to support digital social innovators, especially those involved in the open hardware movement.')?>
                    </li>
                </ul>
                <p><?php _ehtml('DSI4EU is being led by Nesta (UK) and delivered in partnership with the [...]')?></p>
                <a class="log-in-link long next-page read-more w-clearfix w-inline-block" data-ix="log-in-arrow"
                   href="<?php echo $urlHandler->partners() ?>">
                    <div class="login-li long menu-li readmore-li"><?php _ehtml('Find out about our partners')?></div>
                    <img class="login-arrow" src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-arrow-thin-right.png">
                </a>
            </div>
            <?php require __DIR__ . '/partialViews/about-dsi.php' ?>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>