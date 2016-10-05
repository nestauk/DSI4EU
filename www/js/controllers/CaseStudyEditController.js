angular
    .module(angularAppName)
    .controller('CaseStudyEditController', function ($scope, $http, $attrs, Upload) {
        var caseStudyId = $attrs.casestudyid;

        $scope.cardImage = new DSI_Helpers.UploadImageHandler(Upload);

        // saveStory
        (function () {
            $scope.caseStudy = {};
            $http.get(SITE_RELATIVE_PATH + '/case-study/edit/' + caseStudyId + '.json')
                .then(function (response) {
                    $scope.caseStudy = response.data;
                    $scope.cardImage.image = $scope.caseStudy.cardImage;
                });

            $scope.save = function () {
                $scope.loading = true;
                var data = $scope.caseStudy;
                data.cardImage = $scope.cardImage.image;
                data.introPageText = tinyMCE.get('pageIntro').getContent();
                data.mainText = tinyMCE.get('mainText').getContent();
                data.save = true;

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
            }
        }());
    });