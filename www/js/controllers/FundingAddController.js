angular
    .module(angularAppName)
    .controller('FundingAddController', function ($scope, $http) {

        var countryID = $('#countryID');
        var source = $('#fundingSource');
        var type = $('#fundingTypeID');

        (function () {
            $scope.funding = {};

            $scope.add = function () {
                $scope.loading = true;
                var data = $scope.funding;
                data.typeID = type.val();
                data.source = source.val();
                data.countryID = countryID.val();
                data.targets = $('input[name="target[]"]:checked').map(function (index, input) {
                    return input.value;
                }).toArray();
                // data.description = tinyMCE.get('description').getContent();
                data.add = true;

                $http.post(SITE_RELATIVE_PATH + '/funding/add', data)
                    .then(function (response) {
                        $scope.loading = false;
                        if (response.data.code == 'ok') {
                            swal({
                                title: 'Success',
                                text: 'The funding has been successfully created',
                                type: "success"
                            }, function () {
                                window.location.href = response.data.url;
                            });
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