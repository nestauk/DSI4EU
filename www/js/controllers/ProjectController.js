var app = angular.module('DSIApp');

app.controller('ProjectController', function ($scope, $http, $attrs, $timeout, $sce) {
    var addTagSelect = $('#Add-tag');
    var addImpactTagASelect = $('#Add-impact-tag-a');
    var addImpactTagBSelect = $('#Add-impact-tag-b');
    var addImpactTagCSelect = $('#Add-impact-tag-c');
    var addOrganisationSelect = $('#Add-organisation');
    var editCountry = $('#Edit-country');
    var editCountryRegion = $('#Edit-countryRegion');

    $scope.projectid = $attrs.projectid;
    $scope.datePattern = '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])';
    $scope.getDateFrom = function (date) {
        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];

        var jsDate = new Date(date);
        return monthNames[jsDate.getMonth()] + ' ' + jsDate.getFullYear();
    };

    $scope.updateBasic = function () {
        var data = {
            updateBasic: true,
            url: $scope.project.url,
            name: $scope.project.name,
            status: $scope.project.status,
            description: $scope.project.description,
            startDate: $scope.project.startDate,
            endDate: $scope.project.endDate
        };

        $http.post(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json', data)
            .then(function (response) {
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
            currentTags: $scope.project.tags,
            postFields: {addTag: newTag}
        });
    };
    $scope.removeTag = function (tag) {
        removeTag({
            tag: tag,
            currentTags: $scope.project.tags,
            postFields: {removeTag: tag}
        });
    };

    $scope.addImpactTagA = function () {
        var newTag = addImpactTagASelect.select2().val();
        addTag({
            tag: newTag,
            selectBox: addImpactTagASelect,
            currentTags: $scope.project.impactTagsA,
            postFields: {addImpactTagA: newTag}
        });
    };
    $scope.removeImpactTagA = function (tag) {
        removeTag({
            tag: tag,
            currentTags: $scope.project.impactTagsA,
            postFields: {removeImpactTagA: tag}
        });
    };

    $scope.addImpactTagB = function () {
        var newTag = addImpactTagBSelect.select2().val();
        addTag({
            tag: newTag,
            selectBox: addImpactTagBSelect,
            currentTags: $scope.project.impactTagsB,
            postFields: {addImpactTagB: newTag}
        });
    };
    $scope.removeImpactTagB = function (tag) {
        removeTag({
            tag: tag,
            currentTags: $scope.project.impactTagsB,
            postFields: {removeImpactTagB: tag}
        });
    };

    $scope.addImpactTagC = function () {
        var newTag = addImpactTagCSelect.select2().val();
        addTag({
            tag: newTag,
            selectBox: addImpactTagCSelect,
            currentTags: $scope.project.impactTagsC,
            postFields: {addImpactTagC: newTag}
        });
    };
    $scope.removeImpactTagC = function (tag) {
        removeTag({
            tag: tag,
            currentTags: $scope.project.impactTagsC,
            postFields: {removeImpactTagC: tag}
        });
    };

    // Get Project Details
    $http.get(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json')
        .then(function (response) {
            $scope.project = response.data || {};
            console.log(response.data);
            listCountries();
        });

    // List Tags
    $http.get(SITE_RELATIVE_PATH + '/tags-for-projects.json')
        .then(function (result) {
            addTagSelect.select2({
                data: result.data
            });
        });
    // List ImpactTags
    $http.get(SITE_RELATIVE_PATH + '/impact-tags.json')
        .then(function (result) {
            addImpactTagASelect.select2({data: result.data});
            addImpactTagBSelect.select2({data: result.data});
            addImpactTagCSelect.select2({data: result.data});
        });
    // List Organisations
    $http.get(SITE_RELATIVE_PATH + '/organisations.json')
        .then(function (result) {
            $scope.organisations = result.data;
            addOrganisationSelect.select2({data: result.data});
        });

    var addTag = function (data) {
        data.selectBox.select2().val('').trigger("change");

        if (data.tag == '')
            return;

        var index = data.currentTags.indexOf(data.tag);
        if (index == -1) {
            data.currentTags.push(data.tag);
            data.currentTags.sort();

            $http.post(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json', data.postFields)
                .then(function (result) {
                    console.log(result.data);
                });
        }
    };
    var removeTag = function (data) {
        var index = data.currentTags.indexOf(data.tag);
        if (index > -1) {
            data.currentTags.splice(index, 1);

            $http.post(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json', data.postFields)
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
                editCountry.val($scope.project.countryID).trigger("change");
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
                            .val($scope.project.countryRegion)
                            .trigger("change");
                        $scope.regionsLoaded = true;
                        $scope.regionsLoading = false;
                    }, 500);
                });
        }
    };

    $scope.requestToJoin = {};
    $scope.savingCountryRegion = {};
    $scope.sendRequestToJoin = function () {
        $scope.requestToJoin.loading = true;
        $timeout(function () {
            $http.post(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json', {
                requestToJoin: true
            }).then(function (result) {
                $scope.requestToJoin.loading = false;
                $scope.requestToJoin.requestSent = true;
                console.log(result.data);
            });
        }, 500);
    };
    $scope.approveRequestToJoin = function (member) {
        var index = Helpers.getItemIndexById($scope.project.memberRequests, member.id);
        if (index > -1) {
            $scope.project.memberRequests.splice(index, 1);
            $scope.project.members.push(member);

            $http.post(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json', {
                approveRequestToJoin: member.id
            }).then(function (result) {
                console.log(result.data);
            });
        }
    };
    $scope.rejectRequestToJoin = function (member) {
        var index = Helpers.getItemIndexById($scope.project.memberRequests, member.id);
        if (index > -1) {
            $scope.project.memberRequests.splice(index, 1);

            $http.post(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json', {
                rejectRequestToJoin: member.id
            }).then(function (result) {
                console.log(result.data);
            });
        }
    };
    $scope.saveCountryRegion = function () {
        $scope.savingCountryRegion.loading = true;
        $scope.savingCountryRegion.saved = false;
        $http.post(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json', {
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

    $scope.addOrganisation = function () {
        var organisation = addOrganisationSelect.select2().val();

        if (organisation == '')
            return;

        var existingIndex = Helpers.getItemIndexById($scope.project.organisationProjects, organisation);
        if (existingIndex > -1)
            return;

        var index = Helpers.getItemIndexById($scope.organisations, organisation);
        if (index > -1) {
            $scope.project.organisationProjects.push({
                'id': $scope.organisations[index].id,
                'name': $scope.organisations[index].text,
                'url': $scope.organisations[index].url
            });

            $http.post(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json', {
                newOrganisationID: organisation
            }).then(function (result) {
                console.log(result.data);
            });
        }
    };
    $scope.addPost = function () {
        $http.post(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json', {
            addPost: tinymce.activeEditor.getContent()
        }).then(function (response) {
                if (response.data.result == 'ok') {
                    $('.new-post-bg.bg-blur').hide();
                    $scope.project.posts = response.data.posts;
                } else {
                    console.log(response.data);
                }
            }
        );
    };

    $scope.renderHtml = function (html_code) {
        return $sce.trustAsHtml(html_code);
    };

    /*
     $scope.saveGMapPosition = function () {
     $http.get('http://maps.googleapis.com/maps/api/geocode/json?' +
     'latlng=' + selectedLocation.getPosition().lat() + ',' +
     selectedLocation.getPosition().lng() +
     '&sensor=false')
     .then(function (result) {
     if (result.data.status == 'OK') {
     $scope.possibleLocationsNotFound = false;
     console.log(result.data.status);
     var country = result.data.results.pop().formatted_address;
     console.log({
     country: country
     });
     $scope.possibleLocations = result.data.results
     } else {
     $scope.possibleLocationsNotFound = true;
     $scope.possibleLocations = [];
     }
     });
     }
     */

    // Members
    (function () {
        $scope.addProjectMember = {};
        var addMemberSelect = $('#Add-member');
        addMemberSelect.on("change", function (evt) {
            // console.log('addMemberSelect:onChange:val:' + addMemberSelect.val());
            addMember(
                Helpers.getFirstNonEmptyValue(
                    addMemberSelect.val()
                )
            );
            resetSelectField();
        });

        var resetSelectField = function () {
            addMemberSelect.val(null).trigger("change.select2");
        };

        var addMember = function (memberIdOrEmail) {
            if (memberIdOrEmail == '') return;

            $scope.addProjectMember.errors = {};
            $scope.addProjectMember.loading = true;
            $scope.addProjectMember.success = false;
            $scope.$apply();

            $timeout(function () {
                var existingMemberId = Helpers.getItemIndexById($scope.users, memberIdOrEmail);

                if (existingMemberId != -1)
                    return addExistingMember($scope.users[existingMemberId]);
                else
                    return addNewMemberFromEmailAddress(memberIdOrEmail);

            }, 500);
        };

        var addExistingMember = function (newMember) {
            $http.post(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json', {
                addMember: newMember.id
            }).then(function (response) {
                $scope.addProjectMember.loading = false;
                console.log(response.data);

                if (response.data.result == 'ok') {
                    $scope.project.members.push(newMember);
                } else if (response.data.result == 'error') {
                    $scope.addProjectMember.errors = response.data.errors;
                } else {
                    alert('unexpected error');
                    console.log(response.data);
                }
            });
        };

        var addNewMemberFromEmailAddress = function (emailAddress) {
            console.log('add email: ' + emailAddress);

            $http.post(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json', {
                addEmail: emailAddress
            }).then(function (response) {
                $scope.addProjectMember.loading = false;
                console.log(response.data);

                if (response.data.result == 'ok') {
                    $scope.addProjectMember.success = response.data.successMessage;
                    if (response.data.user)
                        $scope.project.members.push(response.data.user);
                } else if (response.data.result == 'error') {
                    $scope.addProjectMember.errors = response.data.errors;
                } else {
                    alert('unexpected error');
                    console.log(response.data);
                }
            });
        };

        $scope.removeMember = function (member) {
            var index = Helpers.getItemIndexById($scope.project.members, member.id);
            if (index > -1) {
                $scope.project.members.splice(index, 1);

                $http.post(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json', {
                    removeMember: member.id
                }).then(function (response) {
                    if (response.data.result != 'ok') {
                        alert('unexpected error');
                        console.log(response);
                    }
                });
            }
        };

        $scope.updateAdminStatus = function (member) {
            console.log(member);
            $http.post(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json', {
                setAdmin: true,
                member: member.id,
                isAdmin: member.isAdmin
            }).then(function (response) {
                if (response.data.result != 'ok') {
                    alert('unexpected error');
                    console.log(response);
                }
            });
        };


        // List Users
        $http.get(SITE_RELATIVE_PATH + '/users.json')
            .then(function (result) {
                $scope.users = result.data;
                addMemberSelect.select2({data: result.data});
            });
    }());

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

