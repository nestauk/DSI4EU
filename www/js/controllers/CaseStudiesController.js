angular
    .module(angularAppName)
    .filter('startFrom', function () {
        return function (input, start) {
            if (input) {
                start = +start; //parse to int
                return input.slice(start);
            }
            return [];
        }
    })
    .controller('CaseStudiesController', function ($scope, $http, $timeout, $attrs) {
        // Get stories
        (function () {
            var jsonUrl = $attrs.jsonurl;
            $scope.caseStudies = [];
            $http.get(jsonUrl)
                .then(function (response) {
                    $scope.caseStudies = response.data;
                    console.log($scope.caseStudies);

                    $scope.currentPage = 1; //current page
                    $scope.maxSize = 10; //pagination max size
                    $scope.entryLimit = 12; //max rows for data table

                    /* init pagination with $scope.list */
                    $scope.noOfPages = Math.ceil($scope.caseStudies.length / $scope.entryLimit);
                })
        }());

        $scope.searchCriteria = {};
        $scope.criteriaMatch = function () {
            return function (item) {
                var hide = false;
                if (typeof $scope.searchCriteria.catg !== 'undefined') {
                    if (item.categoryID != $scope.searchCriteria.catg)
                        hide = true;
                }

                return !hide;
            };
        };

        $scope.recalculatePagination = function () {
            $scope.noOfPages = Math.ceil($scope.filtered.length / $scope.entryLimit);
        };
        $scope.$watch('searchCriteria.catg', function (newValue, oldValue) {
            $scope.currentPage = 1;
        });
        $scope.$watch('searchCriteria.published', function (newValue, oldValue) {
            $scope.currentPage = 1;
        });
    });