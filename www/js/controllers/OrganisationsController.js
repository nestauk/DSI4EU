var app = angular.module('DSIApp');

app.controller('OrganisationsController', function ($scope, $http, $interval) {
    $scope.letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
    $scope.startLetter = '';
    $scope.organisations = [];
    /*
    var preloadOrganisations = $interval(function () {
        $scope.organisations.push({});
    }, 100);
    */

    $http.get(SITE_RELATIVE_PATH + '/organisations.json')
        .then(function (result) {
            // $interval.cancel(preloadOrganisations);
            $scope.startLetter = 'A';
            $scope.loaded = true;
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