app.controller('ProjectPostController', function ($scope, $http, $timeout, $sce) {
    if ($scope.post) {
        $scope.submitComment = function () {
            var data = {
                addPostComment: true,
                post: $scope.post.id,
                comment: $scope.post.newComment
            };
            $scope.post.newComment = '';
            $http.post(SITE_RELATIVE_PATH + '/projectPost/' + $scope.post.id + '.json', data)
                .then(function (response) {
                    $timeout(function () {
                        if (!$scope.post.comments)
                            $scope.post.comments = [];

                        $scope.post.comments.unshift(response.data.comment);
                        $scope.post.commentsCount++;
                        console.log(response.data);
                    }, 500);
                });
        };

        $scope.loadComments = function () {
            $http.get(SITE_RELATIVE_PATH + '/projectPost/' + $scope.post.id + '.json')
                .then(function (response) {
                    $scope.post.comments = response.data.comments;
                    $scope.showComments = true;
                });
        }
    }
});

app.controller('ProjectPostCommentController', function ($scope, $http, $timeout, $sce) {
    if ($scope.comment) {
        $scope.submitComment = function () {
            var data = {
                addReply: true,
                reply: $scope.comment.newReply
            };
            $scope.comment.newReply = '';
            $http.post(SITE_RELATIVE_PATH + '/projectPostComment/' + $scope.comment.id + '.json', data)
                .then(function (response) {
                    $timeout(function () {
                        if (!$scope.comment.replies)
                            $scope.comment.replies = [];

                        $scope.comment.replies.push(response.data.reply);
                    }, 500);
                });
        };
    }
});
