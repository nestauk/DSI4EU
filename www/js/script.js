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
        $(".profile-popover.bg-blur").hide();
    });
    // Prevent events from getting pass .popup
    $("#userMenu").click(function (e) {
        e.stopPropagation();
    });
}());

var DSI_Helpers = {
    UploadImageHandler: function (Upload) {
        this.uploader = {};
        this.upload = function (file, errFiles) {
            var $this = this;
            $this.errorMsg = {};
            $this.uploader.f = file;
            $this.uploader.errFile = errFiles && errFiles[0];
            if (file) {
                file.upload = Upload.upload({
                    url: SITE_RELATIVE_PATH + '/temp-gallery.json',
                    data: {file: file}
                });

                file.upload.then(function (response) {
                    console.log(response.data);
                    file.result = response.data;
                    if (response.data.code == 'ok')
                        $this.image = response.data.imgPath;
                    else if (response.data.code == 'error')
                        $this.errorMsg = response.data.errors;

                    $this.uploader = {};
                }, function (response) {
                    if (response.status > 0)
                        $this.errorMsg = response.status + ': ' + response.data;
                }, function (evt) {
                    file.progress = Math.min(100, parseInt(100.0 *
                        evt.loaded / evt.total));
                });
            }
        };
    }
};