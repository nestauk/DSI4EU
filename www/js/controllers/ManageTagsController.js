angular
    .module(angularAppName)
    .controller('ManageTagsController', function ($scope, $http, $interval, $attrs) {
        var manageTagsJsonUrl = $attrs.managetagsjsonurl;

        $scope.letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');

        $scope.filter = {
            tagID: $attrs.searchtag,
            netwTagID: $attrs.searchnetwtag
        };
        $scope.searchName = $attrs.searchname;
        $scope.startLetter = '';
        $scope.tags = [];

        $http.get(manageTagsJsonUrl)
            .then(function (result) {
                $scope.loaded = true;
                $scope.data = result.data;
                console.log($scope.data);
            });

        $scope.setStartLetter = function (letter) {
            $scope.startLetter = letter;
        };

        $scope.startsWithLetter = function (item) {
            if ($scope.startLetter == '')
                return true;

            var letterMatch = new RegExp($scope.startLetter, 'i');
            return !!letterMatch.test(item.name.substring(0, 1));
        };

        $scope.organisationInCountry = function () {
            return function (item) {
                if ($scope.filter.countryID == '0')
                    return true;

                return $scope.filter.countryID == item.countryID;
            }
        };

        $scope.organisationHasTag = function () {
            return function (item) {
                if ($scope.filter.tagID == '0')
                    return true;

                return $.inArray(parseInt($scope.filter.tagID), item.tags) !== -1;
            }
        };

        $scope.projectHasNetwTag = function () {
            return function (item) {
                if ($scope.filter.netwTagID == '0')
                    return true;

                return $.inArray(parseInt($scope.filter.netwTagID), item.netwTags) !== -1;
            }
        }
    });