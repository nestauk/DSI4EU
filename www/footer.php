<?php
use \DSI\Service\URL;

/** @var $loggedInUser \DSI\Entity\User */
if (!isset($loggedInUser))
    $loggedInUser = null;

?>
</div>
<div class="footer">
    <div class="container-wide">
        <div class="newsletter-block" data-ix="fadeinuponload-3">
            <div class="w-row">
                <div class="w-col w-col-6">
                    <h2 class="newsletter-signup-h2">Stay up to date with the DSI4EU newsletter</h2>
                </div>
                <div class="w-col w-col-6">
                    <div class="w-form">
                        <form class="w-clearfix" id="email-form" name="email-form">
                            <input class="newsletter-signup-input w-input" data-name="Email" id="email" maxlength="256"
                                   name="email" placeholder="Enter your email address" required="required" type="email">
                            <input class="newsletter-signup-submit w-button" data-wait="Please wait..." type="submit"
                                   value="Submit">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-columns w-row">
            <div class="w-col w-col-4">
                <img class="footer-brand" src="<?php echo SITE_RELATIVE_PATH ?>/images/all white.svg">
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3">People</h3>
                <ul class="w-list-unstyled">
                    <?php /*
                    <li class="footer-link">
                        <a class="footer-link" href="#">Who uses DSI4EU?</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Help centre</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Report abuse</a>
                    </li>
                    */ ?>
                    <?php if ($loggedInUser) { ?>
                        <li class="footer-link">
                            <a class="footer-link" href="<?php echo URL::dashboard() ?>">Your dashboard</a>
                        </li>
                    <?php } else { ?>
                        <li class="footer-link">
                            <a class="footer-link" href="#" data-ix="showsignup">Join DSI4EU</a>
                        </li>
                    <?php } ?>
                    <li class="footer-link">
                        <a class="footer-link" href="<?php echo URL::termsOfUser() ?>">Terms of service</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="<?php echo URL::privacyPolicy() ?>">Privacy policy</a>
                    </li>
                </ul>
            </div>

            <div class="w-col w-col-2">
                <h3 class="footer-h3">Projects</h3>
                <ul class="w-list-unstyled">
                    <?php if ($loggedInUser) { ?>
                        <li class="footer-link">
                            <a class="footer-link" href="#" data-ix="create-project-modal">Add a project</a>
                        </li>
                    <?php } ?>
                    <?php /*
                    <li class="footer-link">
                        <a class="footer-link" href="#">Report a&nbsp;project</a>
                    </li>
                    */ ?>
                    <li class="footer-link">
                        <a class="footer-link" href="<?php echo URL::projects() ?>">View projects</a>
                    </li>
                </ul>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3">Organisations</h3>
                <ul class="w-list-unstyled">
                    <?php if ($loggedInUser) { ?>
                        <li class="footer-link">
                            <a class="footer-link" href="#" data-ix="create-organisation-modal">Add an organisation</a>
                        </li>
                    <?php } ?>
                    <?php /*
                    <li class="footer-link">
                        <a class="footer-link" href="#">Report an organisation</a>
                    </li>
                    */ ?>
                    <li class="footer-link">
                        <a class="footer-link" href="<?php echo URL::organisations() ?>">View organisations</a>
                    </li>
                </ul>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3">Development</h3>
                <a class="footer-link" href="<?php echo URL::feedback() ?>">Feedback</a>
            </div>
        </div>
        <div class="footer-partner-row w-row">
            <div class="w-clearfix w-col w-col-6">
                <div class="footer-small-print">Nesta is a registered charity in England and Wales 1144091 and Scotland
                    SC042833. Our main address is 1 Plough Place, London, EC4A 1DE
                </div>
                <img class="footer-cc-img" src="<?php echo SITE_RELATIVE_PATH ?>/images/88x31.png">
                <div class="footer-small-print">All our work is licensed under a&nbsp;
                    <a target="_blank" class="footer-link-small"
                       href="https://creativecommons.org/licenses/by-nc-sa/4.0/">Creative
                        Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>, unless it says
                    otherwise.
                </div>
            </div>
            <div class="w-clearfix w-col w-col-6">
                <a class="footer-partner-link w-inline-block" href="#">
                    <img class="footer-logo" src="<?php echo SITE_RELATIVE_PATH ?>/images/logo_SUPSI.png" width="125">
                </a>
                <a class="footer-partner-link w-inline-block" href="#">
                    <img class="footer-logo"
                         src="<?php echo SITE_RELATIVE_PATH ?>/images/waag-f1d052f43133268eaf2e13090a0b4bf1.png"
                         width="125">
                </a>
                <a class="footer-partner-link w-inline-block" href="#">
                    <img class="footer-logo"
                         src="<?php echo SITE_RELATIVE_PATH ?>/images/nesta-6a9b5fe999e8323b379ccc0d8e70290f.png"
                         width="125">
                </a>
            </div>
        </div>
    </div>
