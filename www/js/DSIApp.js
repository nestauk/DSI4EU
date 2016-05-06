var app = angular.module('DSIApp', ['ngFileUpload']);

app.controller('LoginController', function ($scope, $http, $timeout) {
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

        $http.post(SITE_RELATIVE_PATH + '/login.json', data).then(
            function (response) {
                console.log(data);
                $scope.loading = false;
                if (response.data.response == 'error') {
                    $scope.errors = response.data.errors;
                } else if (response.data.response == 'ok') {
                    $scope.loggedin = true;
                    $timeout(function () {
                        window.location.href = SITE_RELATIVE_PATH + "/";
                    }, 1000);
                }
            },
            function (response) {
                $scope.loading = false;
                alert(response);
            }
        )
    }
});

app.controller('RegisterController', function ($scope, $http, $timeout) {
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
                console.log(data);
                $scope.loading = false;
                if (response.data.response == 'error') {
                    $scope.errors = response.data.errors;
                } else if (response.data.response == 'ok') {
                    $scope.registered = true;
                    $timeout(function () {
                        window.location.href = SITE_RELATIVE_PATH + "/";
                    }, 1000);
                }
            },
            function (response) {
                $scope.loading = false;
                alert(response);
            }
        )
    }
});