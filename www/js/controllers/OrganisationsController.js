var app = angular.module('DSIApp');

app.controller('OrganisationsController', function ($scope, $http) {
    $scope.letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
    $scope.startLetter = 'A';

    $http.get(SITE_RELATIVE_PATH + '/organisations.json')
        .then(function (result) {
            $scope.organisations = result.data;
        });

    $scope.setStartLetter = function (letter) {
        $scope.startLetter = letter;
    };

    $scope.startsWithLetter = function (item) {
        var letterMatch = new RegExp($scope.startLetter, 'i');
        return !!letterMatch.test(item.name.substring(0, 1));
    }
});