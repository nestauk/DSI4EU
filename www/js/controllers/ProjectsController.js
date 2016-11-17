angular
    .module(angularAppName)
    .controller('ProjectsController', function ($scope, $http, $attrs) {
        var projectsJsonUrl = $attrs.projectsjsonurl;
        var projectTagsJsonUrl = $attrs.projecttagsjsonurl;

        $scope.showAdvancedSearch = $attrs.showadvancedsearch ? true : false;

        $scope.letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
        $scope.filter = {
            countryID: '0',
            tagID: $attrs.searchtag,
            helpTagID: $attrs.searchhelptag,
            techTagID: $attrs.searchtechtag
        };
        $scope.searchName = $attrs.searchname;

        if (
            $scope.searchName == '' &&
            $scope.filter.countryID == '0' &&
            $scope.filter.tagID == '0' &&
            $scope.filter.helpTagID == '0' &&
            $scope.filter.techTagID == '0'
        )
            $scope.startLetter = 'A';
        else
            $scope.startLetter = '';

        $http.get(projectsJsonUrl)
            .then(function (result) {
                $scope.projects = result.data;

                $http.get(SITE_RELATIVE_PATH + '/countries.json')
                    .then(function (result) {
                        $scope.countries = filterUsedCountries(result.data, $scope.projects);
                    });
                $http.get(projectTagsJsonUrl)
                    .then(function (result) {
                        $scope.tags = filterUsedTags(result.data.tags, $scope.projects, 'tags');
                        $scope.impactHelpTags = filterUsedTags(result.data.impactTags, $scope.projects, 'helpTags');
                        $scope.impactTechTags = filterUsedTags(result.data.impactTags, $scope.projects, 'techTags');
                    });
            });

        function filterUsedCountries(countries, projects) {
            var existingCountryIDs = projects.map(function (project) {
                return project.countryID
            }).filter(function (id) {
                return id > 0
            }).filter(function (e, i, arr) {
                return arr.indexOf(e, i + 1) === -1;
            }).map(function (id) {
                return parseInt(id);
            });

            return countries.filter(function (country) {
                return $.inArray(country.id, existingCountryIDs) !== -1
            });
        }
        function filterUsedTags(tags, projects, tagKey) {
            var existingTagIDs = projects.map(function (project) {
                return project[tagKey];
            }).reduce(function (p, n) {
                return p.concat(n);
            }, []).filter(function (id) {
                return id > 0
            }).filter(function (e, i, arr) {
                return arr.indexOf(e, i + 1) === -1;
            }).map(function (id) {
                return parseInt(id);
            });

            return tags.filter(function (tag) {
                return $.inArray(tag.id, existingTagIDs) !== -1
            });
        }

        $scope.setStartLetter = function (letter) {
            $scope.startLetter = letter;
        };

        $scope.startsWithLetter = function (item) {
            if ($scope.startLetter == '')
                return true;

            var letterMatch = new RegExp($scope.startLetter, 'i');
            return !!letterMatch.test(item.name.substring(0, 1));
        };

        $scope.dsiFocus4 =
            $scope.dsiFocus8 =
                $scope.dsiFocus9 =
                    $scope.dsiFocus35 = false;

        $scope.projectHasDsiFocusTag = function () {
            return function (item) {
                if ($scope.dsiFocus4 == false && $scope.dsiFocus8 == false && $scope.dsiFocus9 == false && $scope.dsiFocus35 == false)
                    return true;

                if ($scope.dsiFocus4 == true && $.inArray(4, item.dsiFocusTags) === 0)
                    return true;
                if ($scope.dsiFocus8 == true && $.inArray(8, item.dsiFocusTags) === 0)
                    return true;
                if ($scope.dsiFocus9 == true && $.inArray(9, item.dsiFocusTags) === 0)
                    return true;
                if ($scope.dsiFocus35 == true && $.inArray(35, item.dsiFocusTags) === 0)
                    return true;

                return false;
            }
        };

        $scope.projectInCountry = function () {
            return function (item) {
                if ($scope.filter.countryID == '0')
                    return true;

                return $scope.filter.countryID == item.countryID;
            }
        };

        $scope.projectHasTag = function () {
            return function (item) {
                if ($scope.filter.tagID == '0')
                    return true;

                return $.inArray($scope.filter.tagID, item.tags) !== -1;
            }
        };

        $scope.projectHasHelpTag = function () {
            return function (item) {
                if ($scope.filter.helpTagID == '0')
                    return true;

                return $.inArray($scope.filter.helpTagID, item.helpTags) !== -1;
            }
        };

        $scope.projectHasTechTag = function () {
            return function (item) {
                if ($scope.filter.techTagID == '0')
                    return true;

                return $.inArray($scope.filter.techTagID, item.techTags) !== -1;
            }
        }
    });