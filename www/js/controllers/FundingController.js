angular
    .module(angularAppName)
    .controller('FundingController', function ($scope, $http, $attrs) {
        var fundingJsonUrl = $attrs.fundingjsonurl;

        $http.get(fundingJsonUrl)
            .then(function (result) {
                console.log(result.data);
                $scope.data = result.data;
                $scope.fundingSource = $scope.data.sources[0];
                $scope.beforeMonth = $scope.data.months[0];
                $scope.beforeYear = $scope.data.years[0];
                $scope.inCountry = $scope.data.countries[0];
            });
    });