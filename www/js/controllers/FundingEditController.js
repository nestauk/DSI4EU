angular
    .module(angularAppName)
    .controller('FundingEditController', function ($scope, $http, $attrs) {

        var editUrl = $attrs.editurl;
        var countryID = $('#countryID');
        var source = $('#fundingSource');
        var type = $('#fundingTypeID');

        $http.get(editUrl).then(function (response) {
            $scope.funding = response.data;
        });

        $scope.save = function () {
            $scope.loading = true;
            var data = $scope.funding;
            data.typeID = type.val();
            data.source = source.val();
            data.countryID = countryID.val();
            data.targets = $('input[name="target[]"]:checked').map(function (index, input) {
                return input.value;
            }).toArray();
            data.save = true;

            $http.post(editUrl, data)
                .then(function (response) {
                    $scope.loading = false;
                    if (response.data.code == 'ok') {
                        swal({
                            title: 'Success',
                            text: 'The funding has been successfully updated',
                            type: "success"
                        });
                    } else if (response.data.code == 'error') {
                        $scope.errors = response.data.errors;
                        console.log(response.data.errors);
                    } else {
                        alert('unexpected error');
                        console.log(response.data);
                    }
                });
        };
    });