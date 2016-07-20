angular
    .module(angularAppName)
    .controller('OrganisationEditController', function ($scope, $http, $timeout, Upload, $attrs) {
        var organisationId = $attrs.organisationid;
        $scope.organisation = {};
        $http.get(SITE_RELATIVE_PATH + '/org/edit/' + organisationId + '.json')
            .then(function (result) {
                $scope.organisation = result.data || {};
            });

        $scope.currentTab = 'step1';
        $scope.submitStep1 = function () {
            $scope.saveUserDetails({
                postField: 'step1',
                onSuccess: function () {
                    $scope.currentTab = 'step2';
                }
            })
        };
        $scope.submitStep2 = function () {
            $scope.organisation.languages = $('#languagesSelect').val();
            $scope.organisation.skills = $('#skillsSelect').val();
            $scope.saveUserDetails({
                postField: 'step2',
                onSuccess: function () {
                    $scope.currentTab = 'step3';
                }
            })
        };
        $scope.submitStep3 = function () {
            $scope.organisation.projects = $('#projectsSelect').val();
            $scope.organisation.organisations = $('#organisationsSelect').val();
            $scope.saveUserDetails({
                postField: 'step3',
                onSuccess: function () {
                    $scope.currentTab = 'step4';
                }
            })
        };
        $scope.submitStep4 = function (organisationPage) {
            console.log({goto: organisationPage});
            $scope.errors = {};
            if (!$scope.organisation.confirm) {
                $scope.errors = {
                    confirm: 'You have to agree with our terms and conditions'
                };
            } else {
                $scope.loading = true;
                window.location.href = organisationPage;
            }
        };

        $scope.saveUserDetails = function (options) {
            $scope.loading = true;
            $scope.errors = {};

            var data = $scope.organisation;
            data['saveDetails'] = true;
            data['step'] = options.postField;

            console.log(data);
            $scope.loading = false;
            options.onSuccess();
            return;

            $http.post(SITE_RELATIVE_PATH + '/my-profile.json', data)
                .then(function (response) {
                    $scope.loading = false;
                    console.log(response.data);

                    if (response.data.response == 'ok')
                        options.onSuccess();
                    else if (response.data.response == 'error')
                        $scope.errors = response.data.errors;
                });
        };
    });