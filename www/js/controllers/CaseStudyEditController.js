angular
    .module(angularAppName)
    .controller('CaseStudyEditController', function ($scope, $http, $timeout, $attrs, Upload) {
        var caseStudyId = $attrs.casestudyid;

        var editCountry = $('#edit-country');
        var editCountryRegion = $('#edit-countryRegion');

        $scope.logo = new DSI_Helpers.UploadImageHandler(Upload);
        $scope.cardImage = new DSI_Helpers.UploadImageHandler(Upload);
        $scope.headerImage = new DSI_Helpers.UploadImageHandler(Upload);

        // saveStory
        (function () {
            $scope.caseStudy = {};
            $http.get(SITE_RELATIVE_PATH + '/case-study/edit/' + caseStudyId + '.json')
                .then(function (response) {
                    console.log(response.data);
                    $scope.caseStudy = response.data;
                    $scope.logo.image = $scope.caseStudy.logo;
                    $scope.cardImage.image = $scope.caseStudy.cardImage;
                    $scope.headerImage.image = $scope.caseStudy.headerImage;
                });

            $scope.save = function () {
                $scope.loading = true;
                var data = $scope.caseStudy;
                data.logo = $scope.logo.image;
                data.cardImage = $scope.cardImage.image;
                data.headerImage = $scope.headerImage.image;
                data.countryID = editCountry.val();
                data.region = editCountryRegion.val();
                data.mainText = tinyMCE.get('mainText').getContent();
                data.save = true;

                console.log(data);

                $timeout(function () {
                    $http.post(SITE_RELATIVE_PATH + '/case-study/edit/' + caseStudyId + '.json', data)
                        .then(function (response) {
                            $scope.loading = false;
                            if (response.data.code == 'ok') {
                                // swal(response.data.message.title, response.data.message.text, "success");
                                swal('Success', 'Case Study details have been changed', "success");
                            } else if (response.data.code == 'error') {
                                $scope.errors = response.data.errors;
                                console.log(response.data.errors);
                            } else {
                                alert('unexpected error');
                                console.log(response.data);
                            }
                        });
                }, 500);
            }
        }());

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
                    $scope.$watch('caseStudy.countryID', function (param) {
                        editCountry.val($scope.caseStudy.countryID).trigger("change")
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
                        editCountryRegion.val($scope.caseStudy.region).trigger("change");
                    });
            }
        };

        listCountries();
    });