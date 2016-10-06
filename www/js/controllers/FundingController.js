angular
    .module(angularAppName)
    .controller('FundingController', function ($scope, $http, $attrs) {
        var fundingJsonUrl = $attrs.fundingjsonurl;

        $scope.fundingType = "0";
        $scope.fundingTarget = "0";

        $http.get(fundingJsonUrl)
            .then(function (result) {
                $scope.data = result.data;
                $scope.fundingSource = $scope.data.sources[0];
                $scope.beforeMonth = $scope.data.months[0];
                $scope.beforeYear = $scope.data.years[0];
                $scope.inCountry = $scope.data.countries[0];
            });

        $scope.earlierThan = function (val) {
            return function (item) {
                if (val == '000000')
                    return true;
                if (item.closingMonth == '')
                    return false;
                return item.closingMonth < val;
            }
        };

        $scope.fundingHasTarget = function (val) {
            return function (item) {
                if (val == "0")
                    return true;

                var length = item.fundingTargets.length;
                for(var i = 0; i < length; i++) {
                    if(item.fundingTargets[i] == val) return true;
                }
                return false;
            }
        }
    });