(function () {
    angular
        .module(angularAppName)
        .controller('ProjectEditController', function ($scope, $http, $timeout, Upload, $attrs) {
            var projectID = $attrs.projectid;
            var editCountry = $('#edit-country');
            var editCountryRegion = $('#edit-countryRegion');

            $http.get(SITE_RELATIVE_PATH + '/project/edit/' + projectID + '.json')
                .then(function (result) {
                    console.log(result.data);
                    $scope.project = result.data || {};
                    listCountries();
                });

            $scope.save = function () {
                $scope.loading = true;
                $scope.errors = {};

                var data = $scope.project;
                data.countryID = editCountry.val();
                data.region = editCountryRegion.val();
                data.saveDetails = true;

                console.log(data);

                $http.post(SITE_RELATIVE_PATH + '/project/edit/' + projectID + '.json', data)
                    .then(function (response) {
                        $scope.loading = false;
                        console.log(response.data);

                        if (response.data.result == 'ok')
                            swal(response.data.message.title, response.data.message.text, "success");
                        else if (response.data.result == 'error')
                            $scope.errors = response.data.errors;
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
                        editCountry.val($scope.project.countryID).trigger("change");
                    });
            };
            var listCountryRegions = function (countryID) {
                countryID = parseInt(countryID) || 0;
                if (countryID > 0) {
                    $scope.regionsLoaded = false;
                    $scope.regionsLoading = true;
                    $http.get(SITE_RELATIVE_PATH + '/countryRegions/' + countryID + '.json')
                        .then(function (result) {
                            $timeout(function () {
                                editCountryRegion
                                    .html("")
                                    .select2({data: result.data})
                                    .val($scope.project.countryRegion)
                                    .trigger("change");
                                $scope.regionsLoaded = true;
                                $scope.regionsLoading = false;
                            }, 300);
                        });
                }
            };

            $scope.logo = {};
            $scope.logo.upload = function (file, errFiles) {
                $scope.logo.loading = true;
                $scope.logo.f = file;
                $scope.logo.errFile = errFiles && errFiles[0];
                if (file) {
                    file.upload = Upload.upload({
                        url: SITE_RELATIVE_PATH + '/temp-gallery.json',
                        data: {
                            file: file,
                            format: 'projectLogo'
                        }
                    });

                    file.upload.then(function (response) {
                        $scope.logo.loading = false;
                        console.log(response.data);
                        file.result = response.data;
                        if (response.data.code == 'ok')
                            $scope.project.logo = response.data.imgPath;
                        else if (response.data.code == 'error')
                            $scope.logo.errorMsg = response.data.errors;
                    }, function (response) {
                        if (response.status > 0)
                            $scope.logo.errorMsg = response.status + ': ' + response.data;
                    }, function (evt) {
                        file.progress = Math.min(100, parseInt(100.0 *
                            evt.loaded / evt.total));
                    });
                }
            };
        });
}());