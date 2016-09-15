angular
    .module(angularAppName)
    .controller('EventEditController', function ($scope, $http, $attrs) {

        var editUrl = $attrs.editurl;

        var editCountry = $('#edit-country');
        var editCountryRegion = $('#edit-countryRegion');

        $http.get(editUrl).then(function (response) {
            $scope.event = response.data;
        });

        $scope.save = function () {
            $scope.loading = true;
            var data = $scope.event;
            data.countryID = editCountry.val();
            data.region = editCountryRegion.val();
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

        // country & region
        var currentCountry = '';
        var listCountries = function () {
            $http.get(SITE_RELATIVE_PATH + '/countries.json')
                .then(function (result) {
                    editCountry.select2({data: result.data});
                    editCountry.on("change", function () {
                        if (currentCountry != editCountry.val()) {
                            listCountryRegions(editCountry.val());
                            currentCountry = editCountry.val();
                        }
                    });
                    $scope.$watch('event.countryID', function (param) {
                        editCountry.val($scope.event.countryID).trigger("change")
                    });
                });
        };
        var listCountryRegions = function (countryID) {
            countryID = parseInt(countryID) || 0;
            if (countryID > 0) {
                $scope.regionsLoaded = false;
                $scope.regionsLoading = true;
                $http.get(SITE_RELATIVE_PATH + '/countryRegions/' + countryID + '.json')
                    .then(function (result) {
                        editCountryRegion
                            .html("")
                            .select2({data: result.data});
                        $scope.regionsLoaded = true;
                        $scope.regionsLoading = false;
                        editCountryRegion.val($scope.event.region).trigger("change");
                    });
            }
        };

        listCountries();
    });