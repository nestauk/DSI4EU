angular
    .module(angularAppName)
    .controller('EventAddController', function ($scope, $http) {

        (function () {
            $scope.event = {};

            $scope.add = function () {
                $scope.loading = true;
                var data = $scope.event;
                // data.shortDescription = tinyMCE.get('description').getContent();
                data.description = tinyMCE.get('description').getContent();
                data.add = true;

                $http.post(SITE_RELATIVE_PATH + '/events/add', data)
                    .then(function (response) {
                        $scope.loading = false;
                        if (response.data.code == 'ok') {
                            window.location.href = response.data.url;
                        } else if (response.data.code == 'error') {
                            $scope.errors = response.data.errors;
                            console.log(response.data.errors);
                        } else {
                            alert('unexpected error');
                            console.log(response.data);
                        }
                    });
            }
        }());
    });