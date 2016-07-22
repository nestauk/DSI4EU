angular
    .module(angularAppName)
    .controller('OrganisationEditController', function ($scope, $http, $timeout, Upload, $attrs) {
        var organisationId = $attrs.organisationid;

        var editCountry = $('#edit-country');
        var editCountryRegion = $('#edit-countryRegion');

        $scope.logo = new DSI_Helpers.UploadImageHandler(Upload);
        $scope.headerImage = new DSI_Helpers.UploadImageHandler(Upload);


        $scope.organisation = {};
        $http.get(SITE_RELATIVE_PATH + '/org/edit/' + organisationId + '.json')
            .then(function (result) {
                $scope.organisation = result.data || {};
                $scope.logo.image = $scope.organisation.logo;
                $scope.headerImage.image = $scope.organisation.headerImage;
                console.log($scope.organisation);
            });


        $scope.currentTab = 'step1';
        $scope.submitStep1 = function () {
            $scope.organisation.tags = $('#tagsSelect').val();
            $scope.organisation.projects = $('#projectsSelect').val();
            $scope.saveDetails({
                postField: 'step1',
                onSuccess: function () {
                    $scope.currentTab = 'step2';
                }
            })
        };
        $scope.submitStep2 = function () {
            $scope.organisation.countryID = editCountry.val();
            $scope.organisation.region = editCountryRegion.val();
            $scope.saveDetails({
                postField: 'step2',
                onSuccess: function () {
                    $scope.currentTab = 'step3';
                }
            })
        };
        $scope.submitStep3 = function () {
            $scope.organisation.description = tinyMCE.get('description').getContent();
            $scope.saveDetails({
                postField: 'step3',
                onSuccess: function () {
                    $scope.currentTab = 'step4';
                }
            })
        };
        $scope.submitStep4 = function () {
            $scope.errors = {};
            if (!$scope.organisation.confirm) {
                $scope.errors = {
                    confirm: 'You have to agree with our terms and conditions'
                };
            } else {
                $scope.organisation.logo = $scope.logo.image;
                $scope.organisation.headerImage = $scope.headerImage.image;
                $scope.saveDetails({
                    postField: 'step4',
                    onSuccess: function () {
                        swal('Success!', 'The organisation details have been successfully saved.', "success");
                    }
                })
            }
        };

        $scope.saveDetails = function (options) {
            $scope.loading = true;
            $scope.errors = {};

            var data = $scope.organisation;
            data['saveDetails'] = true;
            data['step'] = options.postField;

            console.log(data);

            $http.post(SITE_RELATIVE_PATH + '/org/edit/' + organisationId + '.json', data)
                .then(function (response) {
                    $scope.loading = false;
                    console.log(response.data);

                    if (response.data.code == 'ok')
                        options.onSuccess();
                    else if (response.data.code == 'error')
                        $scope.errors = response.data.errors;
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
                    $scope.$watch('organisation.countryID', function (param) {
                        editCountry.val($scope.organisation.countryID).trigger("change")
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
                        editCountryRegion.val($scope.organisation.region).trigger("change");
                    });
            }
        };
        listCountries();
    });

(function () {
    tinymce.init({
        selector: '#description',
        height: 500,
        plugins: "autoresize autolink lists link preview paste textcolor colorpicker image imagetools media",
        autoresize_bottom_margin: 0,
        autoresize_max_height: 500,
        menubar: false,
        toolbar1: 'styleselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | preview',
        image_advtab: true,
        paste_data_images: false
    });
}());