(function(){
    function textAreaAdjust(o) {
        o.style.height = "1px";
        o.style.height = (25+o.scrollHeight)+"px";
    }
    
    $('.readjustTextarea').each(function () {
        textAreaAdjust($(this).get(0));
        $(this).on('keydown', function () {
            textAreaAdjust($(this).get(0));
        });
    });

    // close user menu popup when clicking outside
    $("body").click(function () {
        $(".profile-popover.bg-blur").fadeOut(300);
    });
    // Prevent events from getting pass .popup
    $("#userMenu").click(function (e) {
        e.stopPropagation();
    });
}());