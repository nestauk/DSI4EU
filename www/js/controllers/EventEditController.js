angular
    .module(angularAppName)
    .controller('EventEditController', function ($scope, $http, $attrs) {

        var editUrl = $attrs.editurl;
        var countryID = $('#countryID');

        $http.get(editUrl).then(function (response) {
            $scope.event = response.data;
        });

        $scope.save = function () {
            $scope.loading = true;
            var data = $scope.event;
            data.countryID = countryID.val();
            data.save = true;

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
        };
    });