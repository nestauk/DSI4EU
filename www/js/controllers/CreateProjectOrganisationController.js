var app = angular.module('DSIApp');

app.controller('CreateProjectOrganisationController', function ($scope, $http, $timeout) {
    $scope.project = {};

    $scope.createProject = function () {
        $scope.project.loading = true;
        $scope.project.errors = {};
        var data = {
            newProject: true,
            name: $scope.project.name
        };
        $http.post(SITE_RELATIVE_PATH + '/createProject.json', data)
            .then(function (response) {
                $timeout(function () {
                    $scope.project.loading = false;
                    $scope.project.saved = false;

                    if (response.data.result == 'ok') {
                        window.location.href = response.data.url;
                    } else if (response.data.result == 'error') {
                        $scope.project.errors = response.data.errors;
                    }
                }, 1000);
            });
    }
});