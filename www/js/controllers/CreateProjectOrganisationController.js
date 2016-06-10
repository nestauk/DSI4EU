angular
    .module(angularAppName)
    .controller('CreateProjectOrganisationController', function ($scope, $http, $timeout) {
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
        };

        $scope.organisation = {};
        $scope.createOrganisation = function () {
            $scope.organisation.loading = true;
            $scope.organisation.errors = {};
            var data = {
                newOrganisation: true,
                name: $scope.organisation.name
            };
            $http.post(SITE_RELATIVE_PATH + '/createOrganisation.json', data)
                .then(function (response) {
                    $timeout(function () {
                        $scope.organisation.loading = false;
                        $scope.organisation.saved = false;

                        if (response.data.result == 'ok') {
                            window.location.href = response.data.url;
                        } else if (response.data.result == 'error') {
                            $scope.organisation.errors = response.data.errors;
                        }
                    }, 1000);
                });
        }
    });