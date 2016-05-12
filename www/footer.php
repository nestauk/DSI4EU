</div>
<div class="w-section footer">
    <div class="w-container">
        <div class="w-row footer-columns">
            <div class="w-col w-col-3"><img
                    src="<?php echo SITE_RELATIVE_PATH ?>/images/dsi-8c1449cf94fe315a853fd9a5d99eaf45.png">
            </div>
            <div class="w-col w-col-3"></div>
            <div class="w-col w-col-3"></div>
            <div class="w-col w-col-3"></div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH ?>/js/webflow.js"></script>
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

<script type="text/javascript" src="<?php echo SITE_RELATIVE_PATH ?>/js/script.js"></script>

</body>
</html>