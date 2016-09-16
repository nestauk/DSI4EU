angular
    .module(angularAppName)
    .controller('CaseStudyAddController', function ($scope, $http, Upload) {

        var editCountry = $('#edit-country');
        var editCountryRegion = $('#edit-countryRegion');

        var UploadImageHandler = function () {
            this.uploader = {};
            this.upload = function (file, errFiles) {
                var $this = this;
                $this.errorMsg = {};
                $this.uploader.f = file;
                $this.uploader.errFile = errFiles && errFiles[0];
                if (file) {
                    file.upload = Upload.upload({
                        url: SITE_RELATIVE_PATH + '/temp-gallery.json',
                        data: {file: file}
                    });

                    file.upload.then(function (response) {
                        console.log(response.data);
                        file.result = response.data;
                        if (response.data.code == 'ok')
                            $this.image = response.data.imgPath;
                        else if (response.data.code == 'error')
                            $this.errorMsg = response.data.errors;

                        $this.uploader = {};
                    }, function (response) {
                        if (response.status > 0)
                            $this.errorMsg = response.status + ': ' + response.data;
                    }, function (evt) {
                        file.progress = Math.min(100, parseInt(100.0 *
                            evt.loaded / evt.total));
                    });
                }
            };
        };

        $scope.logo = new UploadImageHandler();
        $scope.cardImage = new UploadImageHandler();
        $scope.headerImage = new UploadImageHandler();

        // addStory
        (function () {
            $scope.caseStudy = {};
            $scope.caseStudy.isPublished = 0;
            $scope.caseStudy.positionOnHomePage = 0;
            $scope.caseStudy.cardColour = '#000000';

            $scope.add = function () {
                $scope.loading = true;
                var data = $scope.caseStudy;
                data.logo = $scope.logo.image;
                data.cardImage = $scope.cardImage.image;
                data.headerImage = $scope.headerImage.image;
                data.countryID = editCountry.val();
                data.region = editCountryRegion.val();
                data.introPageText = tinyMCE.get('pageIntro').getContent();
                data.mainText = tinyMCE.get('mainText').getContent();
                data.add = true;

                $http.post(SITE_RELATIVE_PATH + '/case-study/add', data)
                    .then(function (response) {
                        $scope.loading = false;
                        if (response.data.code == 'ok') {
                            swal({
                                title: 'Success',
                                text: 'The case study has been successfully created',
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
            }
        }());

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