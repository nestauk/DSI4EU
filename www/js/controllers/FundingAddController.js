angular
    .module(angularAppName)
    .controller('FundingAddController', function ($scope, $http, $timeout) {

        var countryID = $('#countryID');
        var source = $('#fundingSource');

        (function () {
            $scope.funding = {};

            $scope.add = function () {
                $scope.loading = true;
                var data = $scope.funding;
                data.source = source.val();
                data.countryID = countryID.val();
                // data.description = tinyMCE.get('description').getContent();
                data.add = true;

                $timeout(function () {
                    $http.post(SITE_RELATIVE_PATH + '/funding/add', data)
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