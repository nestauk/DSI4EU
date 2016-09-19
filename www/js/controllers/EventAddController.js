angular
    .module(angularAppName)
    .controller('EventAddController', function ($scope, $http) {
        $scope.event = {};

        var editCountry = $('#edit-country');
        var editCountryRegion = $('#edit-countryRegion');

        $scope.add = function () {
            $scope.loading = true;
            var data = $scope.event;
            // data.shortDescription = tinyMCE.get('description').getContent();
            data.description = tinyMCE.get('description').getContent();
            data.countryID = editCountry.val();
            data.region = editCountryRegion.val();
            data.add = true;

            $http.post(SITE_RELATIVE_PATH + '/events/add', data)
                .then(function (response) {
                    $scope.loading = false;
                    if (response.data.code == 'ok') {
                        swal({
                            title: 'Success',
                            text: 'The event has been successfully created',
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
        };

        // country & region
        var listCountries = function () {
            $http.get(SITE_RELATIVE_PATH + '/countries.json')
                .then(function (result) {
                    editCountry.select2({data: result.data});
                    editCountry.on("change", function () {
                        listCountryRegions(editCountry.val());
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
                    });
            }
        };

        listCountries();
    });