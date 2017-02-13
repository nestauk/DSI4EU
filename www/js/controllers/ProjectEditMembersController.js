angular
    .module(angularAppName)
    .controller('ProjectEditMembersController', function ($scope, $http) {
        var url = window.location.pathname + '.json';
        console.log(url);

        $scope.members = [];
        $scope.searchExistingUser = {};

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
                        console.log(response.data);
                    }
                });
        };
        $scope.searchExistingUser.addUser = function (user) {
            swal({
                    title: "Are you sure?",
                    text: "You are about to invite this user to join the project",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Continue",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function () {
                    $http
                        .post(url, {addExistingUser: user.id})
                        .then(function (response) {
                            if (response.data.code == 'ok') {
                                getExistingAndInvitedProjectMembers();
                                swal("Success!", "The user has been invited to join the project.", "success");
                            } else {
                                swal("Info", Object.values(response.data.errors).join(' '), "info");
                            }
                        });
                });
        };

        $scope.cancelInvitationForUser = function (user) {
            swal({
                    title: "Are you sure?",
                    text: "You are about to cancel this user's invitation to join the project",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Continue",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function () {
                    $http
                        .post(url, {cancelUserInvitation: user.id})
                        .then(function (response) {
                            if (response.data.code == 'ok') {
                                getExistingAndInvitedProjectMembers();
                                swal("Success!", "The user has been invited to join the project.", "success");
                            } else {
                                swal("Info", Object.values(response.data.errors).join(' '), "info");
                            }
                        });
                });
        };

        function getExistingAndInvitedProjectMembers() {
            $http
                .get(url)
                .then(function (response) {
                    console.log(response.data);
                    $scope.members = response.data.members;
                    $scope.invitedMembers = response.data.invitedMembers;
                });
        }

        getExistingAndInvitedProjectMembers();
    });