angular
    .module(angularAppName)
    .controller('ProjectController', function ($scope, $http, $attrs, $timeout, $sce) {
        $scope.projectid = $attrs.projectid;

        // Get Project Details
        $http.get(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json')
            .then(function (response) {
                $scope.project = response.data || {};
                console.log(response.data);
            });

        $scope.datePattern = '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])';
        $scope.getDateFrom = function (date) {
            var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ];

            var jsDate = new Date(date);
            return monthNames[jsDate.getMonth()] + ' ' + jsDate.getFullYear();
        };

        $scope.confirmDelete = function (url) {
            swal({
                title: "Delete the project",
                text: "Are you sure you want to delete this project?",
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
                            deleteProject: true,
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
                        text: "The project has been deleted.",
                        type: "success"
                    }, function () {
                        window.location.href = url
                    });
                }
            });
        };

        $scope.report = function () {
            swal({
                title: "Report this project",
                text: "Please tell us why you are reporting this project",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
                inputPlaceholder: "Reason for reporting"
            }, function (inputValue) {
                if (inputValue === false) return false;

                if (inputValue === "") {
                    swal.showInputError("You need to write something!");
                    return false
                }

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
                            report: true,
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
                        text: "Thank you for your report",
                        type: "success"
                    });
                }
            });
        };

        $scope.requestToJoin = {};
        $scope.sendRequestToJoin = function () {
            $scope.requestToJoin.loading = true;
            $timeout(function () {
                $http.post(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json', {
                    requestToJoin: true
                }).then(function (result) {
                    $scope.requestToJoin.loading = false;
                    $scope.requestToJoin.requestSent = true;
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

        $scope.approveInvitationToJoin = function () {
            var data = {
                approveProjectInvitation: true,
                projectID: $scope.projectid
            };
            $http.post(SITE_RELATIVE_PATH + '/dashboard.json', data)
                .then(function (response) {
                    if (response.data.code == 'ok') {
                        swal(response.data.message.title, response.data.message.text, "success");
                        $scope.invitationActioned = true;
                    } else if (response.data.code == 'error') {

                    }
                });
        };
        $scope.rejectInvitationToJoin = function () {
            swal({
                title: "Are you sure?",
                text: "You will not be able to join this project at this time",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, continue!",
                closeOnConfirm: false
            }, function () {
                $http.post(SITE_RELATIVE_PATH + '/dashboard.json', {
                    rejectProjectInvitation: true,
                    projectID: $scope.projectid
                })
                    .then(function (response) {
                        if (response.data.code == 'ok') {
                            swal(response.data.message.title, response.data.message.text, "warning");
                            $scope.invitationActioned = true;
                        } else if (response.data.code == 'error') {

                        }
                    });
            });
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

        // Members
        (function () {
            var addNewMember = function (memberIdOrEmail) {
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
                $scope.addProjectMember.success = false;

                $http.post(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json', {
                    addMember: newMember.id
                }).then(function (response) {
                    $scope.addProjectMember.loading = false;

                    if (response.data.result == 'ok') {
                        $scope.addProjectMember.success = newMember.firstName + ' ' + newMember.lastName + ' has been successfully invited';
                    } else if (response.data.result == 'error') {
                        $scope.addProjectMember.errors = response.data.errors;
                    } else {
                        alert('unexpected error');
                        console.log(response.data);
                    }
                });
            };

            var addNewMemberFromEmailAddress = function (emailAddress) {
                $http.post(SITE_RELATIVE_PATH + '/project/' + $scope.projectid + '.json', {
                    addEmail: emailAddress
                }).then(function (response) {
                    $scope.addProjectMember.loading = false;
                    if (response.data.result == 'ok') {
                        $scope.addProjectMember.success = response.data.successMessage;
                        /* if (response.data.user)
                         $scope.project.members.push(response.data.user); */
                    } else if (response.data.result == 'error') {
                        $scope.addProjectMember.errors = response.data.errors;
                    } else {
                        alert('unexpected error');
                        console.log(response.data);
                    }
                });
            };

            $scope.updateAdminStatus = function (member) {
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
    })
    .controller('ProjectPostController', function ($scope, $http, $timeout, $sce) {
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
                        if (!$scope.post.comments)
                            $scope.post.comments = [];

                        $scope.post.comments.unshift(response.data.comment);
                        $scope.post.commentsCount++;
                    });
            };

            $scope.loadComments = function () {
                if ($scope.showComments)
                    return;

                $scope.loadingComments = true;
                $http.get(SITE_RELATIVE_PATH + '/projectPost/' + $scope.post.id + '.json')
                    .then(function (response) {
                        $timeout(function () {
                            $scope.loadingComments = false;
                            $scope.post.comments = response.data.comments;
                            $scope.showComments = true;
                        }, 100);
                    });
            }
        }
    })
    .controller('ProjectPostCommentController', function ($scope, $http, $timeout, $sce) {
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
