angular
    .module(angularAppName)
    .controller('ProjectsController', function ($scope, $http, $attrs) {
        var projectsJsonUrl = $attrs.projectsjsonurl;

        $scope.letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
        $scope.startLetter = 'A';

        $http.get(projectsJsonUrl)
            .then(function (result) {
                console.log(result.data);
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