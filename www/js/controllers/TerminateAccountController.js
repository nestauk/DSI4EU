angular
    .module(angularAppName)
    .controller('TerminateAccountController', function ($http, $attrs) {
        var url = window.location.href;
        var returnUrl = $attrs.returnurl;

        swal({
                title: "",
                text: translate.get("Are you sure you want to terminate your account?"),
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: translate.get("Yes"),
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function (confirm) {
                if (confirm) {
                    $http.post(url, {confirm: true})
                        .then(function (response) {
                            if (response.data.code == 'ok') {
                                swal({
                                        title: translate.get("Success!"),
                                        text: translate.get("Your account has been terminated."),
                                        type: 'success'
                                    }, function () {
                                        window.location.href = returnUrl;
                                    }
                                );
                            } else {
                                swal("Info", Object.values(response.data.errors).join(' '), "info");
                            }
                        });
                } else {
                    window.location.href = returnUrl;
                }
            });
    });