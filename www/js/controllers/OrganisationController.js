var app = angular.module('DSIApp');

app.controller('OrganisationController', function ($scope, $http, $attrs, $timeout) {
    var addTagSelect = $('#Add-tag');
    var editCountry = $('#Edit-country');
    var editCountryRegion = $('#Edit-countryRegion');

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
            $scope.loadingCountryRegions = true;
            $http.get(SITE_RELATIVE_PATH + '/countryRegions/' + countryID + '.json')
                .then(function (result) {
                    $timeout(function () {
                        editCountryRegion
                            .html("")
                            .select2({data: result.data})
                            .val($scope.organisation.countryRegion)
                            .trigger("change");
                        $scope.loadingCountryRegions = false;
                    }, 500);
                });
        }
    };
});