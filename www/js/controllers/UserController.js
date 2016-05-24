var app = angular.module('DSIApp');

app.controller('UserController', function ($scope, $http, $timeout, Upload) {
    $scope.editPanel = 'basicDetails';

    $scope.saveBasicDetails = function () {
        $scope.userEdit.loading = true;
        $scope.userEdit.errors = {};
        $timeout(function () {
            $http.post(SITE_RELATIVE_PATH + '/profile/' + profileUserID + '/details.json', {
                updateBasicDetails: true,
                firstName: $scope.userEdit.firstName,
                lastName: $scope.userEdit.lastName,
                jobTitle: $scope.userEdit.jobTitle,
                location: $scope.userEdit.location
            }).then(function (response) {
                console.log(response.data);
                $scope.userEdit.loading = false;
                if (response.data.code == 'ok') {
                    $scope.user.firstName = $scope.userEdit.firstName;
                    $scope.user.lastName = $scope.userEdit.lastName;
                    $scope.user.jobTitle = $scope.userEdit.jobTitle;
                    $scope.user.location = $scope.userEdit.location;
                } else {
                    $scope.userEdit.errors = response.data.errors;
                    console.log(response.data.errors);
                }
            });
        }, 500);
    };
    $scope.saveBio = function () {
        $scope.userEdit.loading = true;
        $scope.userEdit.errors = {};
        $timeout(function () {
            $http.post(SITE_RELATIVE_PATH + '/profile/' + profileUserID + '/details.json', {
                updateBio: true,
                bio: $scope.userEdit.bio
            }).then(function (response) {
                $scope.userEdit.loading = false;
                if (response.data.code == 'ok') {
                    $scope.user.bio = $scope.userEdit.bio;
                } else {
                    $scope.userEdit.errors = response.data.errors;
                    console.log(response.data.errors);
                }
            });
        }, 500);
    };
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
    $scope.uploadFiles = function (file, errFiles) {
        $scope.f = file;
        $scope.errFile = errFiles && errFiles[0];
        if (file) {
            file.upload = Upload.upload({
                url: SITE_RELATIVE_PATH + '/uploadProfilePicture',
                data: {file: file}
            });

            file.upload.then(function (response) {
                file.result = response.data;
                if (response.data.result == 'ok')
                    $scope.user.profilePic = response.data.imgPath;
                else if (response.data.result == 'error') {
                    $scope.errorMsg = response.data.errors;
                }
            }, function (response) {
                if (response.status > 0)
                    $scope.errorMsg = response.status + ': ' + response.data;
            }, function (evt) {
                file.progress = Math.min(100, parseInt(100.0 *
                    evt.loaded / evt.total));
            });
        }
    };

    // Skills
    (function () {
        $scope.skills = [];

        var addSkillSelect = $('#Add-skill');
        addSkillSelect.on("change", function (evt) {
            $scope.addSkill(
                Helpers.getFirstNonEmptyValue(
                    addSkillSelect.val()
                )
            );
            addSkillSelect.val(null).trigger("change.select2");
        });

        // List Skills
        $http.get(SITE_RELATIVE_PATH + '/skills.json')
            .then(function (result) {
                addSkillSelect.select2({
                    data: result.data
                });
            });

        $scope.addSkill = function (skill) {
            if (!skill || skill == '')
                return;

            var index = $scope.skills.indexOf(skill);
            // console.log(index);
            if (index == -1) {
                $scope.skills.push(skill);
                $scope.skills.sort();

                $http.post(SITE_RELATIVE_PATH + '/profile/' + profileUserID + '/details.json', {
                    addSkill: skill
                }).then(function (result) {
                    if (result.data != '') {
                        alert('unexpected error');
                        console.log(result.data);
                    }
                });
            }
        };
        $scope.removeSkill = function (skill) {
            var index = $scope.skills.indexOf(skill);
            if (index > -1) {
                $scope.skills.splice(index, 1);

                $http.post(SITE_RELATIVE_PATH + '/profile/' + profileUserID + '/details.json', {
                    removeSkill: skill
                }).then(function (result) {
                    if (result.data != '') {
                        alert('unexpected error');
                        console.log(result.data);
                    }
                });
            }
        };
    }());
    // Languages
    (function () {
        $scope.languages = [];
        var addLanguageSelect = $('#Add-language');
        addLanguageSelect.on("change", function (evt) {
            $scope.addLanguage(
                Helpers.getFirstNonEmptyValue(
                    addLanguageSelect.val()
                )
            );
            addLanguageSelect.val(null).trigger("change.select2");
        });

        // List Languages
        $http.get(SITE_RELATIVE_PATH + '/languages.json')
            .then(function (result) {
                addLanguageSelect.select2({
                    data: result.data
                });
            });

        $scope.addLanguage = function (language) {
            if (language == '')
                return;

            var index = $scope.languages.indexOf(language);
            if (index == -1) {
                $scope.languages.push(language);
                $scope.languages.sort();

                $http.post(SITE_RELATIVE_PATH + '/profile/' + profileUserID + '/details.json', {
                    addLanguage: language
                }).then(function (result) {
                    if (result.data) {
                        alert('unknown error');
                        console.log(result.data);
                    }
                });
            }
        };
        $scope.removeLanguage = function (language) {
            var index = $scope.languages.indexOf(language);
            if (index > -1) {
                $scope.languages.splice(index, 1);

                $http.post(SITE_RELATIVE_PATH + '/profile/' + profileUserID + '/details.json', {
                    removeLanguage: language
                }).then(function (result) {
                    if (result.data) {
                        alert('unknown error');
                        console.log(result.data);
                    }
                });
            }
        };
    }());
    // Links
    (function(){
        $scope.links = [];

        var addLink = $('#Add-link');
        $scope.newLink = '';
        $scope.addLink = function () {
            if ($scope.newLink == '')
                return;

            var index = $scope.links.indexOf($scope.newLink);
            if (index == -1) {
                $scope.links.push($scope.newLink);
                $scope.links.sort(Helpers.sortUrls);

                console.log(SITE_RELATIVE_PATH + '/profile/' + profileUserID + '/details.json');

                $http.post(SITE_RELATIVE_PATH + '/profile/' + profileUserID + '/details.json', {
                    addLink: $scope.newLink
                }).then(function (result) {
                    console.log(result.data);
                });
            }

            $scope.newLink = '';
        };
        $scope.removeLink = function (link) {
            var index = $scope.links.indexOf(link);
            if (index > -1) {
                $scope.links.splice(index, 1);

                $http.post(SITE_RELATIVE_PATH + '/profile/' + profileUserID + '/details.json', {
                    removeLink: link
                }).then(function (result) {
                    console.log(result.data);
                });
            }
        };
    }());
    // joinProject
    (function () {
        $scope.joinProject = {};
        $scope.joinProject.submit = function () {
            $scope.joinProject.success = false;
            $scope.joinProject.loading = true;
            $scope.joinProject.errors = {};

            $timeout(function () {
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
            }, 500);
        };
    }());
    // joinOrganisation
    (function () {
        $scope.joinOrganisation = {};
        $scope.joinOrganisation.submit = function () {
            $scope.joinOrganisation.success = false;
            $scope.joinOrganisation.loading = true;
            $scope.joinOrganisation.errors = {};

            $timeout(function () {
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
            }, 500);
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
    }
});