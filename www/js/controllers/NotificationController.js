angular
    .module(angularAppName)
    .controller('NotificationController', function ($scope, $http, $attrs) {
        var url = $attrs.url;
        console.log(url);

        $http
            .get(url)
            .then(function (response) {
                if (response.data.code == 'ok') {
                    $scope.notifications = response.data.notifications;
                } else {
                    alert('unexpected error');
                    console.log(response.data)
                }
            });
    });