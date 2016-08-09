angular
    .module(angularAppName)
    .controller('DashboardController', function ($scope, $http, $timeout) {
        $scope.projectInvitations = [];
        $scope.organisationInvitations = [];
        $http.get(SITE_RELATIVE_PATH + '/dashboard.json')
            .then(function (response) {
                console.log(response.data);
                $scope.projectInvitations = response.data.projectInvitations;
                $scope.organisationInvitations = response.data.organisationInvitations;
            });

        function extractElm(pool, elm) {
            var newPool = [];
            for (var i in pool) {
                if (pool[i].id != elm.id)
                    newPool.push(pool[i]);
            }
            return newPool;
        }

        $scope.approveProjectInvitation = function (invitation) {
            console.log(invitation);
            $http.post(SITE_RELATIVE_PATH + '/dashboard.json', {
                approveProjectInvitation: true,
                projectID: invitation.id
            }).then(function (response) {
                if (response.data.code == 'ok') {
                    swal(response.data.message.title, response.data.message.text, "success");
                    $scope.projectInvitations = extractElm($scope.projectInvitations, invitation);
                } else if (response.data.code == 'error') {

                }
            });
        };

        $scope.declineProjectInvitation = function (invitation) {
            swal({
                title: "Are you sure?",
                text: "You will not be able to join this project at this time",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, continue!",
                closeOnConfirm: false
            }, function () {
                console.log(invitation);
                $http.post(SITE_RELATIVE_PATH + '/dashboard.json', {
                    rejectProjectInvitation: true,
                    projectID: invitation.id
                }).then(function (response) {
                    console.log(response.data);
                    if (response.data.code == 'ok') {
                        swal(response.data.message.title, response.data.message.text, "warning");
                        $scope.projectInvitations = extractElm($scope.projectInvitations, invitation);
                    } else if (response.data.code == 'error') {

                    }
                });
            });
        };

        $scope.approveOrganisationInvitation = function (invitation) {
            console.log(invitation);
            $http.post(SITE_RELATIVE_PATH + '/dashboard.json', {
                approveOrganisationInvitation: true,
                organisationID: invitation.id
            }).then(function (response) {
                if (response.data.code == 'ok') {
                    swal(response.data.message.title, response.data.message.text, "success");
                    $scope.organisationInvitations = extractElm($scope.organisationInvitations, invitation);
                } else if (response.data.code == 'error') {

                }
            });
        };

        $scope.declineOrganisationInvitation = function (invitation) {
            swal({
                title: "Are you sure?",
                text: "You will not be able to join this organisation at this time",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, continue!",
                closeOnConfirm: false
            }, function () {
                console.log(invitation);
                $http.post(SITE_RELATIVE_PATH + '/dashboard.json', {
                    rejectOrganisationInvitation: true,
                    organisationID: invitation.id
                }).then(function (response) {
                    console.log(response.data);
                    if (response.data.code == 'ok') {
                        swal(response.data.message.title, response.data.message.text, "warning");
                        $scope.organisationInvitations = extractElm($scope.organisationInvitations, invitation);
                    } else if (response.data.code == 'error') {
                        
                    }
                });
            });
        };

        $scope.tempAlertBox = function () {
            swal('Success', 'You are now a member of Nesta', "success");
        };

        $scope.tempConfirmBox = function () {
            swal({
                title: "Are you sure?",
                text: "You will not be able to join this organisation at this time",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, continue!",
                closeOnConfirm: false
            }, function () {
                swal('OK', 'You have declined this invitation', "warning");
            });
        }
    });