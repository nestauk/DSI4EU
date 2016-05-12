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
}());