angular
    .module(angularAppName)
    .controller('FundingEditController', function ($scope, $http, $timeout, $attrs) {

        var editUrl = $attrs.editurl;
        var countryID = $('#countryID');
        var source = $('#fundingSource');

        (function () {
            $http.get(editUrl).then(function (response) {
                $scope.funding = response.data;
                console.log($scope.funding)
            });

            $scope.save = function () {
                $scope.loading = true;
                var data = $scope.funding;
                data.source = source.val();
                data.countryID = countryID.val();
                data.save = true;

                $timeout(function () {
                    $http.post(editUrl, data)
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
                }, 500);
            }
        }());
    });