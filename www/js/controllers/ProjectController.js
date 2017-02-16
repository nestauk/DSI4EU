angular
    .module(angularAppName)
    .controller('ProjectController', function ($scope, $http, $attrs, $timeout, $sce) {
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

        var jsonUrl = $attrs.jsonurl;

        // Get Project Details
        $http.get(jsonUrl)
            .then(function (response) {
                $scope.project = response.data || {};
            });

        $scope.confirmDelete = function (url) {
            swal({
                title: translate.get('Delete project'),
                text: translate.get('Are you sure you want to delete this project?'),
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
                        title: translate.get("Deleted"),
                        text: translate.get("The project has been deleted."),
                        type: "success"
                    }, function () {
                        window.location.href = url
                    });
                }
            });
        };
        $scope.report = function () {
            swal({
                title: translate.get("Report this project"),
                text: translate.get("Please tell us why you are reporting this project"),
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
                inputPlaceholder: translate.get("Reason for reporting")
            }, function (inputValue) {
                if (inputValue === false) return false;

                if (inputValue === "") {
                    swal.showInputError(translate.get("Please write your reason for reporting."));
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
                        title: translate.get("Reported"),
                        text: translate.get("Thank you for your report"),
                        type: "success"
                    });
                }
            });
        };

        $scope.cancelJoinRequest = function () {
            Helpers.swalWarning({
                options: {
                    title: translate.get("Cancel Join Request"),
                    text: translate.get("Are you sure you want to cancel the join request?")
                },
                post: {
                    cancelJoinRequest: true
                },
                success: {
                    title: translate.get("Request Cancelled"),
                    text: translate.get("Your request has been cancelled.")
                },
                successCallback: function () {
                    location.reload();
                }
            });
        };
        $scope.joinProject = function () {
            Helpers.swalWarning({
                options: {
                    title: translate.get("Join Project"),
                    text: translate.get("Are you sure you want to join this project?")
                },
                post: {
                    joinProject: true
                },
                success: {
                    title: "Success",
                    text: translate.get("Your join request has been sent.")
                },
                successCallback: function () {
                    location.reload();
                }
            })
        };
        $scope.leaveProject = function () {
            Helpers.swalWarning({
                options: {
                    title: translate.get("Leave Project"),
                    text: translate.get("Are you sure you want to leave this project?")
                },
                post: {
                    leaveProject: true
                },
                success: {
                    title: translate.get("Success"),
                    text: translate.get("You have left this project")
                },
                successCallback: function () {
                    location.reload();
                }
            })
        };

        $scope.followProject = function () {
            Helpers.swalWarning({
                options: {
                    title: translate.get("Follow Project"),
                    text: translate.get("Are you sure you want to follow this project?")
                },
                post: {
                    followProject: true
                },
                success: {
                    title: translate.get("Success"),
                    text: translate.get("You are now following this project.")
                },
                successCallback: function () {
                    location.reload();
                }
            })
        };
        $scope.unfollowProject = function () {
            Helpers.swalWarning({
                options: {
                    title: translate.get("Unfollow Project"),
                    text: translate.get("Are you sure you want to unfollow this project?")
                },
                post: {
                    unfollowProject: true
                },
                success: {
                    title: translate.get("Success"),
                    text: translate.get("You won't receive any more news from this project.")
                },
                successCallback: function () {
                    location.reload();
                }
            })
        };

        $scope.addPost = function () {
            $http.post(window.location.href, {
                addPost: tinymce.activeEditor.getContent()
            }).then(function (response) {
                if (response.data.result == 'ok') {
                    $('.new-post-bg.bg-blur').hide();
                    $scope.project.posts = response.data.posts;
                } else {
                    console.log(response.data);
                }
            });
        };

        $scope.renderHtml = function (html_code) {
            return $sce.trustAsHtml(html_code);
        };
    })
    .controller('ProjectPostController', function ($scope, $http, $timeout, $sce) {
        $scope.submitComment = function (post) {
            var data = {
                addPostComment: true,
                post: post.id,
                comment: post.newComment
            };
            post.newComment = '';
            $http.post(SITE_RELATIVE_PATH + '/projectPost/' + post.id + '.json', data)
                .then(function (response) {
                    if (!post.comments)
                        post.comments = [];

                    post.comments.unshift(response.data.comment);
                    post.commentsCount++;
                });
        };

        $scope.loadComments = function (post) {
            console.log('loadComments');
            if ($scope.showComments)
                return;

            $scope.loadingComments = true;
            $http.get(SITE_RELATIVE_PATH + '/projectPost/' + post.id + '.json')
                .then(function (response) {
                    $timeout(function () {
                        $scope.loadingComments = false;
                        post.comments = response.data.comments;
                        $scope.showComments = true;
                    }, 100);
                });
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
