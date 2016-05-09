var app = angular.module('DSIApp');

app.controller('ProjectController', function ($scope, $http, $attrs) {
    $scope.datePattern = '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])';

    $http.get(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json')
        .then(function (response) {
            $scope.project = response.data || {};
        });

    $scope.updateBasic = function () {
        // console.log($scope.project);

        var data = {
            updateBasic: true,
            url: $scope.project.url,
            name: $scope.project.name,
            status: $scope.project.status,
            description: $scope.project.description,
            startDate: $scope.project.startDate,
            endDate: $scope.project.endDate
        };

        console.log(data);
        $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', data)
            .then(function (response) {
                console.log(response);
                if (response.data.result == 'error')
                    alert(response.data);
            });
    };

    $scope.getDateFrom = function (date) {
        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];

        var jsDate = new Date(date);
        return monthNames[jsDate.getMonth()] + ' ' + jsDate.getFullYear();
    }
});