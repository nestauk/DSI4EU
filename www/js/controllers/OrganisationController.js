var app = angular.module('DSIApp');

app.controller('OrganisationController', function ($scope, $http, $attrs, $timeout) {
    var editCountry = $('#Edit-country');
    var editCountryRegion = $('#Edit-countryRegion');

    $scope.updateBasic = function () {
        var data = {
            updateBasic: true,
            name: $scope.organisation.name,
            description: $scope.organisation.description
        };

        $http.post(SITE_RELATIVE_PATH + '/org/' + $attrs.organisationid + '.json', data)
            .then(function (response) {
                console.log(response.data);
                if (response.data.result == 'error') {
                    alert('error');
                    console.log(response.data);
                }
            });
    };

    // Get Organisation Details
    $http.get(SITE_RELATIVE_PATH + '/org/' + $attrs.organisationid + '.json')
        .then(function (response) {
            $scope.organisation = response.data || {};
            console.log($scope.organisation);
            listCountries();
        });

    var listCountries = function () {
        $http.get(SITE_RELATIVE_PATH + '/countries.json')
            .then(function (result) {
                editCountry.select2({data: result.data});
                editCountry.on("change", function () {
                    listCountryRegions(editCountry.val());
                });
                editCountry.val($scope.organisation.countryID).trigger("change");
            });
    };
    var listCountryRegions = function (countryID) {
        countryID = parseInt(countryID) || 0;
        if (countryID > 0) {
            $scope.loadingCountryRegions = true;
            $http.get(SITE_RELATIVE_PATH + '/countryRegions/' + countryID + '.json')
                .then(function (result) {
                    $timeout(function () {
                        editCountryRegion
                            .html("")
                            .select2({data: result.data})
                            .val($scope.organisation.countryRegion)
                            .trigger("change");
                        $scope.loadingCountryRegions = false;
                    }, 500);
                });
        }
    };
});