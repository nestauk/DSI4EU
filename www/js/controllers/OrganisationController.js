angular
    .module(angularAppName)
    .controller('OrganisationController', function ($scope, $http, $attrs, $timeout) {
        var addTagSelect = $('#Add-tag');
        var editCountry = $('#Edit-country');
        var editCountryRegion = $('#Edit-countryRegion');
        var addMemberSelect = $('#Add-member');

        $scope.organisationid = $attrs.organisationid;

        $scope.updateBasic = function () {
            var data = {
                updateBasic: true,
                name: $scope.organisation.name,
                description: $scope.organisation.description,
                address: $scope.organisation.address,
                organisationTypeId: $scope.organisation.organisationTypeId,
                organisationSizeId: $scope.organisation.organisationSizeId
            };

            $http.post(SITE_RELATIVE_PATH + '/org/' + $attrs.organisationid + '.json', data)
                .then(function (response) {
                    console.log(response.data);
                    if (response.data.result == 'error') {
                        alert('error');
                        console.log(response.data);
                    }
                });
        };

        $scope.addTag = function () {
            var newTag = addTagSelect.select2().val();
            addTag({
                tag: newTag,
                selectBox: addTagSelect,
                currentTags: $scope.organisation.tags,
                postFields: {addTag: newTag}
            });
        };
        $scope.removeTag = function (tag) {
            removeTag({
                tag: tag,
                currentTags: $scope.organisation.tags,
                postFields: {removeTag: tag}
            });
        };

        $scope.addMember = function () {
            var newMemberID = addMemberSelect.select2().val();
            addMemberSelect.select2().val('').trigger("change");

            if (newMemberID == '') return;

            addNewMember(newMemberID);
        };

        $scope.addOrganisationMember = {};
        var addNewMember = function (memberIdOrEmail) {
            if (memberIdOrEmail == '') return;

            $scope.addOrganisationMember.errors = {};
            $scope.addOrganisationMember.loading = true;
            $scope.addOrganisationMember.success = false;

            $timeout(function () {
                var existingMemberId = Helpers.getItemIndexById($scope.users, memberIdOrEmail);

                if (existingMemberId != -1)
                    return addExistingMember($scope.users[existingMemberId]);
                else
                    return addNewMemberFromEmailAddress(memberIdOrEmail);

            }, 500);
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

        // Get Organisation Details
        $http.get(SITE_RELATIVE_PATH + '/org/' + $attrs.organisationid + '.json')
            .then(function (response) {
                $scope.organisation = response.data || {};
                console.log($scope.organisation);
                listCountries();
            });

        // List Tags
        $http.get(SITE_RELATIVE_PATH + '/tags-for-organisations.json')
            .then(function (result) {
                addTagSelect.select2({
                    data: result.data
                });
            });
        // List Users
        $http.get(SITE_RELATIVE_PATH + '/users.json')
            .then(function (result) {
                $scope.users = result.data;
                addMemberSelect.select2({data: result.data});
            });

        var addTag = function (data) {
            data.selectBox.select2().val('').trigger("change");

            if (data.tag == '')
                return;

            var index = data.currentTags.indexOf(data.tag);
            if (index == -1) {
                data.currentTags.push(data.tag);
                data.currentTags.sort();

                $http.post(SITE_RELATIVE_PATH + '/org/' + $attrs.organisationid + '.json', data.postFields)
                    .then(function (result) {
                        console.log(result.data);
                    });
            }
        };
        var removeTag = function (data) {
            var index = data.currentTags.indexOf(data.tag);
            if (index > -1) {
                data.currentTags.splice(index, 1);

                $http.post(SITE_RELATIVE_PATH + '/org/' + $attrs.organisationid + '.json', data.postFields)
                    .then(function (result) {
                        console.log(result.data);
                    });
            }
        };

        var listCountries = function () {
            $http.get(SITE_RELATIVE_PATH + '/countries.json')
                .then(function (result) {
                    editCountry.select2({data: result.data});
                    editCountry.on("change", function () {
                        listCountryRegions(editCountry.val());
                    });
                    editCountry.val($scope.organisation.countryID).trigger("change");
                });
        };
        var listCountryRegions = function (countryID) {
            countryID = parseInt(countryID) || 0;
            if (countryID > 0) {
                $scope.regionsLoaded = false;
                $scope.regionsLoading = true;
                $http.get(SITE_RELATIVE_PATH + '/countryRegions/' + countryID + '.json')
                    .then(function (result) {
                        $timeout(function () {
                            editCountryRegion
                                .html("")
                                .select2({data: result.data})
                                .val($scope.organisation.countryRegion)
                                .trigger("change");
                            $scope.regionsLoaded = true;
                            $scope.regionsLoading = false;
                        }, 500);
                    });
            }
        };

        $scope.savingCountryRegion = {};
        $scope.saveCountryRegion = function () {
            $scope.savingCountryRegion.loading = true;
            $scope.savingCountryRegion.saved = false;
            $http.post(SITE_RELATIVE_PATH + '/org/' + $attrs.organisationid + '.json', {
                updateCountryRegion: true,
                countryID: editCountry.val(),
                region: editCountryRegion.val()
            }).then(function (result) {
                $timeout(function () {
                    $scope.savingCountryRegion.loading = false;
                    $scope.savingCountryRegion.saved = true;
                    console.log(result.data);
                }, 500);
            });
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

        $scope.newProjectName = '';
        $scope.addNewProject = {};
        $scope.addProject = function () {
            if ($scope.newProjectName != '') {
                $scope.addNewProject.loading = true;
                $timeout(function () {
                    $http.post(SITE_RELATIVE_PATH + '/org/' + $attrs.organisationid + '.json', {
                        createProject: $scope.newProjectName
                    }).then(function (response) {
                        if (response.data.result == 'ok')
                            window.location.href = response.data.url;
                        else
                            console.log(response.data);
                    });
                }, 500);
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