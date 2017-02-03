angular
    .module(angularAppName)
    .controller('SearchController', function ($scope, $http, $attrs) {


        $scope.search = {};
        $scope.search.entry = '';
        $scope.search.caseStudies = [];
        $scope.search.blogPosts = [];
        $scope.search.projects = [];
        $scope.search.organisations = [];

        $scope.$watch('search.entry', function () {
            $scope.search.caseStudies = [];
            $scope.search.blogPosts = [];
            $scope.search.projects = [];
            $scope.search.organisations = [];
            if ($scope.search.entry.length >= 3) {
                $http.post(SITE_RELATIVE_PATH + $attrs.langpath + '/search.json', {
                    term: $scope.search.entry
                }).then(function (result) {
                    $scope.search.caseStudies = result.data.caseStudies;
                    $scope.search.blogPosts = result.data.blogPosts;
                    $scope.search.projects = result.data.projects;
                    $scope.search.organisations = result.data.organisations;
                });
            }
        });
    });