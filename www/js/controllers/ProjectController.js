var app = angular.module('DSIApp');

app.controller('ProjectController', function ($scope, $http, $attrs) {
    $http.get(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json')
        .then(function (response) {
            $scope.project = response.data || {};
        });

    $scope.updateBasic = function () {
        var data = {
            updateBasic: true,
            url: $scope.project.url,
            name: $scope.project.name
        };
        $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', data)
            .then(function (response) {
                console.log(response);
                if(response.data.result == 'error')
                    alert()
            });
    };
});