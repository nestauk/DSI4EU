angular
    .module(angularAppName)
    .controller('ProjectsController', function ($scope, $http, $attrs) {
        var projectsJsonUrl = $attrs.projectsjsonurl;

        $scope.letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
        $scope.startLetter = 'A';
        $scope.country = '0';

        $http.get(projectsJsonUrl)
            .then(function (result) {
                $scope.projects = result.data;
            });
        $http.get(SITE_RELATIVE_PATH + '/countries.json')
            .then(function (result) {
                $scope.countries = result.data;
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

        $scope.dsiFocus4 = false;
        $scope.dsiFocus8 = false;
        $scope.dsiFocus9 = false;
        $scope.dsiFocus35 = false;

        $scope.projectHasDsiFocusTag = function () {
            return function (item) {
                if ($scope.dsiFocus4 == false && $scope.dsiFocus8 == false && $scope.dsiFocus9 == false && $scope.dsiFocus35 == false)
                    return true;

                if ($scope.dsiFocus4 == true && $.inArray(4, item.dsiFocusTags) === 0)
                    return true;
                if ($scope.dsiFocus8 == true && $.inArray(8, item.dsiFocusTags) === 0)
                    return true;
                if ($scope.dsiFocus9 == true && $.inArray(9, item.dsiFocusTags) === 0)
                    return true;
                if ($scope.dsiFocus35 == true && $.inArray(35, item.dsiFocusTags) === 0)
                    return true;

                return false;
            }
        };

        $scope.projectInCountry = function () {
            return function (item) {
                if ($scope.country == '0')
                    return true;

                return $scope.country == item.countryID;
            }
        }
    });