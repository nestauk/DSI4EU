window.DSI_Helpers = {
    UploadImageHandler: function (Upload) {
        this.uploader = {};
        this.upload = function (file, errFiles) {
            const $this = this;
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
                    if (response.data.code === 'ok')
                        $this.image = response.data.imgPath;
                    else if (response.data.code === 'error')
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
    },

    TinyMCEImageUpload: function (uploadData) {
        uploadData.element
            .unbind('change')
            .change(function () {
                var formData = new FormData();
                formData.append('upload', true);
                formData.append('file', $(this)[0].files[0]);

                $.ajax({
                    url: uploadData.uploadUrl,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,  // tell jQuery not to set contentType
                    success: function (data) {
                        uploadData.callback(data.location, {
                            alt: data.name,
                            width: data.width,
                            height: data.height
                        });
                    }
                });
                $(this)[0].value = '';
            })
            .click();
    }
};

(function readjustTextarea() {
    function textAreaAdjust(o) {
        o.style.height = "1px";
        o.style.height = (25 + o.scrollHeight) + "px";
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

(function cookiePolicy() {
    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        const expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) === 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    if (!getCookie('cookies-agree')) {
        const container = $('#cookies');
        container.show();

        $('.js-cookie-accept', container).click(function (ev) {
            ev.preventDefault();
            setCookie('cookies-agree', true, 720);
            container.hide('slow');
            return false;
        })
    }

    if (!getCookie('twitter-dismiss')) {
        const container = $('.twitter-block');
        container.show();
        $('body').addClass('padded-footer');

        $('.js-twitter-dismiss', container).click(function (ev) {
            ev.preventDefault();
            setCookie('twitter-dismiss', true, 720);
            container.hide('slow');
            return false;
        })
    }
}());