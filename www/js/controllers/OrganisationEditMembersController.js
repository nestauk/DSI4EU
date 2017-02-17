angular
    .module(angularAppName)
    .controller('OrganisationEditMembersController', function ($scope, $http) {
        var url = window.location.pathname + '.json';

        $scope.members = [];
        $scope.searchExistingUser = {};
        $scope.inviteByEmail = {};

        $scope.searchExistingUser.submit = function () {
            $scope.searchExistingUser.users = [];
            $scope.searchExistingUser.loading = true;
            $http
                .post(url, {searchExistingUser: $scope.searchExistingUser.input})
                .then(function (response) {
                    $scope.searchExistingUser.loading = false;
                    if (response.data.code == 'ok') {
                        $scope.searchExistingUser.users = response.data.users;
                    } else {
                        console.log({error: response.data});
                    }
                });
        };
        $scope.searchExistingUser.addUser = function (user) {
            swal({
                    title: "",
                    text: translate.get("You are about to invite this user to join the organisation"),
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: translate.get("Continue"),
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function () {
                    $http.post(url, {addExistingUser: user.id})
                        .then(function (response) {
                            if (response.data.code == 'ok') {
                                swal(
                                    translate.get("Success!"),
                                    translate.get("The user has been invited to join the organisation."),
                                    "success"
                                );
                                getExistingAndInvitedOrganisationMembers();
                            } else {
                                swal("Info", Object.values(response.data.errors).join(' '), "info");
                            }
                        });
                });
        };

        $scope.inviteByEmail.submit = function () {
            swal({
                    title: "",
                    text: translate.get("You are about to invite this person to join the organisation"),
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: translate.get("Continue"),
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function () {
                    $http.post(url, {inviteEmail: $scope.inviteByEmail.email})
                        .then(function (response) {
                            if (response.data.code == 'ok') {
                                swal(
                                    translate.get("Success!"),
                                    translate.get("An invitation to join the organisation has been sent by email."),
                                    "success"
                                );
                                getExistingAndInvitedOrganisationMembers();
                                $scope.inviteByEmail.email = '';
                            } else {
                                swal("Info", Object.values(response.data.errors).join(' '), "info");
                            }
                        });
                });
        };

        $scope.cancelInvitationForUser = function (user) {
            swal({
                    title: "",
                    text: translate.get("You are about to cancel this user's invitation to join the organisation"),
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: translate.get("Continue"),
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function () {
                    $http.post(url, {cancelUserInvitation: user.id})
                        .then(function (response) {
                            if (response.data.code == 'ok') {
                                swal(
                                    translate.get("Success!"),
                                    translate.get("The user has been invited to join the organisation."),
                                    "success"
                                );
                                getExistingAndInvitedOrganisationMembers();
                            } else {
                                swal("Info", Object.values(response.data.errors).join(' '), "info");
                            }
                        });
                });
        };
        $scope.removeMember = function (user) {
            swal({
                    title: "",
                    text: translate.get("You are about to remove this user from the organisation"),
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: translate.get("Continue"),
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function () {
                    $http.post(url, {removeMember: user.id})
                        .then(function (response) {
                            if (response.data.code == 'ok') {
                                swal(
                                    translate.get("Success!"),
                                    translate.get("The user has been removed from the organisation."),
                                    "success"
                                );
                                getExistingAndInvitedOrganisationMembers();
                            } else {
                                swal("Info", Object.values(response.data.errors).join(' '), "info");
                            }
                        });
                });
        };
        $scope.makeAdmin = function (user) {
            swal({
                    title: "",
                    text: translate.get("You are about to give admin privileges to this user"),
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: translate.get("Continue"),
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function () {
                    $http.post(url, {makeAdmin: user.id})
                        .then(function (response) {
                            if (response.data.code == 'ok') {
                                swal(
                                    translate.get("Success!"),
                                    translate.get("The user now has admin privileges."),
                                    "success"
                                );
                                getExistingAndInvitedOrganisationMembers();
                            } else {
                                swal("Info", Object.values(response.data.errors).join(' '), "info");
                            }
                        });
                });
        };
        $scope.removeAdmin = function (user) {
            swal({
                    title: "",
                    text: translate.get("You are about to remove admin privileges from this user"),
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: translate.get("Continue"),
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function () {
                    $http.post(url, {removeAdmin: user.id})
                        .then(function (response) {
                            if (response.data.code == 'ok') {
                                swal(
                                    translate.get("Success!"),
                                    translate.get("Admin privileges have been removed from the user."),
                                    "success"
                                );
                                getExistingAndInvitedOrganisationMembers();
                            } else {
                                swal("Info", Object.values(response.data.errors).join(' '), "info");
                            }
                        });
                });
        };
        $scope.cancelInvitationForEmail = function (user) {
            swal({
                    title: "",
                    text: translate.get("You are about to cancel the invitation sent to the user"),
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: translate.get("Continue"),
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function () {
                    $http.post(url, {cancelInvitationForEmail: user.email})
                        .then(function (response) {
                            if (response.data.code == 'ok') {
                                swal(
                                    translate.get("Success!"),
                                    translate.get("The invitation to join the organisation has been cancelled."),
                                    "success"
                                );
                                getExistingAndInvitedOrganisationMembers();
                            } else {
                                swal("Info", Object.values(response.data.errors).join(' '), "info");
                            }
                        });
                });
        };

        function getExistingAndInvitedOrganisationMembers() {
            $http
                .get(url)
                .then(function (response) {
                    $scope.members = response.data.members;
                    $scope.invitedMembers = response.data.invitedMembers;
                    $scope.invitedEmails = response.data.invitedEmails;
                });
        }

        getExistingAndInvitedOrganisationMembers();
    });