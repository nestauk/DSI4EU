angular
    .module(angularAppName)
    .controller('OpenResourcesController', function ($scope, $http, $attrs) {

        var $grid;
        if ($(window).width() > 767) {
            $grid = $('.grid').masonry({
                itemSelector: '.grid-item',
                horizontalOrder: true
            });
        }

        $scope.author_id = "0";
        $scope.clusters = [];
        $scope.types = [];

        $scope.$watch('resources', function () {
            $scope.setFilteredResources();
        });
        $scope.$watch('author_id', function () {
            $scope.setFilteredResources();
        });
        $scope.$watch('clusters', function () {
            $scope.setFilteredResources();
        }, true);
        $scope.$watch('types', function () {
            $scope.setFilteredResources();
        }, true);

        $scope.setFilteredResources = function () {
            if (!$scope.resources) {
                $scope.filteredResources = [];
            } else {
                var clusters = [];
                if ($scope.clusters)
                    $scope.clusters.map(function (value, index) {
                        if (value === true)
                            clusters.push(index);
                    });

                var types = [];
                if ($scope.types)
                    $scope.types.map(function (value, index) {
                        if (value === true)
                            types.push(index);
                    });

                $scope.filteredResources = $scope.resources
                    .filter(function (resource) {
                        return parseInt($scope.author_id) === 0 || parseInt(resource.author_id) === parseInt($scope.author_id);
                    })
                    .filter(function (resource) {
                        if (clusters.length === 0)
                            return true;

                        for (var i = 0; i < clusters.length; i++) {
                            if (resource.clusters[clusters[i]] !== 1)
                                return false;
                        }

                        return true;
                    })
                    .filter(function (resource) {
                        if (types.length === 0)
                            return true;

                        for (var i = 0; i < types.length; i++) {
                            if (resource.types[types[i]] !== 1)
                                return false;
                        }

                        return true;
                    });
            }

            $scope.$evalAsync(function () {
                $grid.masonry('reloadItems').masonry();
            });
        };

        // getResources
        (function () {
            $http.get($attrs.apiurl)
                .then(function (response) {
                    $scope.resources = response.data.object;
                }, function (error) {
                    console.log({error});
                })
        }());

    });