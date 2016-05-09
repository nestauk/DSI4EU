var app = angular.module('DSIApp');

app.controller('ProjectController', function ($scope, $http, $attrs) {
    $scope.datePattern = '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])';
    $scope.getDateFrom = function (date) {
        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];

        var jsDate = new Date(date);
        return monthNames[jsDate.getMonth()] + ' ' + jsDate.getFullYear();
    };

    // Get Project Details
    (function () {
        $http.get(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json')
            .then(function (response) {
                $scope.project = response.data || {};
            });
    }());
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
                if (response.data.result == 'error')
                    alert(response.data);
            });
    };

    var addTagSelect = $('#Add-tag');
    $scope.addTag = function () {
        var newTag = addTagSelect.select2().val();
        addTagSelect.select2().val('').trigger("change");

        if (newTag == '')
            return;

        var index = $scope.project.tags.indexOf(newTag);
        if (index == -1) {
            $scope.project.tags.push(newTag);
            $scope.project.tags.sort();

            $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', {
                addTag: newTag
            }).then(function (result) {
                console.log(result.data);
            });
        }
    };
    $scope.removeTag = function (tag) {
        removeTag({
            tag: tag,
            currentTags: $scope.project.tags,
            postFields: {removeTag: tag}
        });
    };

    var addImpactTagASelect = $('#Add-impact-tag-a');
    $scope.addImpactTagA = function () {
        var newTag = addImpactTagASelect.select2().val();
        addImpactTagASelect.select2().val('').trigger("change");

        if (newTag == '')
            return;

        var index = $scope.project.impactTagsA.indexOf(newTag);
        if (index == -1) {
            $scope.project.impactTagsA.push(newTag);
            $scope.project.impactTagsA.sort();

            $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', {
                addImpactTagA: newTag
            }).then(function (result) {
                console.log(result.data);
            });
        }
    };
    $scope.removeImpactTagA = function (tag) {
        removeTag({
            tag: tag,
            currentTags: $scope.project.impactTagsA,
            postFields: {removeImpactTagA: tag}
        });
    };

    var addImpactTagBSelect = $('#Add-impact-tag-b');
    $scope.addImpactTagB = function () {
        var newTag = addImpactTagBSelect.select2().val();
        addImpactTagBSelect.select2().val('').trigger("change");

        if (newTag == '')
            return;

        var index = $scope.project.impactTagsB.indexOf(newTag);
        if (index == -1) {
            $scope.project.impactTagsB.push(newTag);
            $scope.project.impactTagsB.sort();

            $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', {
                addImpactTagB: newTag
            }).then(function (result) {
                console.log(result.data);
            });
        }
    };
    $scope.removeImpactTagB = function (tag) {
        removeTag({
            tag: tag,
            currentTags: $scope.project.impactTagsB,
            postFields: {removeImpactTagB: tag}
        });
    };

    var addImpactTagCSelect = $('#Add-impact-tag-c');
    $scope.addImpactTagC = function () {
        var newTag = addImpactTagCSelect.select2().val();
        addImpactTagCSelect.select2().val('').trigger("change");

        if (newTag == '')
            return;

        var index = $scope.project.impactTagsC.indexOf(newTag);
        if (index == -1) {
            $scope.project.impactTagsC.push(newTag);
            $scope.project.impactTagsC.sort();

            $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', {
                addImpactTagC: newTag
            }).then(function (result) {
                console.log(result.data);
            });
        }
    };
    $scope.removeImpactTagC = function (tag) {
        removeTag({
            tag: tag,
            currentTags: $scope.project.impactTagsC,
            postFields: {removeImpactTagC: tag}
        });
    };


    // Get Tags List
    (function () {
        $http.get(SITE_RELATIVE_PATH + '/tags-for-projects.json')
            .then(function (result) {
                addTagSelect.select2({
                    data: result.data
                });
            });
    }());
    // Get Impact Tags List
    (function () {
        $http.get(SITE_RELATIVE_PATH + '/impact-tags.json')
            .then(function (result) {
                addImpactTagASelect.select2({data: result.data});
                addImpactTagBSelect.select2({data: result.data});
                addImpactTagCSelect.select2({data: result.data});
            });
    }());

    var removeTag = function (data) {
        var index = data.currentTags.indexOf(data.tag);
        if (index > -1) {
            data.currentTags.splice(index, 1);

            $http.post(SITE_RELATIVE_PATH + '/project/' + $attrs.projectid + '.json', data.postFields)
                .then(function (result) {
                    console.log(result.data);
                });
        }
    }
});