angular
    .module(angularAppName)
    .controller('ProjectEditMembersController', function ($scope, $http) {
        var url = window.location.pathname + '.json';
        console.log(url);

        $scope.members = [];

        $http
            .get(url)
            .then(function (response) {
                $scope.members = response.data.members;
            });

        $scope.save = function () {
            $scope.loading = true;
            $scope.errors = {};

            $http.post(window.location.href, {
                save: true,
                newOwnerID: $('#newOwner').val()
            })
                .then(function (response) {
                    $scope.loading = false;

                    if (response.data.code == 'ok') {
                        swal('Success!', 'The changes have been successfully saved.', 'success');
                    } else if (response.data.code == 'error') {
                        $scope.errors = response.data.errors;
                    }
                });
        };
    });