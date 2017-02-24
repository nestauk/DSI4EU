angular
    .module(angularAppName)
    .controller('DashboardController', function ($scope, $http, $timeout, $attrs) {
        var url = $attrs.dashboardjsonurl;

        $scope.notifications = {};
        $http.get(url)
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
                + ($scope.notifications.projectRequests ? $scope.notifications.projectRequests.length : 0)
                + ($scope.notifications.organisationRequests ? $scope.notifications.organisationRequests.length : 0);
        };

        $scope.approveProjectInvitation = function (invitation) {
            $http.post(url, {
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
                $http.post(url, {
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
            $http.post(url, {
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
                $http.post(url, {
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
            $http.post(url, {
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
                $http.post(url, {
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

        $scope.approveProjectRequest = function (invitation) {
            $http.post(url, {
                approveProjectRequest: true,
                projectID: invitation.project.id,
                userID: invitation.user.id
            }).then(function (response) {
                if (response.data.code == 'ok') {
                    swal(response.data.message.title, response.data.message.text, "success");
                    $scope.notifications.projectRequests =
                        extractElm($scope.notifications.projectRequests, invitation);
                } else {
                    alert('unexpected error');
                    console.log(response.data);
                }
            });
        };

        $scope.declineProjectRequest = function (invitation) {
            swal({
                title: "Are you sure?",
                text: "The user will not be able to join the project at this time",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, continue!",
                closeOnConfirm: false
            }, function () {
                $http.post(url, {
                    rejectProjectRequest: true,
                    projectID: invitation.project.id,
                    userID: invitation.user.id
                }).then(function (response) {
                    if (response.data.code == 'ok') {
                        swal(response.data.message.title, response.data.message.text, "warning");
                        $scope.notifications.projectRequests =
                            extractElm($scope.notifications.projectRequests, invitation);
                    } else {
                        alert('unexpected error');
                        console.log(response.data);
                    }
                });
            });
        };

        $scope.terminateAccount = function () {
            swal({
                    title: "",
                    text: translate.get("Are you sure you want to terminate your account?"),
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: translate.get("Yes"),
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function () {
                    $http.post(url, {terminateAccount: true})
                        .then(function (response) {
                            if (response.data.code == 'ok') {
                                swal(
                                    "",
                                    translate.get("An email will be sent to you to confirm your request."),
                                    "success"
                                );
                            } else {
                                swal("Info", Object.values(response.data.errors).join(' '), "info");
                            }
                        });
                });
        }
    });