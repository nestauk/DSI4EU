angular
    .module(angularAppName)
    .controller('ClusterEditController', function ($scope, $http, $attrs) {

        var editUrl = $attrs.editurl;

        $http.get(editUrl).then(function (response) {
            $scope.cluster = response.data.object;
        });

        $scope.save = function () {
            $scope.loading = true;
            $scope.cluster.description = tinyMCE.get('description').getContent();
            $scope.cluster.get_in_touch = tinyMCE.get('get_in_touch').getContent();

            $http.post(editUrl, $scope.cluster)
                .then(function (response) {
                    swal({
                        title: 'Success',
                        text: 'The event has been successfully saved',
                        type: "success"
                    });
                })
                .catch(function (error) {
                    $scope.errors = error.data.object;
                })
                .finally(function () {
                    $scope.loading = false;
                });
        };
    });