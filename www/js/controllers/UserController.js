angular
    .module(angularAppName)
    .controller('UserController', function ($scope, $http, $attrs, Upload) {
        var profileUserID = $attrs.profileuserid;
        var askForPermanentLogin = $attrs.askforpermanentlogin;

        $scope.getUrlIcon = function (url) {
            switch (Helpers.getUrlType(url)) {
                case 'facebook':
                    return 'social-1_square-facebook.svg';
                case 'twitter':
                    return 'social-1_square-twitter.svg';
                case 'gitHub':
                    return 'social-1_square-github.svg';
                case 'googlePlus':
                    return 'social-1_square-google-plus.svg';
                default:
                    return 'www.png';
            }
        };

        if (askForPermanentLogin) {
            swal({
                title: "Welcome back",
                text: "Would you like to remember you next time when you visit the website?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                closeOnCancel: false,
                showLoaderOnConfirm: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No"
            }, function (confirm) {
                if (confirm) {
                    $http.post(window.location.pathname + '.json', {
                        permanentLogin: true
                    }).then(function (response) {
                        $scope.joinProject.loading = false;
                        if (response.data.code == 'ok') {
                            swal({
                                title: "Success",
                                text: "You will automaticaly be logged in next time when you visit the site.",
                                type: "success"
                            }, function () {
                                window.location.href = window.location.pathname;
                            });
                        } else {
                            alert('unexpected error');
                            console.log(response);
                        }
                    });
                } else {
                    window.location.href = window.location.pathname;
                }
            });
        }

        // joinProject
        (function () {
            $scope.joinProject = {};
            $scope.joinProject.submit = function () {
                $scope.joinProject.success = false;
                $scope.joinProject.loading = true;
                $scope.joinProject.errors = {};

                $http.post(SITE_RELATIVE_PATH + '/profile/' + profileUserID + '/details.json', {
                    joinProject: true,
                    project: $scope.joinProject.data.project
                }).then(function (response) {
                    $scope.joinProject.loading = false;
                    if (response.data.code == 'ok') {
                        $scope.joinProject.success = true;
                    } else if (response.data.code == 'error') {
                        $scope.joinProject.errors = response.data.errors;
                    } else {
                        alert('unexpected error');
                        console.log(response);
                    }
                });
            };
        }());
        // joinOrganisation
        (function () {
            $scope.joinOrganisation = {};
            $scope.joinOrganisation.submit = function () {
                $scope.joinOrganisation.success = false;
                $scope.joinOrganisation.loading = true;
                $scope.joinOrganisation.errors = {};

                $http.post(SITE_RELATIVE_PATH + '/profile/' + profileUserID + '/details.json', {
                    joinOrganisation: true,
                    organisation: $scope.joinOrganisation.data.organisation
                }).then(function (response) {
                    $scope.joinOrganisation.loading = false;
                    if (response.data.code == 'ok') {
                        $scope.joinOrganisation.success = true;
                    } else if (response.data.code == 'error') {
                        $scope.joinOrganisation.errors = response.data.errors;
                    } else {
                        alert('unexpected error');
                        console.log(response);
                    }
                });
            };
        }());

        // Get User Details
        $http.get(SITE_RELATIVE_PATH + '/profile/' + profileUserID + '/details.json')
            .then(function (result) {
                $scope.skills = result.data.tags || [];
                $scope.languages = result.data.languages || [];
                $scope.links = result.data.links.sort(Helpers.sortUrls) || [];
                $scope.user = result.data.user || {};
                $scope.userEdit = Helpers.copyAllFieldsFrom($scope.user);
            });

        var Helpers = {
            getFirstNonEmptyValue: function (values) {
                for (var i in values) {
                    if (values[i] != '')
                        return values[i];
                }
                return null;
            },
            copyAllFieldsFrom: function (currentObject) {
                var newObject = {};
                for (var k in currentObject) newObject[k] = currentObject[k];
                return newObject;
            },
            getUrlType: function (url) {
                if (/^(https?:\/\/)?((w{3}\.)?)twitter\.com\//i.test(url))
                    return 'twitter';
                if (/^(https?:\/\/)?((w{3}\.)?)github\.com\//i.test(url))
                    return 'gitHub';
                if (/^(https?:\/\/)?plus\.google\.com\//i.test(url))
                    return 'googlePlus';
                if (/^(https?:\/\/)?((w{3}\.)?)facebook\.com\//i.test(url))
                    return 'facebook';

                return 'www';
            },
            sortUrls: function (x, y) {
                var levels = {
                    'facebook': 1,
                    'twitter': 2,
                    'gitHub': 3,
                    'googlePlus': 4,
                    'www': 5
                };

                var xLevel = levels[Helpers.getUrlType(x)];
                var yLevel = levels[Helpers.getUrlType(y)];

                if (xLevel == yLevel)
                    return x > y;
                else
                    return xLevel > yLevel;
            }
        };

        $scope.setDisabled = function (disable) {
            swal({
                title: disable ? "Disable the user" : "Re-enable the user",
                text: disable ?
                    "Are you sure you want to disable this user?" :
                    "Are you sure you want to re-enable this user?",
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
                            setUserDisabled: disable,
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
                        title: disable ? "Deleted" : "Re-enabled",
                        text: disable ?
                            "This user has been disabled." :
                            "This user has been re-enabled",
                        type: "success"
                    }, function () {
                        window.location.href = url
                    });
                }
            });
        };

        $scope.report = function () {
            swal({
                title: "Report this user",
                text: "Please tell us why you are reporting this profile",
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
    });