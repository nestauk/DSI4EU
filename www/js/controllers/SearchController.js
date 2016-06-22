angular
    .module(angularAppName)
    .controller('SearchController', function ($scope, $http, $timeout) {
        $scope.search = {};
        $scope.search.entry = '';
        $scope.search.organisations = [];
        $scope.search.projects = [];
        $scope.search.stories = [];

        $scope.$watch('search.entry', function () {
            $scope.search.organisations = [];
            $scope.search.projects = [];
            $scope.search.stories = [];
            if ($scope.search.entry.length >= 3) {
                $http.post(SITE_RELATIVE_PATH + '/search.json', {
                    term: $scope.search.entry
                }).then(function (result) {
                    $scope.search.organisations = result.data.organisations;
                    $scope.search.projects = result.data.projects;
                    $scope.search.stories = result.data.stories;
                });
            }
        });
    });