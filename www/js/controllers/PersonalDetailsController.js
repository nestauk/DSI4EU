var app = angular.module('DSIApp');

app.controller('PersonalDetailsController', function ($scope, $http, $timeout) {
    $http.get(SITE_RELATIVE_PATH + '/my-profile.json')
        .then(function (result) {
            $scope.user = result.data || {};
        });

    $scope.savePersonalDetails = function () {
        $scope.loading = true;
        $scope.errors = {};
        var data = {
            saveDetails: true,
            details: $scope.user
        };
        $http.post(SITE_RELATIVE_PATH + '/my-profile.json', data)
            .then(function (result) {
                $timeout(function () {
                    $scope.loading = false;
                    $scope.saved = false;

                    if (result.data.response == 'ok')
                        $scope.saved = true;
                    else if (result.data.response == 'error')
                        $scope.errors = result.data.errors;
                }, 1000);
            });
    }
});