angular
    .module(angularAppName)
    .controller('CaseStudyEditController', function ($scope, $http, $timeout, $attrs, Upload) {
        var caseStudyId = $attrs.casestudyid;
        console.log(caseStudyId);

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
            $http.get(SITE_RELATIVE_PATH + '/case-study/edit/' + caseStudyId + '.json')
                .then(function (response) {
                    $scope.caseStudy = response.data;
                });

            $scope.add = function () {
                $scope.loading = true;
                var data = $scope.caseStudy;
                data.logo = $scope.logo.image;
                data.cardImage = $scope.cardImage.image;
                data.headerImage = $scope.headerImage.image;
                data.countryID = editCountry.val();
                data.region = editCountryRegion.val();
                data.mainText = tinyMCE.get('mainText').getContent();
                data.add = true;

                $timeout(function () {
                    $http.post(SITE_RELATIVE_PATH + '/case-study/add', data)
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
                }, 500);
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
                        $timeout(function () {
                            editCountryRegion
                                .html("")
                                .select2({data: result.data});
                            $scope.regionsLoaded = true;
                            $scope.regionsLoading = false;
                        }, 300);
                    });
            }
        };

        listCountries();
    });