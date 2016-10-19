angular
    .module(angularAppName)
    .controller('OrganisationController', function ($scope, $http, $attrs, $timeout) {
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
                console.log(response.data);

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
            console.log('add email: ' + emailAddress);

            $http.post(SITE_RELATIVE_PATH + '/org/' + $scope.organisationid + '.json', {
                addEmail: emailAddress
            }).then(function (response) {
                $scope.addOrganisationMember.loading = false;
                console.log(response.data);

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
                    console.log(result.data);
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
            }
        }
    });