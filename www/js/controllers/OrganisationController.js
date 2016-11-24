angular
    .module(angularAppName)
    .controller('OrganisationController', function ($scope, $http, $attrs, $timeout) {
        var Helpers = {
            getFirstNonEmptyValue: function (values) {
                for (var i in values) {
                    if (values[i] != '')
                        return values[i];
                }
                return null;
            },
            getItemIndexById: function (pool, id) {
                for (var i in pool) {
                    if (pool[i].id == id)
                        return i;
                }
                return -1;
            },
            swalWarning: function (data) {
                data.options.type = "warning";
                data.options.showCancelButton = true;
                data.optionscloseOnConfirm = false;
                data.options.showLoaderOnConfirm = true;

                swal(data.options, function () {
                    $http
                        .post(window.location.href, {
                            getSecureCode: true
                        })
                        .then(function (response) {
                            if (response.data.code == 'ok') {
                                receivedCode(response.data.secureCode)
                            } else {
                                alert('unexpected error');
                                console.log(response.data)
                            }
                        });

                    function receivedCode(secureCode) {
                        data.post.secureCode = secureCode;
                        $http
                            .post(window.location.href, data.post)
                            .then(function (response) {
                                if (response.data.code == 'ok') {
                                    success()
                                } else {
                                    alert('unexpected error');
                                    console.log(response.data)
                                }
                            })
                    }

                    function success() {
                        data.success.type = "success";
                        swal(data.success, data.successCallback);
                    }
                });
            }
        };

        var addMemberSelect = $('#Add-member');

        $scope.organisationid = $attrs.organisationid;

        $scope.addMember = function () {
            var newMemberID = addMemberSelect.select2().val();
            addMemberSelect.select2().val('').trigger("change");

            if (newMemberID == '') return;

            addNewMember(newMemberID);
        };

        $scope.confirmDelete = function (url) {
            swal({
                title: "Delete the organisation",
                text: "Are you sure you want to delete this organisation?",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $http
                    .post(window.location.href, {
                        getSecureCode: true
                    })
                    .then(function (response) {
                        if (response.data.code == 'ok') {
                            receivedCode(response.data.secureCode)
                        } else {
                            alert('unexpected error');
                            console.log(response.data)
                        }
                    });

                function receivedCode(secureCode) {
                    $http
                        .post(window.location.href, {
                            deleteOrganisation: true,
                            secureCode: secureCode
                        })
                        .then(function (response) {
                            if (response.data.code == 'ok') {
                                successfulDeletion(response.data.url)
                            } else {
                                alert('unexpected error');
                                console.log(response.data)
                            }
                        })
                }

                function successfulDeletion(url) {
                    swal({
                        title: "Deleted",
                        text: "The organisation has been deleted.",
                        type: "success"
                    }, function () {
                        window.location.href = url
                    });
                }
            });
        };
        $scope.report = function (url) {
            swal({
                title: "Report this organisation",
                text: "Please tell us why you are reporting this organisation",
                type: "input",
                inputPlaceholder: "Reason for report",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function (inputValue) {
                if (inputValue === false) return false;

                if (inputValue === "") {
                    swal.showInputError("Please type the reason for reporting this organisation.");
                    return false
                }

                $http
                    .post(window.location.href, {
                        getSecureCode: true
                    })
                    .then(function (response) {
                        if (response.data.code == 'ok') {
                            receivedCode(response.data.secureCode, inputValue)
                        } else {
                            alert('unexpected error');
                            console.log(response.data)
                        }
                    });

                function receivedCode(secureCode, inputValue) {
                    $http
                        .post(window.location.href, {
                            reportOrganisation: true,
                            reason: inputValue,
                            secureCode: secureCode
                        })
                        .then(function (response) {
                            if (response.data.code == 'ok') {
                                successfulReport(response.data.url)
                            } else {
                                alert('unexpected error');
                                console.log(response.data)
                            }
                        })
                }

                function successfulReport(url) {
                    swal({
                        title: "Reported",
                        text: "Thank you for reporting this organisation.",
                        type: "success"
                    });
                }
            });
        };

        $scope.addOrganisationMember = {};
        var addNewMember = function (memberIdOrEmail) {
            if (memberIdOrEmail == '') return;

            $scope.addOrganisationMember.errors = {};
            $scope.addOrganisationMember.loading = true;
            $scope.addOrganisationMember.success = false;

            var existingMemberId = Helpers.getItemIndexById($scope.users, memberIdOrEmail);

            if (existingMemberId != -1)
                return addExistingMember($scope.users[existingMemberId]);
            else
                return addNewMemberFromEmailAddress(memberIdOrEmail);
        };

        var addExistingMember = function (newMember) {
            $scope.addOrganisationMember.success = false;

            $http.post(SITE_RELATIVE_PATH + '/org/' + $scope.organisationid + '.json', {
                addMember: newMember.id
            }).then(function (response) {
                $scope.addOrganisationMember.loading = false;
                if (response.data.result == 'ok') {
                    $scope.addOrganisationMember.success = newMember.firstName + ' ' + newMember.lastName + ' has been successfully invited';
                } else if (response.data.result == 'error') {
                    $scope.addOrganisationMember.errors = response.data.errors;
                } else {
                    alert('unexpected error');
                    console.log(response.data);
                }
            });
        };
        var addNewMemberFromEmailAddress = function (emailAddress) {
            $http.post(SITE_RELATIVE_PATH + '/org/' + $scope.organisationid + '.json', {
                addEmail: emailAddress
            }).then(function (response) {
                $scope.addOrganisationMember.loading = false;
                if (response.data.result == 'ok') {
                    $scope.addOrganisationMember.success = response.data.successMessage;
                    /* if (response.data.user)
                     $scope.project.members.push(response.data.user); */
                } else if (response.data.result == 'error') {
                    $scope.addOrganisationMember.errors = response.data.errors;
                } else {
                    alert('unexpected error');
                    console.log(response.data);
                }
            });
        };

        $scope.removeMember = function (member) {
            var index = Helpers.getItemIndexById($scope.organisation.members, member.id);
            if (index > -1) {
                $scope.organisation.members.splice(index, 1);

                $http.post(SITE_RELATIVE_PATH + '/org/' + $attrs.organisationid + '.json', {
                    removeMember: member.id
                }).then(function (result) {
                    console.log(result.data);
                });
            }
        };

        $scope.requestToJoin = {};
        $scope.sendRequestToJoin = function () {
            $scope.requestToJoin.loading = true;
            $timeout(function () {
                $http.post(SITE_RELATIVE_PATH + '/org/' + $attrs.organisationid + '.json', {
                    requestToJoin: true
                }).then(function (result) {
                    $scope.requestToJoin.loading = false;
                    $scope.requestToJoin.requestSent = true;
                });
            }, 500);
        };
        $scope.approveRequestToJoin = function (member) {
            var index = Helpers.getItemIndexById($scope.organisation.memberRequests, member.id);
            if (index > -1) {
                $scope.organisation.memberRequests.splice(index, 1);
                $scope.organisation.members.push(member);

                $http.post(SITE_RELATIVE_PATH + '/org/' + $attrs.organisationid + '.json', {
                    approveRequestToJoin: member.id
                }).then(function (result) {
                    console.log(result.data);
                });
            }
        };
        $scope.rejectRequestToJoin = function (member) {
            var index = Helpers.getItemIndexById($scope.organisation.memberRequests, member.id);
            if (index > -1) {
                $scope.organisation.memberRequests.splice(index, 1);

                $http.post(SITE_RELATIVE_PATH + '/org/' + $attrs.organisationid + '.json', {
                    rejectRequestToJoin: member.id
                }).then(function (result) {
                    console.log(result.data);
                });
            }
        };

        $scope.cancelJoinRequest = function () {
            Helpers.swalWarning({
                options: {
                    title: "Cancel Join Request",
                    text: "Are you sure you want to cancel the join request?"
                },
                post: {
                    cancelJoinRequest: true
                },
                success: {
                    title: "Request Cancelled",
                    text: "Your request has been cancelled"
                },
                successCallback: function () {
                    location.reload();
                }
            });
        };
        $scope.joinOrganisation = function () {
            Helpers.swalWarning({
                options: {
                    title: "Join Organisation",
                    text: "Are you sure you want to join this organisation?"
                },
                post: {
                    joinOrganisation: true
                },
                success: {
                    title: "Success",
                    text: "Join request has been sent"
                },
                successCallback: function () {
                    location.reload();
                }
            })
        };
        $scope.leaveOrganisation = function () {
            Helpers.swalWarning({
                options: {
                    title: "Leave Organisation",
                    text: "Are you sure you want to leave this organisation?"
                },
                post: {
                    leaveOrganisation: true
                },
                success: {
                    title: "Success",
                    text: "You have left this organisation"
                },
                successCallback: function () {
                    location.reload();
                }
            })
        };

        $scope.followOrganisation = function () {
            Helpers.swalWarning({
                options: {
                    title: "Follow Organisation",
                    text: "Are you sure you want to follow this organisation?"
                },
                post: {
                    followOrganisation: true
                },
                success: {
                    title: "Success",
                    text: "You are now following this organisation."
                },
                successCallback: function () {
                    location.reload();
                }
            })
        };
        $scope.unfollowOrganisation = function () {
            Helpers.swalWarning({
                options: {
                    title: "Unfollow Organisation",
                    text: "Are you sure you want to unfollow this organisation?"
                },
                post: {
                    unfollowOrganisation: true
                },
                success: {
                    title: "Success",
                    text: "You won't receive any more news regarding this organisation."
                },
                successCallback: function () {
                    location.reload();
                }
            })
        };
    });