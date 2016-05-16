var app = angular.module('DSIApp');

app.controller('UserController', function ($scope, $http) {
    $scope.skills = [];
    $scope.languages = [];
    $scope.links = [];

    $http.get(SITE_RELATIVE_PATH + '/profile/' + profileUserID + '/details.json')
        .then(function (result) {
            $scope.skills = result.data.tags || [];
            $scope.languages = result.data.languages || [];
            $scope.links = result.data.links.sort(sortUrls) || [];
            $scope.user = result.data.user || {};
        });

    $scope.updateUserBio = function () {
        $http.post(SITE_RELATIVE_PATH + '/profile/' + profileUserID + '/details.json', {
            updateBio: true,
            bio: $scope.user.bio
        }).then(function (result) {
            console.log(result.data);
        });
    };

    var addSkillSelect = $('#Add-skill');
    $scope.addSkills = function () {
        var newSkill = addSkillSelect.select2().val();
        addSkillSelect.select2().val('').trigger("change");

        if (newSkill == '')
            return;

        var index = $scope.skills.indexOf(newSkill);
        console.log(index);
        if (index == -1) {
            $scope.skills.push(newSkill);
            $scope.skills.sort();

            $http.post(SITE_RELATIVE_PATH + '/profile/' + profileUserID + '/details.json', {
                addSkill: newSkill
            }).then(function (result) {
                console.log(result.data);
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
                console.log(result.data);
            });
        }
    };
    // List Skills
    $http.get(SITE_RELATIVE_PATH + '/skills.json')
        .then(function (result) {
            addSkillSelect.select2({
                data: result.data
            });
        });

    var addLanguageSelect = $('#Add-language');
    $scope.addLanguages = function () {
        var newLanguage = addLanguageSelect.select2().val();
        addLanguageSelect.select2().val('').trigger("change");

        if (newLanguage == '')
            return;

        var index = $scope.languages.indexOf(newLanguage);
        if (index == -1) {
            $scope.languages.push(newLanguage);
            $scope.languages.sort();

            $http.post(SITE_RELATIVE_PATH + '/profile/' + profileUserID + '/details.json', {
                addLanguage: newLanguage
            }).then(function (result) {
                console.log(result.data);
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
                console.log(result.data);
            });
        }
    };
    // List Languages
    $http.get(SITE_RELATIVE_PATH + '/languages.json')
        .then(function (result) {
            addLanguageSelect.select2({
                data: result.data
            });
        });

    var addLink = $('#Add-link');
    $scope.newLink = '';
    $scope.addLink = function () {
        if ($scope.newLink == '')
            return;

        var index = $scope.links.indexOf($scope.newLink);
        if (index == -1) {
            $scope.links.push($scope.newLink);
            $scope.links.sort(sortUrls);

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

    $scope.getUrlIcon = function (url) {
        switch (getUrlType(url)) {
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

    var sortUrls = function (x, y) {
        var levels = {
            'facebook': 1,
            'twitter': 2,
            'gitHub': 3,
            'googlePlus': 4,
            'www': 5
        };

        var xLevel = levels[getUrlType(x)];
        var yLevel = levels[getUrlType(y)];

        if (xLevel == yLevel)
            return x > y;
        else
            return xLevel > yLevel;
    };

    var getUrlType = function (url) {
        if (/^(https?:\/\/)?((w{3}\.)?)twitter\.com\//i.test(url))
            return 'twitter';
        if (/^(https?:\/\/)?((w{3}\.)?)github\.com\//i.test(url))
            return 'gitHub';
        if (/^(https?:\/\/)?plus\.google\.com\//i.test(url))
            return 'googlePlus';
        if (/^(https?:\/\/)?((w{3}\.)?)facebook\.com\//i.test(url))
            return 'facebook';

        return 'www';
    }
});