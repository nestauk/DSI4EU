angular
    .module(angularAppName)
    .controller('CaseStudyAddController', function ($scope, $http, Upload) {
        $scope.cardImage = new DSI_Helpers.UploadImageHandler(Upload);

        // addStory
        (function () {
            $scope.caseStudy = {};
            $scope.caseStudy.isPublished = 0;
            $scope.caseStudy.positionOnHomePage = 0;
            $scope.caseStudy.projectID = "0";
            $scope.caseStudy.organisationID = "0";

            $scope.add = function () {
                $scope.loading = true;
                var data = $scope.caseStudy;
                data.cardImage = $scope.cardImage.image;
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

    });