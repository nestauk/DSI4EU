angular
    .module(angularAppName)
    .controller('UpdatePasswordController', function ($scope, $http, Upload, $timeout) {
        $scope.savePassword = function () {
            $scope.loading = true;
            $scope.errors = {};

            var data = {
                changePassword: true,
                password: $scope.password,
                retypePassword: $scope.retypePassword
            };
            $http.post(SITE_RELATIVE_PATH + '/my-profile.json', data)
                .then(function (response) {
                    $timeout(function () {
                        $scope.loading = false;
                        $scope.saved = false;
                        if (response.data.result == 'ok') {
                            $scope.saved = true;
                            $scope.password = '';
                            $scope.retypePassword = '';
                        } else if (response.data.result == 'error') {
                            $scope.errors = response.data.errors;
                        }
                    }, 1000);
                });
        }
    });