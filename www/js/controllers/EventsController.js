angular
    .module(angularAppName)
    .controller('EventsController', function ($scope, $http, $attrs) {
        var eventsJsonUrl = $attrs.eventsjsonurl;

        $http.get(eventsJsonUrl)
            .then(function (result) {
                console.log(result.data);
                $scope.data = result.data;
                $scope.beforeMonth = $scope.data.months[0];
                $scope.beforeYear = $scope.data.years[0];
            });

        $scope.earlierThan = function (val) {
            return function (item) {
                if (val == '000000')
                    return true;
                if (item.startMonth == '')
                    return false;
                return item.startMonth < val;
            }
        }
    });