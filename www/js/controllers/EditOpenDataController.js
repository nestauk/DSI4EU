angular
    .module(angularAppName)
    .controller('EditOpenDataController', function ($scope, $http, Upload, $attrs) {
        $scope.modals = {
            resource: false
        };

        $scope.deleteResource = function (url) {
            if (confirm('Are you sure you want to remove this resource?')) {
                $http.delete(url)
                    .then(function (response) {
                        swal({
                            title: 'Success',
                            text: 'The resource has been successfully removed',
                            type: "success"
                        }, function () {
                            window.location.reload(true);
                        });
                    })
                    .catch(function (error) {
                        $scope.errors = error.data.object;
                    });
            }
        };

        $scope.saveTexts = function () {
            $scope.loading = true;
            $scope.texts = {
                main: tinyMCE.get('main-text').getContent(),
                sub: tinyMCE.get('sub-text').getContent(),
            };

            $http.post(window.location.href, $scope.texts)
                .then(function (response) {
                    swal({
                        title: 'Success',
                        text: 'The page details have been successfully saved',
                        type: "success"
                    });
                })
                .catch(function (error) {
                    $scope.errors = error.data.object;
                    swal({
                        title: '',
                        text: 'Please correct the errors',
                        type: "error"
                    });
                })
                .finally(function () {
                    $scope.loading = false;
                });
        };
    });