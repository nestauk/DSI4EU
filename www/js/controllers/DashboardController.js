angular
    .module(angularAppName)
    .controller('DashboardController', function ($scope, $http, $timeout, $attrs) {
        var dashboardJsonUrl = $attrs.dashboardjsonurl;

        $scope.notifications = {};
        $http.get(dashboardJsonUrl)
            .then(function (response) {
                $scope.notifications = response.data;
            });

        function extractElm(pool, elm) {
            var newPool = [];
            for (var i in pool) {
                if (pool[i].id != elm.id)
                    newPool.push(pool[i]);
            }
            return newPool;
        }

        $scope.notificationsCount = function () {
            return ($scope.notifications.projectInvitations ? $scope.notifications.projectInvitations.length : 0)
                + ($scope.notifications.organisationInvitations ? $scope.notifications.organisationInvitations.length : 0)
                + ($scope.notifications.organisationRequests ? $scope.notifications.organisationRequests.length : 0);
        };

        $scope.approveProjectInvitation = function (invitation) {
            $http.post(dashboardJsonUrl, {
                approveProjectInvitation: true,
                projectID: invitation.id
            }).then(function (response) {
                if (response.data.code == 'ok') {
                    swal(response.data.message.title, response.data.message.text, "success");
                    $scope.notifications.projectInvitations =
                        extractElm($scope.notifications.projectInvitations, invitation);
                } else {
                    alert('unexpected error');
                    console.log(response.data);
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
                $http.post(dashboardJsonUrl, {
                    rejectProjectInvitation: true,
                    projectID: invitation.id
                }).then(function (response) {
                    if (response.data.code == 'ok') {
                        swal(response.data.message.title, response.data.message.text, "warning");
                        $scope.notifications.projectInvitations =
                            extractElm($scope.notifications.projectInvitations, invitation);
                    } else {
                        alert('unexpected error');
                        console.log(response.data);
                    }
                });
            });
        };

        $scope.approveOrganisationInvitation = function (invitation) {
            $http.post(dashboardJsonUrl, {
                approveOrganisationInvitation: true,
                organisationID: invitation.id
            }).then(function (response) {
                if (response.data.code == 'ok') {
                    swal(response.data.message.title, response.data.message.text, "success");
                    $scope.notifications.organisationInvitations =
                        extractElm($scope.notifications.organisationInvitations, invitation);
                } else {
                    alert('unexpected error');
                    console.log(response.data);
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
                $http.post(dashboardJsonUrl, {
                    rejectOrganisationInvitation: true,
                    organisationID: invitation.id
                }).then(function (response) {
                    if (response.data.code == 'ok') {
                        swal(response.data.message.title, response.data.message.text, "warning");
                        $scope.notifications.organisationInvitations =
                            extractElm($scope.notifications.organisationInvitations, invitation);
                    } else {
                        alert('unexpected error');
                        console.log(response.data);
                    }
                });
            });
        };

        $scope.approveOrganisationRequest = function (invitation) {
            $http.post(dashboardJsonUrl, {
                approveOrganisationRequest: true,
                organisationID: invitation.organisation.id,
                userID: invitation.user.id
            }).then(function (response) {
                if (response.data.code == 'ok') {
                    swal(response.data.message.title, response.data.message.text, "success");
                    $scope.notifications.organisationRequests =
                        extractElm($scope.notifications.organisationRequests, invitation);
                } else {
                    alert('unexpected error');
                    console.log(response.data);
                }
            });
        };

        $scope.declineOrganisationRequest = function (invitation) {
            swal({
                title: "Are you sure?",
                text: "The user will not be able to join the organisation at this time",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, continue!",
                closeOnConfirm: false
            }, function () {
                $http.post(dashboardJsonUrl, {
                    rejectOrganisationRequest: true,
                    organisationID: invitation.organisation.id,
                    userID: invitation.user.id
                }).then(function (response) {
                    if (response.data.code == 'ok') {
                        swal(response.data.message.title, response.data.message.text, "warning");
                        $scope.notifications.organisationRequests =
                            extractElm($scope.notifications.organisationRequests, invitation);
                    } else {
                        alert('unexpected error');
                        console.log(response.data);
                    }
                });
            });
        };
    });