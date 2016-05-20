var app = angular.module('DSIApp');

app.controller('ProjectsController', function ($scope, $http) {
    $scope.letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
    $scope.startLetter = 'A';

    $http.get(SITE_RELATIVE_PATH + '/projects.json')
        .then(function (result) {
            $scope.projects = result.data;
        });

    $scope.setStartLetter = function (letter) {
        $scope.startLetter = letter;
    };

    $scope.startsWithLetter = function (item) {
        var letterMatch = new RegExp($scope.startLetter, 'i');
        return !!letterMatch.test(item.name.substring(0, 1));
    }
});