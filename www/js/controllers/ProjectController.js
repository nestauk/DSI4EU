var app = angular.module('DSIApp');

app.controller('ProjectController', function ($scope, $http, $attrs, $timeout, $sce) {
    /*
     var addTagSelect = $('#Add-tag');
     var addImpactTagASelect = $('#Add-impact-tag-a');
     var addImpactTagBSelect = $('#Add-impact-tag-b');
     var addImpactTagCSelect = $('#Add-impact-tag-c');
     var addOrganisationSelect = $('#Add-organisation');
     var editCountry = $('#Edit-country');
     var editCountryRegion = $('#Edit-countryRegion');

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

     $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', data)
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
     $http.get(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json')
     .then(function (response) {
     $scope.project = response.data || {};
     console.log($scope.project.posts);
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

     $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', data.postFields)
     .then(function (result) {
     console.log(result.data);
     });
     }
     };
     var removeTag = function (data) {
     var index = data.currentTags.indexOf(data.tag);
     if (index > -1) {
     data.currentTags.splice(index, 1);

     $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', data.postFields)
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
     $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', {
     requestToJoin: true
     }).then(function (result) {
     $scope.requestToJoin.loading = false;
     $scope.requestToJoin.requestSent = true;
     console.log(result.data);
     });
     }, 500);
     };
     $scope.approveRequestToJoin = function (member) {
     var index = getItemIndexById($scope.project.memberRequests, member.id);
     if (index > -1) {
     $scope.project.memberRequests.splice(index, 1);
     $scope.project.members.push(member);

     $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', {
     approveRequestToJoin: member.id
     }).then(function (result) {
     console.log(result.data);
     });
     }
     };
     $scope.rejectRequestToJoin = function (member) {
     var index = getItemIndexById($scope.project.memberRequests, member.id);
     if (index > -1) {
     $scope.project.memberRequests.splice(index, 1);

     $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', {
     rejectRequestToJoin: member.id
     }).then(function (result) {
     console.log(result.data);
     });
     }
     };
     $scope.saveCountryRegion = function () {
     $scope.savingCountryRegion.loading = true;
     $scope.savingCountryRegion.saved = false;
     $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', {
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

     var existingIndex = getItemIndexById($scope.project.organisationProjects, organisation);
     if (existingIndex > -1)
     return;

     var index = getItemIndexById($scope.organisations, organisation);
     if (index > -1) {
     $scope.project.organisationProjects.push({
     'id': $scope.organisations[index].id,
     'name': $scope.organisations[index].text,
     'url': $scope.organisations[index].url
     });

     $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', {
     newOrganisationID: organisation
     }).then(function (result) {
     console.log(result.data);
     });
     }
     };
     $scope.addPost = function () {
     $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', {
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

     var getItemIndexById = function (pool, id) {
     for (var i in pool) {
     if (pool[i].id == id)
     return i;
     }
     return -1;
     };

     $scope.renderHtml = function (html_code) {
     return $sce.trustAsHtml(html_code);
     };
     */

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
        var addMemberSelect = $('#Add-member');
        addMemberSelect.on("change", function (evt) {
            console.log(addMemberSelect.val());
            $scope.addMember(
                Helpers.getFirstNonEmptyValue(
                    addMemberSelect.val()
                )
            );
            addMemberSelect.val(null).trigger("change.select2");
        });

        $scope.addMember = function (member) {
            if (member == '') return;

            var newMember = null;
            for (var i in $scope.users) {
                if (member == $scope.users[i].id)
                    newMember = $scope.users[i];
            }
            if (newMember)
                return $scope.addExistingMember(newMember);
            else {
                /////
            }
        };
        $scope.addExistingMember = function (newMember) {
            $scope.project.members.push(newMember);
            $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', {
                addMember: member
            }).then(function (result) {
                console.log(result.data);
            });
        };

        $scope.removeMember = function (member) {
            var index = getItemIndexById($scope.project.members, member.id);
            if (index > -1) {
                $scope.project.members.splice(index, 1);

                $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', {
                    removeMember: member.id
                }).then(function (result) {
                    console.log(result.data);
                });
            }
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
        }
    }
});