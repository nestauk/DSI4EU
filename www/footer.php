</div>
<div class="w-section footer">
    <div class="container-wide">
        <div class="w-row footer-columns">
            <div class="w-col w-col-4">
                <img src="<?php echo SITE_RELATIVE_PATH ?>/images/logo-white.svg" class="footer-brand">
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3">People</h3>
                <ul class="w-list-unstyled">
                    <li class="footer-link">
                        <a href="#" class="footer-link">Who uses DSI4EU?</a>
                    </li>
                    <li class="footer-link">
                        <a href="#" class="footer-link">Join DSI4EU</a>
                    </li>
                    <li class="footer-link">
                        <a href="#" class="footer-link">Help centre</a>
                    </li>
                    <li class="footer-link">
                        <a href="#" class="footer-link">Report abuse</a>
                    </li>
                    <li class="footer-link">
                        <a href="#" class="footer-link">Terms of service</a>
                    </li>
                    <li class="footer-link">
                        <a href="#" class="footer-link">Privacy policy</a>
                    </li>
                </ul>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3">Projects</h3>
                <ul class="w-list-unstyled">
                    <li class="footer-link">
                        <a href="#" class="footer-link">Add a project to DSI4EU</a>
                    </li>
                    <li class="footer-link">
                        <a href="#" class="footer-link">Report a&nbsp;project</a>
                    </li>
                    <li class="footer-link">
                        <a href="#" class="footer-link">View project listing</a>
                    </li>
                </ul>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3">Organisations</h3>
                <ul class="w-list-unstyled">
                    <li class="footer-link">
                        <a href="#" class="footer-link">Add an organisation</a>
                    </li>
                    <li class="footer-link">
                        <a href="#" class="footer-link">Report an organisation</a>
                    </li>
                    <li class="footer-link">
                        <a href="#" class="footer-link">View partner organisations</a>
                    </li>
                </ul>
            </div>
            <div class="w-col w-col-2">
                <h3 class="footer-h3">Development</h3>
                <a href="<?php echo \DSI\Service\URL::feedback() ?>" class="footer-link">Feedback</a>
            </div>
        </div>
    </div>
</div>

<div class="under-development" data-ix="under-dev">
    <h1 class="page-h1 notice">Under development</h1>
    <div class="notification-p">This page is under development and may not behave as expected. Please visit the <a
            class="notification-link" href="<?php echo \DSI\Service\URL::feedback() ?>">feedback</a> page to view pages
        &amp; tasks currently being tested.
    </div>
</div>

<script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH ?>/js/dsi4eu.js?v=<?php echo Sysctl::$version ?>"></script>
<!--[if lte IE 9]>
<script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
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

<script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH ?>/js/script.js?v=<?php echo \DSI\Service\Sysctl::$version ?>"></script>

</body>
</html>