</div>


<?php /*
<div class="footer w-section">
    <div class="container-wide">
        <div class="footer-columns w-row">
            <div class="w-col w-col-4">
                <img class="footer-brand" src="<?php echo SITE_RELATIVE_PATH ?>/images/all white.svg">
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3">People</h3>
                <ul class="w-list-unstyled">
                    <li class="footer-link">
                        <a class="footer-link" href="home-dashboard.html">Your dashboard</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Who uses DSI4EU?</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Join DSI4EU</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Help centre</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Report abuse</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Terms of service</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Privacy policy</a>
                    </li>
                </ul>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3">Projects</h3>
                <ul class="w-list-unstyled">
                    <li class="footer-link">
                        <a class="footer-link" href="#">Add a project to DSI4EU</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Report a&nbsp;project</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">View project listing</a>
                    </li>
                </ul>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3">Organisations</h3>
                <ul class="w-list-unstyled">
                    <li class="footer-link">
                        <a class="footer-link" href="#">Add an organisation</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">Report an organisation</a>
                    </li>
                    <li class="footer-link">
                        <a class="footer-link" href="#">View partner organisations</a>
                    </li>
                </ul>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3">Development</h3>
                <a class="footer-link" href="feedback.html">Feedback</a>
            </div>
        </div>
        <div class="footer-partner-row w-row">
            <div class="w-col w-col-6"></div>
            <div class="w-clearfix w-col w-col-6">
                <a class="footer-partner-link w-inline-block" href="#">
                    <img src="<?php echo SITE_RELATIVE_PATH ?>/images/logo_SUPSI.png" width="125">
                </a>
                <a class="footer-partner-link w-inline-block" href="#">
                    <img src="<?php echo SITE_RELATIVE_PATH ?>/images/waag-f1d052f43133268eaf2e13090a0b4bf1.png"
                         width="125">
                </a>
                <a class="footer-partner-link w-inline-block" href="#">
                    <img src="<?php echo SITE_RELATIVE_PATH ?>/images/nesta-6a9b5fe999e8323b379ccc0d8e70290f.png"
                         width="125">
                </a>
            </div>
        </div>
    </div>
</div>
 */ ?>

<div class="cookies" id="cookies">
    <div class="container-wide">
        <div class="w-row">
            <div class="w-col w-col-6">
                <h3 class="cookie-h3">We use cookies to help us improve this site and your experience. Continue to use
                    the site if you’re happy with this or click to find out more.</h3>
            </div>
            <div class="w-clearfix w-col w-col-6">
                <a class="cookie-button w-button" href="#">Find out more</a>
                <a class="cookie-button w-button" href="#" onclick="$('#cookies').hide()">Continue</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/dsi4eu.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>
<!--[if lte IE 9]>
<script src="<?php echo SITE_RELATIVE_PATH ?>/js/lib/placeholders/placeholders.min.js"></script>
<![endif]-->
<script>
    $(function () {
        // quick search regex
        var qsRegex;
        // init Isotope
        var $grid = $('.grid').isotope({
            itemSelector: '.element-item',
            layoutMode: 'fitRows',
            filter: function () {
                return qsRegex ? $(this).text().match(qsRegex) : true;
            }
        });
        // use value of search field to filter
        var $quicksearch = $('.quicksearch').keyup(debounce(function () {
            qsRegex = new RegExp($quicksearch.val(), 'gi');
            $grid.isotope();
        }, 200));
    });
    // debounce so filtering doesn't happen every millisecond
    function debounce(fn, threshold) {
        var timeout;
        return function debounced() {
            if (timeout) {
                clearTimeout(timeout);
            }
            function delayed() {
                fn();
                timeout = null;
            }

            timeout = setTimeout(delayed, threshold || 100);
        }
    }
</script>

<script>
    // Create Project / Organisation Dropdown
    (function () {
        var nav = $('nav.w-dropdown-list.create-drop-down');
        $('a', nav).on('click', function () {
            nav.removeClass('w--open');
        });
    }());
</script>

<script type="text/javascript"
        src="<?php echo SITE_RELATIVE_PATH ?>/js/script.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php include(__DIR__ . '/partialViews/googleAnalytics.html'); ?>

</body>
</html>