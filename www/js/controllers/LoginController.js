angular
    .module(angularAppName)
    .controller('LoginController', function ($scope, $http, $timeout, $attrs) {
        var loginJsonUrl = $attrs.loginjsonurl;
        var afterLoginUrl = $attrs.afterloginurl;

        $scope.forgotPassword = {};
        $scope.email = {value: ''};
        $scope.password = {value: ''};
        $scope.onSubmit = function () {
            $scope.loading = true;
            $scope.errors = {};

            var data = {
                email: $scope.email.value,
                password: $scope.password.value,
                login: true
            };

            $http.post(loginJsonUrl, data).then(
                function (response) {
                    $scope.loading = false;
                    if (response.data.response == 'error') {
                        $scope.errors = response.data.errors;
                    } else if (response.data.response == 'ok') {
                        $scope.loggedin = true;
                        window.location.href = afterLoginUrl;
                    }
                },
                function (response) {
                    $scope.loading = false;
                    alert(response);
                }
            )
        };

        $scope.forgotPasswordSubmit = function () {
            $scope.forgotPassword.loading = true;
            $scope.forgotPassword.errors = {};

            if (!$scope.forgotPassword.codeSent) {
                sendSecurityCode_useCase();
                return;
            }
            if (!$scope.forgotPassword.codeVerified) {
                verifySecurityCode_useCase();
                return;
            }
            completePasswordRecovery_useCase();
        };

        function sendSecurityCode_useCase() {
            $http.post(SITE_RELATIVE_PATH + '/forgotPassword.json', {
                sendCode: true,
                email: $scope.email.value
            }).then(
                function (response) {
                    $scope.forgotPassword.loading = false;
                    if (response.data.result == 'error') {
                        $scope.forgotPassword.errors = response.data.errors;
                    } else if (response.data.result == 'ok') {
                        $scope.forgotPassword.codeSent = true;
                    }
                }
            );
        }

        function verifySecurityCode_useCase() {
            $http.post(SITE_RELATIVE_PATH + '/forgotPassword.json', {
                verifyCode: true,
                email: $scope.email.value,
                code: $scope.forgotPassword.code
            }).then(
                function (response) {
                    $scope.forgotPassword.loading = false;
                    if (response.data.result == 'error') {
                        $scope.forgotPassword.errors = response.data.errors;
                    } else if (response.data.result == 'ok') {
                        $scope.forgotPassword.codeVerified = true;
                    }
                }
            );
        }

        function completePasswordRecovery_useCase() {
            $http.post(SITE_RELATIVE_PATH + '/forgotPassword.json', {
                completeForgotPassword: true,
                email: $scope.email.value,
                code: $scope.forgotPassword.code,
                password: $scope.forgotPassword.password,
                retypePassword: $scope.forgotPassword.retypePassword
            }).then(
                function (response) {
                    $scope.forgotPassword.loading = false;
                    if (response.data.result == 'error') {
                        $scope.forgotPassword.errors = response.data.errors;
                    } else if (response.data.result == 'ok') {
                        $scope.forgotPassword.complete = true;
                    }
                }
            );
        }
    })
    .controller('RegisterController', function ($scope, $http) {
        $scope.email = {value: ''};
        $scope.password = {value: ''};
        $scope.onSubmit = function () {
            $scope.loading = true;
            $scope.errors = {};

            var data = {
                email: $scope.email.value,
                password: $scope.password.value,
                register: true
            };

            $http.post(SITE_RELATIVE_PATH + '/register.json', data).then(
                function (response) {
                    $scope.loading = false;
                    if (response.data.response == 'error') {
                        $scope.errors = response.data.errors;
                    } else if (response.data.response == 'ok') {
                        $scope.registered = true;
                        window.location.href = response.data.url;
                    }
                },
                function (response) {
                    $scope.loading = false;
                    alert(response);
                }
            )
        }
    });