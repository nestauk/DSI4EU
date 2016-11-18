angular
    .module(angularAppName)
    .controller('OrganisationsController', function ($scope, $http, $interval, $attrs) {
        var organisationsJsonUrl = $attrs.organisationsjsonurl;
        var organisationTagsJsonUrl = $attrs.organisationtagsjsonurl;

        $scope.showAdvancedSearch = $attrs.showadvancedsearch ? true : false;

        $scope.letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
        $scope.filter = {
            countryID: '0',
            tagID: $attrs.searchtag,
            netwTagID: $attrs.searchnetwtag
        };
        $scope.searchName = $attrs.searchname;
        $scope.startLetter = '';
        $scope.organisations = [];

        $http.get(organisationsJsonUrl)
            .then(function (result) {
                $scope.loaded = true;
                $scope.organisations = result.data;

                $http.get(SITE_RELATIVE_PATH + '/countries.json')
                    .then(function (result) {
                        $scope.countries = filterUsedCountries(result.data, $scope.organisations);
                    });
                $http.get(organisationTagsJsonUrl)
                    .then(function (result) {
                        $scope.tags = filterUsedTags(result.data.tags, $scope.organisations, 'tags');
                        console.log($scope.tags);
                        $scope.netwTags = filterUsedTags(result.data.netwTags, $scope.organisations, 'netwTags');
                    });
            });

        function filterUsedCountries(countries, organisations) {
            var existingCountryIDs = organisations.map(function (organisation) {
                return organisation.countryID
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

        function filterUsedTags(tags, organisations, tagKey) {
            var existingTagIDs = organisations.map(function (organisation) {
                return organisation[tagKey];
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

        $scope.organisationInCountry = function () {
            return function (item) {
                if ($scope.filter.countryID == '0')
                    return true;

                return $scope.filter.countryID == item.countryID;
            }
        };

        $scope.organisationHasTag = function () {
            return function (item) {
                if ($scope.filter.tagID == '0')
                    return true;

                return $.inArray(parseInt($scope.filter.tagID), item.tags) !== -1;
            }
        };

        $scope.projectHasNetwTag = function () {
            return function (item) {
                if ($scope.filter.netwTagID == '0')
                    return true;

                return $.inArray(parseInt($scope.filter.netwTagID), item.netwTags) !== -1;
            }
        }
    });